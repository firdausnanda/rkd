<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FakultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fakultas::create([
            'nama_fakultas' => 'Fakultas Sains Dan Teknologi',
            'alias' => 'FST',
            'dekan' => 'Amin Zakaria, S.Kep.,Ners., M.Kes',
            'nidn_dekan' => '0703077604',
        ]);

        Fakultas::create([
            'nama_fakultas' => 'Fakultas Ilmu Kesehatan',
            'alias' => 'FIK',
            'dekan' => 'Ardhiles Wahyu  Kurniawan,S.Kep.Ners.,M.Kep',
            'nidn_dekan' => '0717048301',
        ]);
    }
}
