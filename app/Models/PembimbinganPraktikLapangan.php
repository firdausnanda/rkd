<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembimbinganPraktikLapangan extends Model
{
    use HasFactory;

    protected $table = 'sgas_praktik_lapangan';

    protected $fillable = [
        'id_sgas',
        'nama_mahasiswa',
        'nim',
        'tempat_kegiatan',
        'tgl_mulai_kegiatan',
        'tgl_selesai_kegiatan',
    ];

    public function sgas()
    {
        return $this->belongsTo(Sgas::class, 'id_sgas', 'id');
    }
}
