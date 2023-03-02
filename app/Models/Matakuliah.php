<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_matakuliah',
        'nama_matakuliah',
        'sks',
        't',
        'p',
        'k',
        'kurikulum',
        'kode_prodi',
    ];

    protected $table = 'm_matakuliah';
}
