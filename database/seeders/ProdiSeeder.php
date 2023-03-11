<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Prodi::create(['kode_prodi' => '14401', 'nama_prodi' => 'D3 Keperawatan']);
        Prodi::create(['kode_prodi' => '15401', 'nama_prodi' => 'D3 Kebidanan']);
        Prodi::create(['kode_prodi' => '15901', 'nama_prodi' => 'Pendidikan Profesi Bidan']);
        Prodi::create(['kode_prodi' => '11202', 'nama_prodi' => 'S1 Fisioterapi']);
        Prodi::create(['kode_prodi' => '11407', 'nama_prodi' => 'D3 Akupunktur']);
        Prodi::create(['kode_prodi' => '13462', 'nama_prodi' => 'D3 Rekam Medis dan Informasi Kesehatan']);
        Prodi::create(['kode_prodi' => '14201', 'nama_prodi' => 'S1 Ilmu Keperawatan']);
        Prodi::create(['kode_prodi' => '14320', 'nama_prodi' => 'Sarjana Terapan Keperawatan Anestesiologi']);
        Prodi::create(['kode_prodi' => '14901', 'nama_prodi' => 'Profesi Ners']);
        Prodi::create(['kode_prodi' => '15302', 'nama_prodi' => 'Sarjana Terapan Kebidanan']);
        Prodi::create(['kode_prodi' => '48202', 'nama_prodi' => 'S1 Farmasi Klinik dan Komunitas']);
        Prodi::create(['kode_prodi' => '48401', 'nama_prodi' => 'D3 Farmasi']);
        Prodi::create(['kode_prodi' => '55201', 'nama_prodi' => 'S1 Informatika']);
    }
}
