<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Parts
            [
                'sku' => 'ENG001',
                'name' => 'Engine Oil - 20W40 (1L)',
                'type' => 'part',
                'price' => 450.00,
                'cost' => 380.00,
                'stock_qty' => 25,
                'is_active' => true,
            ],
            [
                'sku' => 'BRAKE001',
                'name' => 'Brake Pad Set - Front',
                'type' => 'part',
                'price' => 850.00,
                'cost' => 650.00,
                'stock_qty' => 15,
                'is_active' => true,
            ],
            [
                'sku' => 'BRAKE002',
                'name' => 'Brake Pad Set - Rear',
                'type' => 'part',
                'price' => 750.00,
                'cost' => 580.00,
                'stock_qty' => 12,
                'is_active' => true,
            ],
            [
                'sku' => 'CHAIN001',
                'name' => 'Drive Chain - 428H x 120L',
                'type' => 'part',
                'price' => 1200.00,
                'cost' => 950.00,
                'stock_qty' => 8,
                'is_active' => true,
            ],
            [
                'sku' => 'SPARK001',
                'name' => 'Spark Plug - NGK CR8E',
                'type' => 'part',
                'price' => 180.00,
                'cost' => 120.00,
                'stock_qty' => 50,
                'is_active' => true,
            ],
            [
                'sku' => 'AIR001',
                'name' => 'Air Filter Element',
                'type' => 'part',
                'price' => 320.00,
                'cost' => 240.00,
                'stock_qty' => 20,
                'is_active' => true,
            ],
            [
                'sku' => 'TIRE001',
                'name' => 'Front Tyre - 90/90-17',
                'type' => 'part',
                'price' => 2800.00,
                'cost' => 2200.00,
                'stock_qty' => 6,
                'is_active' => true,
            ],
            [
                'sku' => 'TIRE002',
                'name' => 'Rear Tyre - 120/80-17',
                'type' => 'part',
                'price' => 3500.00,
                'cost' => 2800.00,
                'stock_qty' => 4,
                'is_active' => true,
            ],
            [
                'sku' => 'CLUTCH001',
                'name' => 'Clutch Cable',
                'type' => 'part',
                'price' => 280.00,
                'cost' => 200.00,
                'stock_qty' => 15,
                'is_active' => true,
            ],
            [
                'sku' => 'CARB001',
                'name' => 'Carburetor Repair Kit',
                'type' => 'part',
                'price' => 650.00,
                'cost' => 480.00,
                'stock_qty' => 10,
                'is_active' => true,
            ],
            
            // Services
            [
                'name' => 'Basic Service & Oil Change',
                'type' => 'service',
                'price' => 800.00,
                'cost' => 0,
                'stock_qty' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Complete Engine Service',
                'type' => 'service',
                'price' => 2500.00,
                'cost' => 0,
                'stock_qty' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Brake System Service',
                'type' => 'service',
                'price' => 1200.00,
                'cost' => 0,
                'stock_qty' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Electrical System Diagnosis',
                'type' => 'service',
                'price' => 600.00,
                'cost' => 0,
                'stock_qty' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Carburetor Cleaning & Tuning',
                'type' => 'service',
                'price' => 900.00,
                'cost' => 0,
                'stock_qty' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Tyre Installation & Balancing',
                'type' => 'service',
                'price' => 400.00,
                'cost' => 0,
                'stock_qty' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Chain & Sprocket Replacement',
                'type' => 'service',
                'price' => 500.00,
                'cost' => 0,
                'stock_qty' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'General Repair & Maintenance',
                'type' => 'service',
                'price' => 1500.00,
                'cost' => 0,
                'stock_qty' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Bike Wash & Cleaning',
                'type' => 'service',
                'price' => 250.00,
                'cost' => 0,
                'stock_qty' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Tyre Change Service',
                'type' => 'service',
                'price' => 350.00,
                'cost' => 0,
                'stock_qty' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Engine Overhaul',
                'type' => 'service',
                'price' => 8500.00,
                'cost' => 0,
                'stock_qty' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Full Service Package',
                'type' => 'service',
                'price' => 1800.00,
                'cost' => 0,
                'stock_qty' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Oil Change Service',
                'type' => 'service',
                'price' => 200.00,
                'cost' => 0,
                'stock_qty' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Battery Check & Replacement',
                'type' => 'service',
                'price' => 500.00,
                'cost' => 0,
                'stock_qty' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Suspension Service',
                'type' => 'service',
                'price' => 1200.00,
                'cost' => 0,
                'stock_qty' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
