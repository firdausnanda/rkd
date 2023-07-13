<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\Prodi;
use App\Models\Sgas;
use App\Models\SgasPengajaran;
use App\Models\TahunAkademik;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function matakuliah(Request $request)
    {
        $ta = TahunAkademik::orderBy('tahun_akademik', 'desc')->get();
        $prodi = Prodi::all();
        if ($request->all()) {
            
            // dd($request->all());
            $matakuliah = Matakuliah::whereHas('pengajaran.sgas', function(Builder $q) use ($request){
                $q->where('sgas.semester', $request->semester)->where('sgas.id_tahun_akademik', $request->ta);
            })->with('pengajaran.sgas')->limit(5)->get();
            dd($matakuliah);

            // $sgas = SgasPengajaran::whereHas('sgas', function(Builder $q) use ($request) {
            //     $q->where('semester', $request->semester)->where('id_tahun_akademik', $request->ta);
            // })->with('sgas.dosen','matakuliah')->limit(2)->groupBy('id_matakuliah')->get();
            // dd($sgas);
            return ResponseFormatter::success($matakuliah, 'Data berhasil diambil!');
        }
        return view('pages.report.matakuliah', compact('ta', 'prodi'));
    }

    public function dosen(Request $request)
    {
        $ta = TahunAkademik::orderBy('tahun_akademik', 'desc')->get();
        $prodi = Prodi::all();
        if ($request->ajax()) {
            $sgas = Dosen::whereHas('sgas', function(Builder $q) use ($request) {
                    $q->where('semester', $request->semester)->where('id_tahun_akademik', $request->ta);
                })->with('sgas.pengajaran.matakuliah', 'prodi')->latest()->get();
            // dd($sgas);
            return ResponseFormatter::success($sgas, 'Data berhasil diambil!');
        }
        return view('pages.report.dosen', compact('ta', 'prodi'));
    }
}
