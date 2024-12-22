<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guards = array_keys(config('auth.guards'));
        foreach ($guards as $guard) {
            Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => $guard]);
            Role::firstOrCreate(['name' => 'cliente', 'guard_name' => $guard]);
        }
        $user = User::where('email', 'admin@admin.com')->first();
        $user->assignRole('superadmin');

        $user = User::where('email', 'cliente@gmail.com')->first();
        $user->assignRole('cliente');
    }
}
