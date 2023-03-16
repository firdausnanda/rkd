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
        $ta = TahunAkademik::orderBy('tahun_akademik', 'desc')->get();

        if ($request->ajax()) {
            $pengajaran = SgasPengajaran::with('matakuliah', 'prodi', 'sgas')
                            ->whereHas('sgas',  function (Builder $query) use ($request) {
                                $query->where('id_dosen', $request->dosen)
                                ->where('id_tahun_akademik', $request->ta)
                                ->where('semester', $request->semester);
                            })->get();

            foreach ($pengajaran as $p) {
            
                $totalDosen = SgasPengajaran::with('matakuliah', 'sgas')
                                ->whereHas('matakuliah', function (Builder $query) use ($p) {
                                    $query->where('id', $p->sgas->id_matakuliah);
                                })
                                ->whereHas('sgas', function (Builder $query) use ($p) {
                                    $query->where('id_tahun_akademik', $p->id_tahun_akademik)->where('semester', $p->semester);
                                })
                                ->count();

                // $p->total = $totalDosen;            
            }

            return ResponseFormatter::success($pengajaran, 'Data berhasil diambil!');
        }

        return view('pages.superadmin.pengajaran', compact('dosen', 'ta', 'prodi'));
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
            Sgas::updateOrCreate([
                'id_dosen' => $request->dosen,
                'id_tahun_akademik' => $request->ta,
                'semester' => $request->semester
            ],[
                'validasi' => 0,
                'no_plot' => $no
            ]);

            // Dosen
            $dosen = Dosen::with('prodi')->where('id', $request->dosen)->first();

            return ResponseFormatter::success($dosen, 'Data berhasil diambil!');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error!');
        }
    }

    public function kurikulum(Request $request)
    {
        $kurikulum = Matakuliah::where('kode_prodi', $request->prodi)->groupBy('kurikulum')->get();
        return ResponseFormatter::success($kurikulum, 'Data Berhasil diambil');
    }
}
