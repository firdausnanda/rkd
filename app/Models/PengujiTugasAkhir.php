<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengujiTugasAkhir extends Model
{
    use HasFactory;

    protected $table = 'sgas_penguji_tugas_akhir';

    protected $fillable = [
        'id_sgas',
        'nama_mahasiswa',
        'nim',
        'judul_ta',
        'peran',
    ];

    public function sgas()
    {
        return $this->belongsTo(Sgas::class, 'id_sgas', 'id');
    }
}
