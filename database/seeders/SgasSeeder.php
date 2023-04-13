<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Sgas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SgasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(public_path('plugins/file/sgas.json'));
        $sgas = json_decode($json, true);
        
        foreach ($sgas as $d) {
            $dosen = Dosen::where('nidn', $d['nidn'])->first();
            Sgas::create([
                'id' => $d['id_sgas'],
                'id_dosen' => $dosen->id,
                'id_tahun_akademik' => $d['id_ta'],
                'semester' => $d['semester'],
                'validasi' => $d['validasi'],
                'no_plot' => $d['no_plot']
            ]);
        }
    }
}
