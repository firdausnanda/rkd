<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SgasPenunjang extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_sgas',
        'jenis_kegiatan',
        'sks',
        'masa_penugasan',
    ];

    protected $table = 'sgas_penunjang';
}
