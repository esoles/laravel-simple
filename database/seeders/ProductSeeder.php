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
            [
                'name' => 'Smartphone',
                'description' => '-',
                'price' => 500,
                'category_id' => 1, // Electronicos
                'position' => 1,
                'status' => 'enabled'
            ],
            [
                'name' => 'Laptop HP',
                'description' => '-',
                'price' => 1200,
                'category_id' => 1, // Electronicos
                'position' => 2,
                'status' => 'enabled'
            ],
            [
                'name' => 'Padre rico padre pobre',
                'description' => '-',
                'price' => 50,
                'category_id' => 2, // Libros
                'position' => 1,
                'status' => 'enabled'
            ],
            [
                'name' => 'Camisa de algodón',
                'description' => '-',
                'price' => 20,
                'category_id' => 3, // Ropa
                'position' => 1,
                'status' => 'enabled'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
