<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
 
        $categories = [
            [
                'name' => 'Electronicos',
                'description' => '-',
                'position' => 1,
                'status' => 'enabled'
            ],
            [
                'name' => 'Libros',
                'description' => '-',
                'position' => 2,
                'status' => 'enabled'
            ],
            [
                'name' => 'Ropa',
                'description' => '-',
                'position' => 3,
                'status' => 'disabled'
            ],
            [
                'name' => 'Software',
                'description' => '-',
                'position' => 4,
                'status' => 'enabled'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
