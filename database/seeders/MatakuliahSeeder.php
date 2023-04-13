<?php

namespace Database\Seeders;

use App\Models\Matakuliah;
use App\Models\Prodi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MatakuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(public_path('plugins/file/matakuliah.json'));
        $matakuliah = json_decode($json, true);
        
        foreach ($matakuliah as $d) {
            $prodi = Prodi::where('id', $d['prodii'])->first();
            $matakuliah = Matakuliah::create([
                'kode_matakuliah' => $d['kode_matkul'],
                'nama_matakuliah' => Str::upper($d['nama_matkul']),
                'sks' => $d['sks'],
                't' => $d['t'],
                'p' => $d['p'],
                'k' => $d['k'],
                'kurikulum' => $d['kurikulum'],
                'kode_prodi' => $prodi->kode_prodi,
            ]);
        }
    }
}
