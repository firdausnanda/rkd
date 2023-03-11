<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $superadmin = User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@superadmin.com',
            'password' => Hash::make('password')
        ]);

        $superadmin->assignRole('superadmin');
    }
}
