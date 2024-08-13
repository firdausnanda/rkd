<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Bilangan;
use App\Helpers\Pdf;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\Prodi;
use App\Models\Sgas;
use App\Models\SgasPengajaran;
use App\Models\TahunAkademik;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PengajaranController extends Controller
{
    public function index(Request $request)
    {

        if (Auth::user()->roles[0]->name == 'user') {
            $dosen = Dosen::where('id', Auth::user()->id_dosen)->get();
        } else {
            $dosen = Dosen::where('is_active', 1)->get();
        }

        if (Auth::user()->roles[0]->name == 'prodi') {
            $prodi = Prodi::where('kode_prodi', Auth::user()->kode_prodi)->get();
        } else {
            $prodi = Prodi::all();
        }

        $matakuliah = Matakuliah::get();
        $ta = TahunAkademik::orderBy('tahun_akademik', 'desc')->get();

        if ($request->ajax()) {
            $pengajaran = SgasPengajaran::with('matakuliah', 'prodi', 'sgas')
                ->whereHas('sgas',  function (Builder $query) use ($request) {
                    $query->where('id_dosen', $request->dosen)
                        ->where('id_tahun_akademik', $request->ta)
                        ->where('jenis_kegiatan', SgasPengajaran::class)
                        ->where('semester', $request->semester);
                })->get();

            foreach ($pengajaran as $key => $p) {

                $totalDosen = SgasPengajaran::with('matakuliah', 'sgas')
                    ->whereHas('matakuliah', function (Builder $query) use ($p) {
                        $query->where('id', $p->id_matakuliah);
                    })
                    ->whereHas('sgas', function (Builder $query) use ($p) {
                        $query->where('id_tahun_akademik', $p->sgas->id_tahun_akademik)
                            ->where('jenis_kegiatan', SgasPengajaran::class);
                    })
                    ->where('semester', $p->semester)
                    ->count();

                $pengajaran[$key]->total_dosen = $totalDosen;
            }

            return ResponseFormatter::success($pengajaran, 'Data berhasil diambil!');
        }

        return view('pages.transaction.pengajaran', compact('dosen', 'ta', 'prodi', 'matakuliah'));
    }

    public function sgas(Request $request)
    {
        try {
            // Get Nomor Plot
            $no = Sgas::select('no_plot')
                ->where('id_tahun_akademik', $request->ta)
                ->where('semester', $request->semester)
                ->get();

            // CekDosen
            $cekdosen = Dosen::with('prodi')->where('id', $request->dosen)->first();

            // Check if Sgas exist
            $ceksgas = Sgas::where('id_dosen', $request->dosen)
                ->where('id_tahun_akademik', $request->ta)
                ->where('semester', $request->semester)
                ->where('jenis_kegiatan', SgasPengajaran::class)
                ->first();

            if ($ceksgas == null || $ceksgas == '') {
                Sgas::create([
                    'id_dosen' => $request->dosen,
                    'id_tahun_akademik' => $request->ta,
                    'semester' => $request->semester,
                    'validasi' => 0,
                    'no_plot' => $no->count() + 1,
                    'homebase_dosen' => $cekdosen->id_prodi,
                    'jabatan_fungsional' => $cekdosen->jabatan_fungsional,
                    'jenis_kegiatan' => SgasPengajaran::class,
                    'jabatan_struktural' => $cekdosen->jabatan_struktural
                ]);
            }

            $sgas = Sgas::with('homebase', 'dosen')
                ->where('id_dosen', $request->dosen)
                ->where('id_tahun_akademik', $request->ta)
                ->where('semester', $request->semester)
                ->where('jenis_kegiatan', SgasPengajaran::class)
                ->first();

            // Tahun Akademik
            $ta = TahunAkademik::where('id', $request->ta)->first();

            return ResponseFormatter::success([$cekdosen, $sgas, $ta], 'Data berhasil diambil!');
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
            $cek_sgas = Sgas::with('tahun_akademik')->where('id', $request->sgas)->first();
            $cek_sks_matakuliah = Matakuliah::where('id', $request->matkul)->first();

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

            if ($totalDosen == 0) {
                $totalDosen = 1;
            }

            if ($cek_sgas->tahun_akademik->id > 5) {
                // jumlah pertemuan / jumlah rencana pertemuan 1 semester * sks matakuliah
                $total = $request->jumlah_pertemuan / 14 * $cek_sks_matakuliah->sks;
            } else {
                // Rumus sebelum TA 2023 
                $total = $total_sks * $request->kelas / $totalDosen;
            }

            $pengajaran = SgasPengajaran::create([
                'id_sgas' => $request->sgas,
                'id_matakuliah' => $request->matkul,
                'id_prodi' => $prodi->id,
                'semester' => $request->semester,
                'kelas' => $request->kelas,
                'jumlah_pertemuan' => $request->jumlah_pertemuan,
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
            $cek_sgas = Sgas::with('tahun_akademik')->where('id', $request->sgas)->first();
            $cek_sks_matakuliah = Matakuliah::where('id', $request->matkul)->first();

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

            if ($totalDosen == 0) {
                $totalDosen = 1;
            }

            if ($cek_sgas->tahun_akademik->id > 5) {
                // jumlah pertemuan / jumlah rencana pertemuan 1 semester * sks matakuliah
                $total = $request->jumlah_pertemuan / 14 * $cek_sks_matakuliah->sks;
            } else {
                // Rumus sebelum TA 2023 
                $total = $total_sks * $request->kelas / $totalDosen;
            }

            $pengajaran = SgasPengajaran::where('id', $request->id_pengajaran)->update([
                'id_matakuliah' => $request->matkul,
                'id_prodi' => $prodi->id,
                'semester' => $request->semester,
                'kelas' => $request->kelas,
                'jumlah_pertemuan' => $request->jumlah_pertemuan,
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

    public function delete(Request $request)
    {
        try {
            $pengajaran = SgasPengajaran::where('id', $request->id)->delete();
            return ResponseFormatter::success($pengajaran, 'Data Berhasil Dihapus!');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error!');
        }
    }

    public function print(Request $request)
    {
        $id = $request->id;
        $sgas = Sgas::with('pengajaran', 'tahun_akademik', 'dosen.prodi.fakultas')->where('id', $id)->first();

        if (!$sgas->dosen->prodi) {
            return ResponseFormatter::error('Data dosen tidak memiliki homebase', 'server error', 402);
        }

        $aliasFakultas = $sgas->dosen && $sgas->dosen->prodi ? $sgas->dosen->prodi->fakultas->alias : '';
        $fakultas = $sgas->dosen && $sgas->dosen->prodi ? $sgas->dosen->prodi->fakultas->nama_fakultas : '';
        $dekan = $sgas->dosen && $sgas->dosen->prodi ? $sgas->dosen->prodi->fakultas->dekan : '';
        $nidn_dekan = $sgas->dosen && $sgas->dosen->prodi ? $sgas->dosen->prodi->fakultas->nidn_dekan : '';

        $pengajaran = SgasPengajaran::with('matakuliah', 'prodi', 'sgas')
            ->whereHas('sgas',  function (Builder $query) use ($id) {
                $query->where('id', $id);
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

        // dd($sgas);
        $pdf = new Pdf($fakultas); //L For Landscape / P For Portrait
        $pdf->AddPage();

        // Header
        $title = "SURAT TUGAS";
        $pdf->SetFont('Arial', '', 11, 5);
        $w = $pdf->GetStringWidth($title) + 6;
        $pdf->SetX((210 - $w) / 2);
        $pdf->Cell($w, 15, $title, 0, 0, 'C');
        $pdf->Ln(4);
        $tahun = $sgas->semester == 'ganjil' ? $sgas->tahun_akademik->semester_ganjil : $sgas->tahun_akademik->semester_genap;
        $pdf->Cell(193, 15, 'Nomor : Sgas / ' . $sgas->no_plot . ' / ' . Bilangan::Roman((int)Carbon::parse($tahun)->format('m')) . ' / ' . Str::of($tahun)->substr(0, 4) . ' / ' . $aliasFakultas, 0, 0, 'C');
        $pdf->Ln(17);

        $pdf->Cell(1, 7, 'Menimbang', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(135, 7, 'Bahwa untuk melaksanakan kegiatan Tri Dharma di lingkungan ' . $fakultas . ' ITSK RS DR. Soepraoen Kesdam V/Brw Malang maka perlu dikeluarkan surat tugas.', 0, 'J', false, 10);
        $pdf->Ln(3);

        $pdf->Cell(1, 7, 'Dasar', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(135, 7, 'Rencana Operasional kegiatan pelaksanaan Tri Dharma perguruan tinggi bagi dosen Semester ' . Str::title($sgas->semester) . ' TA. ' . $sgas->tahun_akademik->tahun_akademik . ' di lingkungan ' . $fakultas . ' ITSK RS DR. Soepraoen Kesdam V/Brw Malang.', 0, 'J', false, 10);

        $title = "DITUGASKAN";
        $pdf->SetFont('Arial', 'B', 11, 5);
        $w = $pdf->GetStringWidth($title) + 6;
        $pdf->SetX((210 - $w) / 2);
        $pdf->Cell($w, 15, $title, 0, 1, 'C');

        $pdf->SetFont('Arial', '', 11, 5);
        $pdf->Cell(1, 7, 'Kepada', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(135, 7, 'Nama, NIDN, Pengampu Mata Kuliah, seperti tersebut pada lampiran.', 0, 'J', false, 10);
        $pdf->Ln(3);

        $pdf->Cell(1, 7, 'Untuk', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(135, 7, '1.   Seterimanya surat perintah ini ditugaskan sebagai Dosen Pengajar untuk melaksanakan kegiatan tri dharma perguruan tinggi bagi dosen Semester ' . Str::title($sgas->semester) . ' TA. ' . $sgas->tahun_akademik->tahun_akademik . ' di lingkungan ' . $fakultas . ' ITSK RS DR. Soepraoen Kesdam V/Brw Malang;', 0, 'J', false, 0);
        $pdf->Ln(3);
        $pdf->Cell(52);
        $pdf->MultiCellIndent(135, 7, '2.   Lapor kepada Dekan ' . $fakultas . ' RS dr. Soepraoen atas pelaksanaan surat tugas ini;', 0, 'J', false, 0);
        $pdf->Ln(3);
        $pdf->Cell(52);
        $pdf->MultiCellIndent(135, 7, '3.   Melaksanakan tugas ini dengan seksama dan penuh rasa tanggung jawab.', 0, 'J', false, 0);
        $pdf->Ln(3);

        $pdf->Cell(1, 7, 'Selesai.', 0, 1, 'L');

        // Ttd
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'Ditetapkan di : Malang', 0, 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'Pada Tanggal : ' . Carbon::parse($tahun)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('j F Y'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 11, 5);
        $pdf->Cell(97);
        $pdf->Cell(98, 5, 'Dekan', 0, 1, 'C');
        $pdf->Cell(97);
        $pdf->Cell(98, 5, $fakultas, 0, 1, 'C');
        $pdf->ln(23);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, $dekan, 0, 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'NIDN. ' . $nidn_dekan, 0, 0, 'C');
        $pdf->ln(10);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(1, 7, 'Salinan surat ini disampaikan kepada :', 0, 0, 'L');
        $pdf->ln(5);
        $pdf->Cell(1, 7, '1. Yang bersangkutan', 0, 0, 'L');
        $pdf->ln(5);
        $pdf->Cell(1, 7, '2. Arsip', 0, 0, 'L');
        $pdf->ln(5);

        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 11);
        $pdf->Ln(2);
        $pdf->Cell(1, 7, 'Nomor : Sgas / ' . $sgas->no_plot . ' / ' . Bilangan::Roman((int)Carbon::parse($tahun)->format('m')) . ' / ' . Str::of($tahun)->substr(0, 4) . ' / ' . $aliasFakultas, 0, 0, 'L');
        $pdf->Ln(5);

        // Header
        $pdf->SetFont('Arial', 'BU', 12, 5);
        $title = "SURAT TUGAS";
        $w = $pdf->GetStringWidth($title) + 6;
        $pdf->SetX((210 - $w) / 2);
        $pdf->Cell($w, 15, $title, 0, 0, 'C');
        $pdf->Ln(17);

        $pdf->SetFont('Arial', '', 11, 5);
        $pdf->Cell(1, 7, '1.    Pejabat yang memberi tugas', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, 'Dekan ' . $fakultas, 0, 'J', false, 0);
        $pdf->Ln(0);

        $pdf->Cell(1, 7, '2.    Nama yang diberi tugas', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, $sgas->dosen->nama, 0, 'J', false, 0);
        $pdf->Ln(0);

        $pdf->Cell(1, 7, '3.    Jabatan', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, $sgas->dosen->prodi ? 'Dosen ' . $sgas->dosen->prodi->nama_prodi : '-', 0, 'J', false, 0);
        $pdf->Ln(0);

        $pdf->Cell(1, 7, '4.    Jabatan Fungsional', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, $sgas->dosen->jabatan_fungsional ?? '-', 0, 'J', false, 0);
        $pdf->Ln(0);

        $pdf->Cell(1, 7, '5.    Jabatan Struktural', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, $sgas->dosen->jabatan_struktural ?? '-', 0, 'J', false, 0);
        $pdf->Ln(0);

        $pdf->Cell(1, 7, '6.    NIDN', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, $sgas->dosen->nidn ?? '-', 0, 'J', false, 0);
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 11, 5);
        $pdf->Cell(1, 7, 'I. Pengajaran', 0, 0, 'L');
        $pdf->Ln(7);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(10, 7, 'No', 1, 0, 'C');
        $pdf->Cell(25, 7, 'Kode MA', 1, 0, 'C');
        $pdf->Cell(45, 7, 'Matakuliah', 1, 0, 'C');
        $pdf->Cell(25, 7, 'Prodi', 1, 0, 'C');
        $pdf->Cell(15, 7, 'Semester', 1, 0, 'C');
        $pdf->Cell(15, 7, 'SKS', 1, 0, 'C');
        $pdf->Cell(15, 7, 'Kelas', 1, 0, 'C');
        $pdf->Cell(20, 7, 'Total Dosen', 1, 0, 'C');
        $pdf->Cell(20, 7, 'Total', 1, 0, 'C');

        $pdf->SetFont('Arial', '', 8);
        $totalall = 0;
        foreach ($pengajaran as $key => $v) {
            // $total = $v->total_sks * $v->kelas / $v->total_dosen;
            $total = $v->matakuliah->sks * $v->kelas / $v->total_dosen;
            $totalall = $totalall + $total;
            $pdf->ln();

            $pdf->SetWidths(array(10, 25, 45, 25, 15, 15, 15, 20, 20));
            $pdf->SetLineHeight(5);
            $pdf->SetAligns(array('C', 'L', 'L', 'L', 'C', 'C', 'C', 'C', 'C'));
            $pdf->Row(array(
                $key + 1,
                $v->matakuliah->kode_matakuliah,
                $v->matakuliah->nama_matakuliah,
                $v->prodi->nama_prodi,
                $v->semester,
                $v->matakuliah->sks,
                $v->kelas,
                $v->total_dosen,
                number_format($total, 2, '.', ''),
            ));
        }

        $pdf->ln();
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(170, 7, 'Total', 1, 0, 'C');
        $pdf->Cell(20, 7, number_format($totalall, 2, '.', ''), 1, 0, 'C');

        // Ttd
        $pdf->Ln(15);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'Ditetapkan di : Malang', 0, 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'Pada Tanggal : ' . Carbon::parse($tahun)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('j F Y'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 11, 5);
        $pdf->Cell(97);
        $pdf->Cell(98, 5, 'Dekan', 0, 1, 'C');
        $pdf->Cell(97);
        $pdf->Cell(98, 5, $fakultas, 0, 1, 'C');
        $pdf->ln(23);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, $dekan, 0, 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'NIDN. ' . $nidn_dekan, 0, 0, 'C');
        $pdf->ln(10);

        header('Access-Control-Allow-Origin: *');
        $pdf->Output('D', 'PA.pdf');
    }

    public function print_ttd(Request $request)
    {
        $id = $request->id;
        $sgas = Sgas::with('pengajaran', 'tahun_akademik', 'dosen.prodi.fakultas')->where('id', $id)->first();

        if (!$sgas->dosen->prodi) {
            return ResponseFormatter::error('Data dosen tidak memiliki homebase', 'server error', 402);
        }

        $aliasFakultas = $sgas->dosen && $sgas->dosen->prodi ? $sgas->dosen->prodi->fakultas->alias : '';
        $fakultas = $sgas->dosen && $sgas->dosen->prodi ? $sgas->dosen->prodi->fakultas->nama_fakultas : '';
        $dekan = $sgas->dosen && $sgas->dosen->prodi ? $sgas->dosen->prodi->fakultas->dekan : '';
        $nidn_dekan = $sgas->dosen && $sgas->dosen->prodi ? $sgas->dosen->prodi->fakultas->nidn_dekan : '';

        $pengajaran = SgasPengajaran::with('matakuliah', 'prodi', 'sgas')
            ->whereHas('sgas',  function (Builder $query) use ($id) {
                $query->where('id', $id);
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

        // dd($sgas);
        $pdf = new Pdf($fakultas); //L For Landscape / P For Portrait
        $pdf->AddPage();

        // Header
        $title = "SURAT TUGAS MENGAJAR";
        $pdf->SetFont('Arial', '', 11, 5);
        $w = $pdf->GetStringWidth($title) + 6;
        $pdf->SetX((210 - $w) / 2);
        $pdf->Cell($w, 15, $title, 0, 0, 'C');
        $pdf->Ln(4);
        $tahun = $sgas->semester == 'ganjil' ? $sgas->tahun_akademik->semester_ganjil : $sgas->tahun_akademik->semester_genap;
        $pdf->Cell(193, 15, 'Nomor : Sgas / ' . $sgas->no_plot . ' / ' . Bilangan::Roman((int)Carbon::parse($tahun)->format('m')) . ' / ' . Str::of($tahun)->substr(0, 4) . ' / ' . $aliasFakultas, 0, 0, 'C');
        $pdf->Ln(17);

        $pdf->Cell(1, 7, 'Menimbang', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(135, 7, 'Bahwa untuk melaksanakan kegiatan Tri Dharma di lingkungan ' . $fakultas . ' ITSK RS DR. Soepraoen Kesdam V/Brw Malang maka perlu dikeluarkan surat tugas.', 0, 'J', false, 10);
        $pdf->Ln(3);

        $pdf->Cell(1, 7, 'Dasar', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(135, 7, 'Rencana Operasional kegiatan pelaksanaan Tri Dharma perguruan tinggi bagi dosen Semester ' . Str::title($sgas->semester) . ' TA. ' . $sgas->tahun_akademik->tahun_akademik . ' di lingkungan ' . $fakultas . ' ITSK RS DR. Soepraoen Kesdam V/Brw Malang.', 0, 'J', false, 10);

        $title = "DITUGASKAN";
        $pdf->SetFont('Arial', 'B', 11, 5);
        $w = $pdf->GetStringWidth($title) + 6;
        $pdf->SetX((210 - $w) / 2);
        $pdf->Cell($w, 15, $title, 0, 1, 'C');

        $pdf->SetFont('Arial', '', 11, 5);
        $pdf->Cell(1, 7, 'Kepada', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(135, 7, 'Nama, NIDN, Pengampu Mata Kuliah, seperti tersebut pada lampiran.', 0, 'J', false, 10);
        $pdf->Ln(3);

        $pdf->Cell(1, 7, 'Untuk', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(135, 7, '1.   Seterimanya surat perintah ini ditugaskan sebagai Dosen Pengajar untuk melaksanakan kegiatan tri dharma perguruan tinggi bagi dosen Semester ' . Str::title($sgas->semester) . ' TA. ' . $sgas->tahun_akademik->tahun_akademik . ' di lingkungan ' . $fakultas . ' ITSK RS DR. Soepraoen Kesdam V/Brw Malang;', 0, 'J', false, 0);
        $pdf->Ln(3);
        $pdf->Cell(52);
        $pdf->MultiCellIndent(135, 7, '2.   Lapor kepada Dekan ' . $fakultas . ' RS dr. Soepraoen atas pelaksanaan surat tugas ini;', 0, 'J', false, 0);
        $pdf->Ln(3);
        $pdf->Cell(52);
        $pdf->MultiCellIndent(135, 7, '3.   Melaksanakan tugas ini dengan seksama dan penuh rasa tanggung jawab.', 0, 'J', false, 0);
        $pdf->Ln(3);

        $pdf->Cell(1, 7, 'Selesai.', 0, 1, 'L');

        // Ttd
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'Ditetapkan di : Malang', 0, 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'Pada Tanggal : ' . Carbon::parse($tahun)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('j F Y'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 11, 5);
        $pdf->Cell(97);
        $pdf->Cell(98, 5, 'Dekan', 0, 1, 'C');
        $pdf->Cell(97);
        $pdf->Cell(98, 5, $fakultas, 0, 1, 'C');
        $pdf->ln(23);

        $pdf->Cell(97);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        if ($aliasFakultas == 'FST') {
            $pdf->Image('./img/ttd11.png', $x, $y - 30, 90);
        } elseif ($aliasFakultas == 'FIK') {
            $pdf->Image('./img/ttd9.png', $x, $y - 35, 90);
        }

        $pdf->ln();
        $pdf->Cell(97);
        $pdf->Cell(98, 7, $dekan, 0, 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'NIDN. ' . $nidn_dekan, 0, 0, 'C');
        $pdf->ln(10);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(1, 7, 'Salinan surat ini disampaikan kepada :', 0, 0, 'L');
        $pdf->ln(5);
        $pdf->Cell(1, 7, '1. Yang bersangkutan', 0, 0, 'L');
        $pdf->ln(5);
        $pdf->Cell(1, 7, '2. Arsip', 0, 0, 'L');
        $pdf->ln(5);

        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 11);
        $pdf->Ln(2);
        $pdf->Cell(1, 7, 'Nomor : Sgas / ' . $sgas->no_plot . ' / ' . Bilangan::Roman((int)Carbon::parse($tahun)->format('m')) . ' / ' . Str::of($tahun)->substr(0, 4) . ' / ' . $aliasFakultas, 0, 0, 'L');
        $pdf->Ln(2);

        // Header
        $pdf->SetFont('Arial', 'BU', 12, 5);
        $title = "SURAT TUGAS MENGAJAR";
        $w = $pdf->GetStringWidth($title) + 6;
        $pdf->SetX((210 - $w) / 2);
        $pdf->Cell($w, 15, $title, 0, 0, 'C');
        $pdf->Ln(13);

        $pdf->SetFont('Arial', '', 11, 5);
        $pdf->Cell(1, 7, '1.    Pejabat yang memberi tugas', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, 'Dekan ' . $fakultas, 0, 'J', false, 0);
        $pdf->Ln(0);

        $pdf->Cell(1, 7, '2.    Nama yang diberi tugas', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, $sgas->dosen->nama, 0, 'J', false, 0);
        $pdf->Ln(0);

        $pdf->Cell(1, 7, '3.    NIDN', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, $sgas->dosen->nidn ?? '-', 0, 'J', false, 0);
        $pdf->Ln(0);

        $pdf->Cell(1, 7, '4.    Jabatan Fungsional', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, $sgas->dosen->jabatan_fungsional ?? '-', 0, 'J', false, 0);
        $pdf->Ln(0);

        $pdf->Cell(1, 7, '5.    Jabatan Struktural', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, $sgas->dosen->jabatan_struktural ?? '-', 0, 'J', false, 0);
        $pdf->Ln(0);

        $pdf->Cell(1, 7, '6.    Unit Kerja', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, $sgas->dosen->prodi ? $sgas->dosen->prodi->nama_prodi : '-', 0, 'J', false, 0);
        $pdf->Ln(0);

        $pdf->SetFont('Arial', 'B', 11, 5);
        $pdf->Cell(1, 7, 'I. Pengajaran', 0, 0, 'L');
        $pdf->Ln(7);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(10, 7, 'No', 1, 0, 'C');
        $pdf->Cell(25, 7, 'Kode MA', 1, 0, 'C');
        $pdf->Cell(60, 7, 'Matakuliah', 1, 0, 'C');
        $pdf->Cell(30, 7, 'Prodi', 1, 0, 'C');
        $pdf->Cell(15, 7, 'Semester', 1, 0, 'C');
        $pdf->Cell(15, 7, 'SKS', 1, 0, 'C');
        // Rumus sebelum TA 2023 
        if ($sgas->tahun_akademik->id > 5) {
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(15, 7, 'Jumlah TM', 1, 0, 'C');
        } else {
            // Rumus sebelum TA 2023 
            $pdf->Cell(15, 7, 'Kelas', 1, 0, 'C');
        }
        $pdf->SetFont('Arial', 'B', 9);
        // $pdf->Cell(20, 7,'Total Dosen', 1, 0, 'C');
        $pdf->Cell(20, 7, 'Total', 1, 0, 'C');

        $pdf->SetFont('Arial', '', 8);
        $totalall = 0;
        foreach ($pengajaran as $key => $v) {
            // $total = $v->total_sks * $v->kelas / $v->total_dosen;

            if ($sgas->tahun_akademik->id > 5) {
                // jumlah pertemuan / jumlah rencana pertemuan 1 semester * sks matakuliah
                $total = $v->jumlah_pertemuan / 14 * $v->matakuliah->sks;
            } else {
                // Rumus sebelum TA 2023 
                $total = $v->matakuliah->sks * $v->kelas / $v->total_dosen;
            }

            $totalall = $totalall + round($total, 2);
            $pdf->ln();

            $pdf->SetWidths(array(10, 25, 60, 30, 15, 15, 15, 20));
            $pdf->SetLineHeight(5);
            $pdf->SetAligns(array('C', 'L', 'L', 'L', 'C', 'C', 'C', 'C', 'C'));
            $pdf->Row(array(
                $key + 1,
                $v->matakuliah->kode_matakuliah,
                $v->matakuliah->nama_matakuliah,
                $v->prodi->nama_prodi,
                $v->semester,
                $v->matakuliah->sks,
                $v->kelas,
                number_format($total, 2, '.', ''),
            ));
        }

        $pdf->ln();
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(170, 7, 'Total', 1, 0, 'C');
        $pdf->Cell(20, 7, number_format($totalall, 2, '.', ''), 1, 0, 'C');

        // Ttd
        $pdf->Ln(7);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'Ditetapkan di : Malang', 0, 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'Pada Tanggal : ' . Carbon::parse($tahun)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('j F Y'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 11, 5);
        $pdf->Cell(97);
        $pdf->Cell(98, 5, 'Dekan', 0, 1, 'C');
        $pdf->Cell(97);
        $pdf->Cell(98, 5, $fakultas, 0, 1, 'C');
        $pdf->ln(23);

        $pdf->Cell(97);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        if ($aliasFakultas == 'FST') {
            $pdf->Image('./img/ttd11.png', $x, $y - 30, 90);
        } elseif ($aliasFakultas == 'FIK') {
            $pdf->Image('./img/ttd9.png', $x, $y - 35, 90);
        }

        $pdf->ln();
        $pdf->Cell(97);
        $pdf->Cell(98, 7, $dekan, 0, 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'NIDN. ' . $nidn_dekan, 0, 0, 'C');
        $pdf->ln(10);

        header('Access-Control-Allow-Origin: *');
        $pdf->Output('D', 'PA.pdf');
    }
}
