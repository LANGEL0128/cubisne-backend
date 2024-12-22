<?php

namespace Database\Seeders;

use App\Models\Packagepromotion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackagepromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Packagepromotion::insert([
            [
                'title' => 'Paquete 1',
                'days' => 7,
                'price' => 600,
            ],
            [
                'title' => 'Paquete 2',
                'days' => 15,
                'price' => 1200,
            ],
            [
                'title' => 'Paquete 3',
                'days' => 31,
                'price' => 2400,
            ],
            [
                'title' => 'Paquete 4',
                'days' => 95, // 3 meses + dias
                'price' => 7200,
            ],
            [
                'title' => 'Paquete 5',
                'days' => 191, // 6 meses + dias
                'price' => 14400,
            ],
        ]);
    }
}
