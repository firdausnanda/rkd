<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembimbinganTugasAkhir extends Model
{
    use HasFactory;

    protected $table = 'sgas_pembimbingan_tugas_akhir';

    protected $fillable = [
        'id_sgas',
        'nama_mahasiswa',
        'nim',
        'judul_ta',
    ];

    public function sgas()
    {
        return $this->belongsTo(Sgas::class, 'id_sgas', 'id');
    }
}
