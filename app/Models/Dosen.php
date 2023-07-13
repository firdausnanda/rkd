<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'nidn',
        'nama',
        'id_prodi',
        'jabatan_fungsional',
        'status',
        'keterangan'
    ];

    protected $table = 'm_dosen';

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi', 'id');
    }

    public function sgas()
    {
        return $this->hasMany(Sgas::class, 'id_dosen', 'id');
    }
}
