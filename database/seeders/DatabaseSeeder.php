<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Run an array of specific seeders...
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            FakultasSeeder::class,
            ProdiSeeder::class,
            TahunAkademikSeeder::class,
            DosenSeeder::class,
            MatakuliahSeeder::class,
            SgasSeeder::class,
            SgasPengajaranSeeder::class,
        ]);
    }
}
