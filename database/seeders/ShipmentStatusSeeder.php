<?php

namespace Database\Seeders;

use App\Models\ShipmentStatus;
use Illuminate\Database\Seeder;

class ShipmentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'pending',
            'shipped',
            'in_transit',
            'delivered',
            'failed',
        ];

        foreach ($statuses as $status) {
            ShipmentStatus::updateOrCreate(
                ['slug' => $status],
                [
                    'name' => ucfirst(str_replace('_', ' ', $status)),
                    'slug' => $status,
                ]
            );
        }
    }
}
