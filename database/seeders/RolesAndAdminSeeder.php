<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Roles base
        $adminRole   = Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Gerente']);
        Role::firstOrCreate(['name' => 'Vendedor']);

        // Usuario Admin inicial
        $admin = User::firstOrCreate(
            ['email' => 'admin@ventasli.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('Admin12345!'),
            ]
        );

        if (!$admin->hasRole('Admin')) {
            $admin->assignRole($adminRole);
        }
    }
}