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
                'description' => 'Promociona tu negocio con el paquete más básico',
                'days' => 7,
                'price' => 600,
                'priority' => 1,
            ],
            [
                'title' => 'Paquete 2',
                'description' => 'Promociona tu negocio por 15 días',
                'days' => 15,
                'price' => 1200,
                'priority' => 1,
            ],
            [
                'title' => 'Paquete 3',
                'description' => 'Este paquete le da un nivel mayor de proridad a su negocio',
                'days' => 31,
                'price' => 2400,
                'priority' => 2,
            ],
            [
                'title' => 'Paquete 4',
                'description' => 'Eleva tu negocio con un nuevo nivel de prioridad por 95 días',
                'days' => 95, // 3 meses + dias
                'price' => 7200,
                'priority' => 3,
            ],
            [
                'title' => 'Paquete 5',
                'description' => 'Eleva tu negocio con el nivel de prioridad más alto de todos por 191 dias',
                'days' => 191, // 6 meses + dias
                'price' => 14400,
                'priority' => 4,
            ],
        ]);
    }
}
