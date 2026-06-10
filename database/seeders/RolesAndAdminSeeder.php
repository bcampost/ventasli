<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Roles base
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'docs_admin']);
        Role::firstOrCreate(['name' => 'user']);

        // Usuario admin
        $user = User::firstOrCreate(
            ['email' => 'admin@ventasli.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('Admin123*'),
            ]
        );

        if (!$user->hasRole('admin')) {
            $user->syncRoles(['admin']);
        }
    }
}