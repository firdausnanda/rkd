<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Sgas;
use App\Models\SgasPengajaran;
use App\Models\TahunAkademik;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiController extends Controller
{
    public function index(Request $request)
    {
        $ta = TahunAkademik::orderBy('tahun_akademik', 'desc')->get();
        if ($request->ajax()) {
            if ($request->id_sgas) {
                $pengajaran = SgasPengajaran::with('matakuliah', 'prodi', 'sgas')
                            ->whereHas('sgas',  function (Builder $query) use ($request) {
                                $query->where('id', $request->id_sgas);
                            })->get();

                foreach ($pengajaran as $key => $p) {
            
                $totalDosen = SgasPengajaran::with('matakuliah', 'sgas')
                                ->whereHas('matakuliah', function (Builder $query) use ($p) {
                                    $query->where('id', $p->id_matakuliah);
                                })
                                ->whereHas('sgas', function (Builder $query) use ($p) {
                                    $query->where('id_tahun_akademik', $p->sgas->id_tahun_akademik);
                                })
                                ->where('semester', $p->semester)
                                ->count();

                $pengajaran[$key]->total_dosen = $totalDosen;                
                }

                return ResponseFormatter::success($pengajaran, 'Data berhasil diambil!');
            }

            if (Auth::user()->roles[0]->name == 'admin') {
                $sgas = Sgas::where('semester', $request->semester)
                            ->where('id_tahun_akademik', $request->ta)
                            ->where('validasi', $request->status)
                            ->whereHas('dosen.prodi.fakultas', function (Builder $q){
                                $q->where('id', Auth::user()->id_fakultas);
                            })
                            ->with('tahun_akademik', 'dosen.prodi')
                            ->get();
            }else{
                $sgas = Sgas::where('semester', $request->semester)
                            ->where('id_tahun_akademik', $request->ta)
                            ->where('validasi', $request->status)
                            ->with('tahun_akademik', 'dosen.prodi')
                            ->get();
            }

            return ResponseFormatter::success($sgas, 'Data berhasil diambil!');
        }
        
        return view('pages.transaction.validasi', compact('ta'));
    }

    public function update(Request $request)
    {
        try {
            if ($request->status_validasi == 1) {
                $validasi = 0;
            }else {
                $validasi = 1;
            }

            $sgas = Sgas::where('id', $request->id_sgas)->update([
                'validasi' => $validasi
            ]);

            return ResponseFormatter::success($sgas, 'Data berhasil diupdate');
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th, 'server error');
        }
    }

    public function update_all(Request $request)
    {
        try {

            if ($request->validasi == 1) {
                $validasi = 0;
            }else {
                $validasi = 1;
            }
                 
            foreach ($request->dataDosen as $d) {
                $query = Sgas::find($d['id']); 
                $query->update([
                    'validasi' => $validasi
                ]);
            }

            return ResponseFormatter::success($query, 'Data Mahasiswa berhasil diupdate', 201);
          
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Server Error', 500);
        }
    }
}
