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

    public function sgas()
    {
        return $this->belongsTo(Sgas::class, 'id_sgas', 'id');
    }
    
    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'id_matakuliah', 'id');
    }
    
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi', 'id');
    }
}
