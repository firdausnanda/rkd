<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sgas extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_dosen',
        'id_tahun_akademik',
        'semester',
        'validasi',
        'no_plot',
    ];

    protected $table = 'sgas';
}
