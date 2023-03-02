<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_akademik',
        'semester_genap',
        'semester_ganjil',
        'min',
        'max',
    ];

    protected $table = 'm_tahun_akademik';
}
