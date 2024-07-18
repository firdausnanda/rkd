<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembimbinganAkademik extends Model
{
    use HasFactory;

    protected $table = 'sgas_pembimbingan_akademik';

    protected $fillable = [
        'id_sgas',
        'nama_mahasiswa',
        'nim',
        'semester',
    ];

    public function sgas()
    {
        return $this->belongsTo(Sgas::class, 'id_sgas', 'id');
    }
}
