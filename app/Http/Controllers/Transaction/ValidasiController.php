<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\PembimbinganAkademik;
use App\Models\PembimbinganPraktikLapangan;
use App\Models\PembimbinganTugasAkhir;
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

                switch ($request->kegiatan) {
                    case '1':
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
                        break;
                    case '2':
                        $pa = PembimbinganAkademik::where('id_sgas', $request->id_sgas)->get();
                        return ResponseFormatter::success($pa, 'Data berhasil diambil!');
                        break;
                    case '3':
                        $ta = PembimbinganTugasAkhir::where('id_sgas', $request->id_sgas)->get();
                        return ResponseFormatter::success($ta, 'Data berhasil diambil!');
                        break;
                    case '4':
                        $pkl = PembimbinganPraktikLapangan::where('id_sgas', $request->id_sgas)->get();
                        return ResponseFormatter::success($pkl, 'Data berhasil diambil!');
                        break;
                }
            }

            $sgas = Sgas::where('semester', $request->semester)
                ->where('id_tahun_akademik', $request->ta)
                ->with('tahun_akademik', 'dosen.prodi');

            // Cek if admin fakultas disesuaikan sesuai prodinya
            if (Auth::user()->roles[0]->name == 'admin') {
                $sgas = $sgas->whereHas('dosen.prodi.fakultas', function (Builder $q) {
                    $q->where('id', Auth::user()->id_fakultas);
                });
            }

            // Cek Kegiatan
            switch ($request->kegiatan) {
                case 1:
                    $sgas = $sgas->where('validasi', $request->status);
                    break;
                case 2:
                    $sgas = $sgas->where('validasi_pa', $request->status);
                    break;
                case 3:
                    $sgas = $sgas->where('validasi_ta', $request->status);
                    break;
                case 4:
                    $sgas = $sgas->where('validasi_pkl', $request->status);
                    break;
            }

            return ResponseFormatter::success($sgas->get(), 'Data berhasil diambil!');
        }

        return view('pages.transaction.validasi', compact('ta'));
    }

    public function update(Request $request)
    {
        try {

            $sgas = Sgas::find($request->id_sgas);

            switch ($request->kegiatan) {
                case '1':

                    if ($sgas->validasi == 1) {
                        $validasi = 0;
                    } else {
                        $validasi = 1;
                    }

                    $sgas->update([
                        'validasi' => $validasi
                    ]);

                    break;

                case '2':

                    if ($sgas->validasi_pa == 1) {
                        $validasi = 0;
                    } else {
                        $validasi = 1;
                    }

                    $sgas->update([
                        'validasi_pa' => $validasi
                    ]);

                    break;

                case '3':

                    if ($sgas->validasi_ta == 1) {
                        $validasi = 0;
                    } else {
                        $validasi = 1;
                    }

                    $sgas->update([
                        'validasi_ta' => $validasi
                    ]);

                    break;

                case '4':

                    if ($sgas->validasi_pkl == 1) {
                        $validasi = 0;
                    } else {
                        $validasi = 1;
                    }

                    $sgas->update([
                        'validasi_pkl' => $validasi
                    ]);

                    break;
            }

            return ResponseFormatter::success($sgas, 'Data berhasil diupdate');
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th, 'server error');
        }
    }

    public function update_all(Request $request)
    {
        try {

            switch ($request->kegiatan) {
                case '1':

                    if ($request->validasi == 1) {
                        $validasi = 0;
                    } else {
                        $validasi = 1;
                    }

                    foreach ($request->dataDosen as $d) {
                        $query = Sgas::find($d['id']);
                        $query->update([
                            'validasi' => $validasi
                        ]);
                    }

                    break;

                case '2':
                    if ($request->validasi == 1) {
                        $validasi = 0;
                    } else {
                        $validasi = 1;
                    }

                    foreach ($request->dataDosen as $d) {
                        $query = Sgas::find($d['id']);
                        $query->update([
                            'validasi_pa' => $validasi
                        ]);
                    }

                    break;

                case '3':
                    if ($request->validasi == 1) {
                        $validasi = 0;
                    } else {
                        $validasi = 1;
                    }

                    foreach ($request->dataDosen as $d) {
                        $query = Sgas::find($d['id']);
                        $query->update([
                            'validasi_ta' => $validasi
                        ]);
                    }

                    break;

                case '4':
                    if ($request->validasi == 1) {
                        $validasi = 0;
                    } else {
                        $validasi = 1;
                    }

                    foreach ($request->dataDosen as $d) {
                        $query = Sgas::find($d['id']);
                        $query->update([
                            'validasi_pkl' => $validasi
                        ]);
                    }

                    break;
            }

            return ResponseFormatter::success($query, 'Data Mahasiswa berhasil diupdate', 201);
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Server Error', 500);
        }
    }
}
