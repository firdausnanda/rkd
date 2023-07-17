<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Sgas;
use App\Models\SgasPengajaran;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SiakadController extends Controller
{
    public function index(Request $request) 
    {
        $sgas = Sgas::whereHas('dosen', function(Builder $q) use ($request){
                            $q->where('nidn',  $request->nidn);
                        })
                        ->whereHas('tahun_akademik', function(Builder $q) use ($request){
                            $q->where('tahun_akademik',  $request->ta);
                        })
                        ->where('semester', $request->semester)
                        ->with('pengajaran.matakuliah.prodi', 'dosen.prodi')
                        ->first();
        
        foreach ($sgas->pengajaran as $key => $p) {
            
                $totalDosen = SgasPengajaran::with('matakuliah', 'sgas')
                                ->whereHas('matakuliah', function (Builder $query) use ($p) {
                                    $query->where('id', $p->id_matakuliah);
                                })
                                ->whereHas('sgas', function (Builder $query) use ($p) {
                                    $query->where('id_tahun_akademik', $p->sgas->id_tahun_akademik);
                                })
                                ->where('semester', $p->semester)
                                ->count();

                $sgas->pengajaran[$key]->total_dosen = $totalDosen;                
        }

        return ResponseFormatter::success($sgas, 'Data berhasil diambil!', 200);
    }
}
