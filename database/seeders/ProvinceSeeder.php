<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Province::insert([
            [
                'name' => 'Pinar del Río',
            ],
            [
                'name' => 'Artemisa',
            ],
            [
                'name' => 'La Habana',
            ],
            [
                'name' => 'Mayabeque',
            ],
            [
                'name' => 'Matanzas',
            ],
            [
                'name' => 'Villa Clara',
            ],
            [
                'name' => 'Cienfuegos',
            ],
            [
                'name' => 'Sancti Spíritus',
            ],
            [
                'name' => 'Ciego de Ávila',
            ],
            [
                'name' => 'Camaguey',
            ],
            [
                'name' => 'Las Tunas',
            ],
            [
                'name' => 'Holguín',
            ],
            [
                'name' => 'Granma',
            ],
            [
                'name' => 'Santiago de Cuba',
            ],
            [
                'name' => 'Guantánamo',
            ],
            [
                'name' => 'Isla de la Juventud',
            ],
        ]);
    }
}
