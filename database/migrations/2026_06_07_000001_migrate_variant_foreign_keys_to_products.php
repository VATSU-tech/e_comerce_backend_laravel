<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->migrateTableToProducts('cart_items');
        $this->migrateTableToProducts('order_items');
        $this->migrateTableToProducts('inventories');

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Variants were intentionally removed from the application. This data
        // migration is not reversible without recreating the variant catalogue.
    }

    private function migrateTableToProducts(string $table): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        if (! Schema::hasColumn($table, 'product_id')) {
            Schema::table($table, function (Blueprint $table): void {
                $table->unsignedBigInteger('product_id')->nullable()->after('id');
            });
        }

        if (Schema::hasTable('product_variants') && Schema::hasColumn($table, 'product_variant_id')) {
            DB::statement(<<<SQL
                UPDATE {$table}
                SET product_id = (
                    SELECT product_variants.product_id
                    FROM product_variants
                    WHERE product_variants.id = {$table}.product_variant_id
                )
                WHERE product_id IS NULL
                  AND product_variant_id IS NOT NULL
                  AND EXISTS (
                    SELECT 1
                    FROM product_variants
                    WHERE product_variants.id = {$table}.product_variant_id
                  )
            SQL);
        }

        DB::table($table)->whereNull('product_id')->delete();

        if (Schema::hasColumn($table, 'product_variant_id')) {
            $this->dropForeignIfPossible($table, 'product_variant_id');

            Schema::table($table, function (Blueprint $table): void {
                $table->dropColumn('product_variant_id');
            });
        }

        if ($table === 'cart_items') {
            $this->deduplicateCartItems();

            if (! $this->hasIndex('cart_items', 'cart_items_cart_id_product_id_unique')) {
                Schema::table('cart_items', function (Blueprint $table): void {
                    $table->unique(['cart_id', 'product_id'], 'cart_items_cart_id_product_id_unique');
                });
            }
        }
    }

    private function dropForeignIfPossible(string $table, string $column): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table($table, function (Blueprint $table) use ($column): void {
            $table->dropForeign([$column]);
        });
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        if (DB::getDriverName() === 'sqlite') {
            return collect(DB::select("PRAGMA index_list('{$table}')"))
                ->contains(fn (object $index): bool => $index->name === $indexName);
        }

        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->exists();
    }

    private function deduplicateCartItems(): void
    {
        $duplicates = DB::table('cart_items')
            ->select('cart_id', 'product_id', DB::raw('MIN(id) as keep_id'), DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('cart_id', 'product_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('cart_items')
                ->where('id', $duplicate->keep_id)
                ->update(['quantity' => $duplicate->total_quantity]);

            DB::table('cart_items')
                ->where('cart_id', $duplicate->cart_id)
                ->where('product_id', $duplicate->product_id)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }
    }
};
