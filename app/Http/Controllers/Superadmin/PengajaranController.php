<?php

namespace App\Http\Controllers\Superadmin;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\Prodi;
use App\Models\Sgas;
use App\Models\SgasPengajaran;
use App\Models\TahunAkademik;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PengajaranController extends Controller
{
    public function index(Request $request)
    {
        $dosen = Dosen::all();
        $prodi = Prodi::all();
        $matakuliah = Matakuliah::get();
        $ta = TahunAkademik::orderBy('tahun_akademik', 'desc')->get();

        if ($request->ajax()) {
            $pengajaran = SgasPengajaran::with('matakuliah', 'prodi', 'sgas')
                            ->whereHas('sgas',  function (Builder $query) use ($request) {
                                $query->where('id_dosen', $request->dosen)
                                ->where('id_tahun_akademik', $request->ta)
                                ->where('semester', $request->semester);
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

        return view('pages.superadmin.pengajaran', compact('dosen', 'ta', 'prodi', 'matakuliah'));
    }

    public function sgas(Request $request)
    {
        try {
            // Get Nomor Plot
            $no = Sgas::select('no_plot')->where('id_tahun_akademik', $request->ta)->get();
            
            if ($no) {
                $no = 1;
            }else{
                $no->max();
            }          

            // Update or Create Sgas
            $sgas = Sgas::updateOrCreate([
                'id_dosen' => $request->dosen,
                'id_tahun_akademik' => $request->ta,
                'semester' => $request->semester
            ],[
                'validasi' => 0,
                'no_plot' => $no
            ]);

            // Dosen
            $dosen = Dosen::with('prodi')->where('id', $request->dosen)->first();

            return ResponseFormatter::success([$dosen, $sgas], 'Data berhasil diambil!');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error!');
        }
    }

    public function kurikulum(Request $request)
    {
        $kurikulum = Matakuliah::where('kode_prodi', $request->prodi)->orderBy('id', 'desc')->groupBy('kurikulum')->get();
        return ResponseFormatter::success($kurikulum, 'Data Berhasil diambil');
    }
   
    public function matakuliah(Request $request)
    {
        $matakuliah = Matakuliah::where('kode_prodi', $request->prodi)->where('kurikulum', $request->kurikulum)->orderBy('id', 'desc')->get();
        return ResponseFormatter::success($matakuliah, 'Data Berhasil diambil');
    }
    
    public function sks(Request $request)
    {
        $matakuliah = Matakuliah::where('id', $request->matakuliah)->first();
        return ResponseFormatter::success($matakuliah, 'Data Berhasil diambil');
    }
    
    public function store(Request $request)
    {
        try {
            $prodi = Prodi::where('kode_prodi', $request->prodi)->first();
            // Hitung Total SKS
            $total_sks = $request->teori + $request->praktek + $request->klinik;

            // Hitung SKS Total (total sks * kelas / jumlah dosen)
            $totalDosen = SgasPengajaran::with('matakuliah', 'sgas')
                                ->whereHas('matakuliah', function (Builder $query) use ($request) {
                                    $query->where('id', $request->matkul);
                                })
                                ->whereHas('sgas', function (Builder $query) use ($request) {
                                    $query->where('id_tahun_akademik', $request->ta);
                                })
                                ->where('semester', $request->semester)
                                ->count();
            $total = $total_sks * $request->kelas / $totalDosen;

            $pengajaran = SgasPengajaran::create([
                'id_sgas' => $request->sgas,
                'id_matakuliah' => $request->matkul,
                'id_prodi' => $prodi->id,
                'semester' => $request->semester,
                'kelas' => $request->kelas,
                't_sks' => $request->teori,
                'p_sks' => $request->praktek,
                'k_sks' => $request->klinik,
                'total_sks' => $total_sks,
                'total' => $total
            ]);
            return ResponseFormatter::success($pengajaran, 'Data Berhasil disimpan');
            
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th, 'Server Error!');
        }
    }
    
    public function update(Request $request)
    {
        try {
            $prodi = Prodi::where('kode_prodi', $request->prodi)->first();
            // Hitung Total SKS
            $total_sks = $request->teori + $request->praktek + $request->klinik;

            // Hitung SKS Total (total sks * kelas / jumlah dosen)
            $totalDosen = SgasPengajaran::with('matakuliah', 'sgas')
                                ->whereHas('matakuliah', function (Builder $query) use ($request) {
                                    $query->where('id', $request->matkul);
                                })
                                ->whereHas('sgas', function (Builder $query) use ($request) {
                                    $query->where('id_tahun_akademik', $request->ta);
                                })
                                ->where('semester', $request->semester)
                                ->count();
            $total = $total_sks * $request->kelas / $totalDosen;

            $pengajaran = SgasPengajaran::where('id', $request->id_pengajaran)->update([
                'id_matakuliah' => $request->matkul,
                'id_prodi' => $prodi->id,
                'semester' => $request->semester,
                'kelas' => $request->kelas,
                't_sks' => $request->teori,
                'p_sks' => $request->praktek,
                'k_sks' => $request->klinik,
                'total_sks' => $total_sks,
                'total' => $total
            ]);
            return ResponseFormatter::success($pengajaran, 'Data Berhasil diupdate');
            
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th, 'Server Error!');
        }
    }
}
