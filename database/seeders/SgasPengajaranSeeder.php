<?php

namespace Database\Seeders;

use App\Models\Matakuliah;
use App\Models\Sgas;
use App\Models\SgasPengajaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SgasPengajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(public_path('plugins/file/detail.json'));
        $detail = json_decode($json, true);
        
        foreach ($detail as $d) {
            $matakuliah = Matakuliah::where('kode_matakuliah', $d['kode_matkul'])->first();
            SgasPengajaran::create([
                'id_sgas' => $d['id_sgas'],
                'id_matakuliah' => $matakuliah->id,
                'id_prodi' => $d['prodi'],
                'semester' => $d['semesterd'],
                'kelas' => $d['kelas'],
                't_sks' => $d['tsks'],
                'p_sks' => $d['psks'],
                'k_sks' => $d['ksks'],
                'total_sks' => $d['total'],
            ]);
        }
    }
}
