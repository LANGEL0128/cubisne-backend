<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            [
                'name' => 'Belleza',
            ],
            [
                'name' => 'Restaurante',
            ],
            [
                'name' => 'Tienda',
            ],
            [
                'name' => 'Gimnasio',
            ],
            [
                'name' => 'Clínica',
            ],
            [
                'name' => 'Fotografía',
            ],
            [
                'name' => 'Consultoría',
            ],
            [
                'name' => 'Agencia',
            ],
            [
                'name' => 'Panadería',
            ],
            [
                'name' => 'Reparación',
            ],
            [
                'name' => 'Educación',
            ],
        ]);
    }
}
