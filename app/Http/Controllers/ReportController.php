<?php

namespace App\Http\Controllers;

use App\Exports\DosenExport;
use App\Helpers\ResponseFormatter;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Matakuliah;
use App\Models\Prodi;
use App\Models\Sgas;
use App\Models\SgasPengajaran;
use App\Models\TahunAkademik;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
        $fakultas = Fakultas::all();
        if ($request->ajax()) {
            if ($request->id_sgas) {
                
                $sgas = SgasPengajaran::with('matakuliah', 'prodi')
                            ->whereHas('sgas', function (Builder $q) use ($request){
                                $q->where('id_tahun_akademik', $request->ta)->where('semester', $request->semester)->where('id_dosen', $request->id_dosen);
                            })->get();

                foreach ($sgas as $key => $p) {
            
                    $totalDosen = SgasPengajaran::with('matakuliah', 'sgas')
                                        ->whereHas('matakuliah', function (Builder $query) use ($p) {
                                            $query->where('id', $p->id_matakuliah);
                                        })
                                        ->whereHas('sgas', function (Builder $query) use ($p) {
                                            $query->where('id_tahun_akademik', $p->sgas->id_tahun_akademik);
                                        })
                                        ->where('semester', $p->semester)
                                        ->count();

                    $sgas[$key]->total_dosen = $totalDosen;                
                }

                return ResponseFormatter::success($sgas, 'Data Detail berhasil diambil!');
            }

            if ($request->fakultas) {
                $sgas = Dosen::whereHas('sgas', function(Builder $q) use ($request) {
                        $q->where('semester', $request->semester)->where('id_tahun_akademik', $request->ta)->where('validasi', 1);
                    })->whereHas('prodi', function(Builder $q) use ($request){
                        $q->where('id_fakultas', $request->fakultas);
                    })->with('sgas.pengajaran.matakuliah', 'prodi')->latest()->get();
                return ResponseFormatter::success($sgas, 'Data berhasil diambil!');
            }else{
                $sgas = Dosen::whereHas('sgas', function(Builder $q) use ($request) {
                        $q->where('semester', $request->semester)->where('id_tahun_akademik', $request->ta)->where('validasi', 1);
                    })->with('sgas.pengajaran.matakuliah', 'prodi')->latest()->get();
                return ResponseFormatter::success($sgas, 'Data berhasil diambil!');
            }

        }
        return view('pages.report.dosen', compact('ta', 'prodi', 'fakultas'));
    }

    public function printDosen(Request $request) 
    {       
        $nama_file = 'Dosen_'.date('Y-m-d_H-i-s').'.xlsx';
        return Excel::download(new DosenExport($request->semester, $request->ta, $request->fakultas), $nama_file);
    }
}
