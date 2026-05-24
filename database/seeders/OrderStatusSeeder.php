<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'pending',
            'processing',
            'paid',
            'shipped',
            'completed',
            'cancelled',
        ];

        foreach ($statuses as $status) {
            OrderStatus::updateOrCreate(
                ['slug' => $status],
                [
                    'name' => ucfirst($status),
                    'slug' => $status,
                ]
            );
        }
    }
}
