<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('admin'),
                'is_active' => true,
            ],
            [
                'name' => 'Cliente',
                'email' => 'cliente@gmail.com',
                'password' => bcrypt('12345'),
                'is_active' => true,
            ]
        ]);
    }
}
