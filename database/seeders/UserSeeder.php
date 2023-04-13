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

        $json = file_get_contents(public_path('plugins/file/user.json'));
        $user = json_decode($json, true);

        foreach ($user as $d) {
            $user = User::create([
                'name' => $d['name'],
                'email' => $d['email'],
                'password' => $d['password'],
                'kode_prodi' => $d['prodi'],
            ]);

            $user->assignRole($d['role']);
        }

        $superadmin = User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@superadmin.com',
            'password' => Hash::make('password')
        ]);

        $superadmin->assignRole('superadmin');
    }
}
