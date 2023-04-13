<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(public_path('plugins/file/dosen.json'));
        $dosen = json_decode($json, true);

        foreach ($dosen as $d) {
            $dosen = Dosen::create([
                'nidn' => $d['nidn'],
                'nama' => $d['nama'],
                'id_prodi' => $d['prodi'],
                'jabatan_fungsional' => $d['jabatan_fungsional'],
                'status' => $d['status'],
                'is_active' => 1
            ]);

            $user = User::create([
                'name' => $d['nama'],
                'email' => $d['nidn'],
                'password' =>  Hash::make($d['nidn']),
                'id_dosen' => $dosen->id
            ]);

            $user->assignRole('user');
        }
    }
}
