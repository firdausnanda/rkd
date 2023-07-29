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
        Prodi::create(['kode_prodi' => '14401', 'nama_prodi' => 'D3 Keperawatan', 'id_fakultas' => 2]);
        Prodi::create(['kode_prodi' => '15401', 'nama_prodi' => 'D3 Kebidanan', 'id_fakultas' => 2]);
        Prodi::create(['kode_prodi' => '15901', 'nama_prodi' => 'Pendidikan Profesi Bidan', 'id_fakultas' => 2]);
        Prodi::create(['kode_prodi' => '11202', 'nama_prodi' => 'S1 Fisioterapi', 'id_fakultas' => 2]);
        Prodi::create(['kode_prodi' => '11407', 'nama_prodi' => 'D3 Akupunktur', 'id_fakultas' => 2]);
        Prodi::create(['kode_prodi' => '13462', 'nama_prodi' => 'D3 Rekam Medis dan Informasi Kesehatan', 'id_fakultas' => 1]);
        Prodi::create(['kode_prodi' => '14201', 'nama_prodi' => 'S1 Ilmu Keperawatan', 'id_fakultas' => 2]);
        Prodi::create(['kode_prodi' => '14320', 'nama_prodi' => 'Sarjana Terapan Keperawatan Anestesiologi', 'id_fakultas' => 1]);
        Prodi::create(['kode_prodi' => '14901', 'nama_prodi' => 'Profesi Ners', 'id_fakultas' => 2]);
        Prodi::create(['kode_prodi' => '15302', 'nama_prodi' => 'Sarjana Terapan Kebidanan', 'id_fakultas' => 2]);
        Prodi::create(['kode_prodi' => '48202', 'nama_prodi' => 'S1 Farmasi Klinik dan Komunitas', 'id_fakultas' => 1]);
        Prodi::create(['kode_prodi' => '48401', 'nama_prodi' => 'D3 Farmasi', 'id_fakultas' => 1]);
        Prodi::create(['kode_prodi' => '55201', 'nama_prodi' => 'S1 Informatika', 'id_fakultas' => 1]);
    }
}
