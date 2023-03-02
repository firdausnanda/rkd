<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SgasPengajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_sgas',
        'id_matakuliah',
        'id_prodi',
        'semester',
        'kelas',
        't_sks',
        'p_sks',
        'k_sks',
        'total_sks',
        'total',
    ];

    protected $table = 'sgas_pengajaran';
}
