<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Desarrollo web',
                'description' => '-',
                'price' => 1500,
                'category_id' => 4, // Software
                'position' => 1,
                'duration' => '4 meses',
                'status' => 'enabled'
            ],
            [
                'name' => 'Desarrollo de aplicaciones móviles',
                'description' => '-',
                'price' => 2500,
                'category_id' => 4, // Software
                'position' => 2,
                'duration' => '1 mes',
                'status' => 'enabled'
            ],
            [
                'name' => 'Diseño gráfico',
                'description' => '-',
                'price' => 1800,
                'category_id' => 4, // Software
                'position' => 1,
                'duration' => '1 semana',
                'status' => 'enabled'
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
