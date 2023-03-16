<?php

namespace Database\Seeders;

use App\Models\TahunAkademik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TahunAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TahunAkademik::create([
            'tahun_akademik' => '2022/2023',
            'semester_genap' => '2022-08-29',
            'semester_ganjil' => '2022-02-13',
            'min' => '1',
            'max' => '1000',
        ]);

        TahunAkademik::create([
            'tahun_akademik' => '2021/2022',
            'semester_genap' => '2022-08-16',
            'semester_ganjil' => '2022-02-14',
            'min' => '1',
            'max' => '1000',
        ]);

        TahunAkademik::create([
            'tahun_akademik' => '2020/2021',
            'semester_genap' => '2022-07-05',
            'semester_ganjil' => '2022-07-12',
            'min' => '1',
            'max' => '1000',
        ]);
    }
}
