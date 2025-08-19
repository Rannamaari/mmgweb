<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Motorcycle;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'John Doe',
                'phone' => '+91 98765 43210',
                'alt_phone' => '+91 87654 32109',
                'email' => 'john@example.com',
                'address' => '123 Main Street, Mumbai, Maharashtra',
                'gst_number' => '27ABCDE1234F1Z5',
                'motorcycles' => [
                    [
                        'plate_no' => 'MH 01 AB 1234',
                        'make' => 'Honda',
                        'model' => 'CB Shine',
                        'year' => 2020,
                        'color' => 'Black',
                        'vin' => 'ME4PC2200L1234567',
                        'engine_no' => 'PC22E1234567',
                    ]
                ]
            ],
            [
                'name' => 'Jane Smith',
                'phone' => '+91 87654 32109',
                'email' => 'jane@example.com',
                'address' => '456 Park Avenue, Pune, Maharashtra',
                'motorcycles' => [
                    [
                        'plate_no' => 'MH 12 CD 5678',
                        'make' => 'Bajaj',
                        'model' => 'Pulsar 150',
                        'year' => 2019,
                        'color' => 'Blue',
                    ],
                    [
                        'plate_no' => 'MH 12 EF 9012',
                        'make' => 'TVS',
                        'model' => 'Apache RTR 160',
                        'year' => 2021,
                        'color' => 'Red',
                    ]
                ]
            ],
            [
                'name' => 'Rajesh Kumar',
                'phone' => '+91 98765 11111',
                'address' => '789 Commercial Street, Bangalore, Karnataka',
                'gst_number' => '29XYZAB5678G2Z9',
                'motorcycles' => [
                    [
                        'plate_no' => 'KA 05 GH 3456',
                        'make' => 'Hero',
                        'model' => 'Splendor Plus',
                        'year' => 2018,
                        'color' => 'Silver',
                    ]
                ]
            ]
        ];

        foreach ($customers as $customerData) {
            $motorcycles = $customerData['motorcycles'];
            unset($customerData['motorcycles']);
            
            $customer = Customer::create($customerData);
            
            foreach ($motorcycles as $motorcycle) {
                $customer->motorcycles()->create($motorcycle);
            }
        }
    }
}
