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
        'validasi_pa',
        'validasi_ta',
        'validasi_pkl',
        'validasi_penguji_ta',
        'no_plot',
        'homebase_dosen',
        'jabatan_fungsional',
        'jabatan_struktural',
        'jenis_kegiatan',
    ];

    protected $table = 'sgas';

    public function pengajaran()
    {
        return $this->hasMany(SgasPengajaran::class, 'id_sgas', 'id');
    }
   
    public function tahun_akademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'id_tahun_akademik', 'id');
    }
    
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id');
    }

    public function homebase()
    {
        return $this->belongsTo(Prodi::class, 'homebase_dosen', 'id');
    }
}
