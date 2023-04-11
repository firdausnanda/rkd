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
        $dosen = Dosen::all();

        if (Auth::user()->roles[0]->name == 'prodi') {
            $prodi = Prodi::where('kode_prodi', Auth::user()->kode_prodi)->get();
        }else{
            $prodi = Prodi::all();
        }

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

        return view('pages.transaction.pengajaran', compact('dosen', 'ta', 'prodi', 'matakuliah'));
    }

    public function sgas(Request $request)
    {
        try {
            // Get Nomor Plot
            $no = Sgas::select('no_plot')->where('id_tahun_akademik', $request->ta)->where('semester', $request->semester)->get();

            // Check if Sgas exist
            $sgas = Sgas::where('id_dosen', $request->dosen)->where('id_tahun_akademik', $request->ta)->where('semester', $request->semester)->first();
            if ($sgas == null || $sgas == '') {
                $sgas = Sgas::create([
                    'id_dosen' => $request->dosen,
                    'id_tahun_akademik' => $request->ta,
                    'semester' => $request->semester,
                    'validasi' => 0,
                    'no_plot' => $no->count() + 1
                ]);
            }

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

            if ($totalDosen == 0) {
                $totalDosen = 1;
            }
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
            
            if ($totalDosen == 0) {
                $totalDosen = 1;
            }
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
        $sgas = Sgas::with('pengajaran', 'tahun_akademik', 'dosen.prodi')->where('id', $id)->first();

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
        $pdf = new Pdf(); //L For Landscape / P For Portrait
        $pdf->AddPage();

        // Header
        $title = "SURAT TUGAS";
        $pdf->SetFont('Arial', '', 11, 5);
        $w = $pdf->GetStringWidth($title) + 6;
        $pdf->SetX((210 - $w) / 2);
        $pdf->Cell($w, 15, $title, 0, 0, 'C');
        $pdf->Ln(4);
        $tahun = $sgas->semester == 'ganjil' ? $sgas->tahun_akademik->semester_ganjil : $sgas->tahun_akademik->semester_genap;
        $pdf->Cell(193, 15, 'Nomor : Sgas / ' . $sgas->no_plot . ' / ' . Bilangan::Roman((int)Carbon::parse($tahun)->format('m')) . ' / ' . Str::of($tahun)->substr(0, 4), 0, 0, 'C');
        $pdf->Ln(17);

        $pdf->Cell(1, 7, 'Menimbang', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(135, 7, 'Bahwa untuk melaksanakan kegiatan Tri Dharma di lingkungan Fakultas Sains, Teknologi, dan Kesehatan ITSK RS DR. Soepraoen Kesdam V/Brw Malang maka perlu dikeluarkan surat tugas.', 0, 'J', false, 10);
        $pdf->Ln(3);

        $pdf->Cell(1, 7, 'Dasar', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(135, 7, 'Rencana Operasional kegiatan pelaksanaan Tri Dharma perguruan tinggi bagi dosen Semester ' . Str::title($sgas->semester) . ' TA. ' . $sgas->tahun_akademik->tahun_akademik .' di lingkungan Fakultas Sains, Teknologi, dan Kesehatan ITSK RS DR. Soepraoen Kesdam V/Brw Malang.', 0, 'J', false, 10);

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
        $pdf->MultiCellIndent(135, 7, '1.   Seterimanya surat perintah ini ditugaskan sebagai Dosen Pengajar untuk melaksanakan kegiatan tri dharma perguruan tinggi bagi dosen Semester ' . Str::title($sgas->semester) . ' TA. ' . $sgas->tahun_akademik->tahun_akademik .' di lingkungan Fakultas Sains, Teknologi, dan Kesehatan ITSK RS DR. Soepraoen Kesdam V/Brw Malang;', 0, 'J', false, 0);
        $pdf->Ln(3);
        $pdf->Cell(52);
        $pdf->MultiCellIndent(135, 7, '2.   Lapor kepada Dekan Fakultas Sains, Teknologi, Dan Kesehatan RS dr. Soepraoen atas pelaksanaan surat tugas ini;', 0, 'J', false, 0);
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
        $pdf->Cell(98, 5, 'Fakultas Sains, Teknologi Dan Kesehatan', 0, 1, 'C');
        $pdf->ln(23);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'Amin Zakaria, S.Kep., Ners., M.Kes', 0, 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'NIDN. 0703077604', 0, 0, 'C');
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
        $pdf->Cell(1, 7, 'Nomor : Sgas / ' . $sgas->no_plot . ' / ' . Bilangan::Roman((int)Carbon::parse($tahun)->format('m')) . ' / ' . Str::of($tahun)->substr(0, 4), 0, 0, 'L');
        $pdf->Ln(5);

        // Header
        $pdf->SetFont('Arial', 'BU', 12 , 5);
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
        $pdf->MultiCellIndent(100, 7, 'Dekan Fakultas Sains Teknologi dan Kesehatan', 0, 'J', false, 0);
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
        
        $pdf->Cell(1, 7, '5.    NIP / NIDN / NIDK', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, $sgas->dosen->nidn ?? '-', 0, 'J', false, 0);
        $pdf->Ln(5);
        
        $pdf->SetFont('Arial', 'B', 11, 5);
        $pdf->Cell(1, 7, 'I. Pengajaran', 0, 0, 'L');
        $pdf->Ln(7);
        
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(10, 7,'No', 1, 0, 'C');
        $pdf->Cell(25, 7,'Kode MA', 1, 0, 'C');
        $pdf->Cell(45, 7,'Matakuliah', 1, 0, 'C');
        $pdf->Cell(25, 7,'Prodi', 1, 0, 'C');
        $pdf->Cell(25, 7,'Semester', 1, 0, 'C');
        $pdf->Cell(20, 7,'Kelas', 1, 0, 'C');
        $pdf->Cell(20, 7,'Total Dosen', 1, 0, 'C');
        $pdf->Cell(20, 7,'Total', 1, 0, 'C');

        $pdf->SetFont('Arial', '', 8);
        foreach($pengajaran as $key => $v){
            $total = $v->total_sks * $v->kelas / $v->total_dosen;
            $pdf->ln();

            $pdf->SetWidths(Array(10,25,45,25,25,20,20,20));
            $pdf->SetLineHeight(5);
            $pdf->SetAligns(Array('C','L','L','L','C','C', 'C', 'C'));
            $pdf->Row(Array(
                $key+1,
                $v->matakuliah->kode_matakuliah,
                $v->matakuliah->nama_matakuliah,
                $v->prodi->nama_prodi,
                $v->semester,
                $v->kelas,
                $v->total_dosen,
                $total,
            ));
        }

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
        $pdf->Cell(98, 5, 'Fakultas Sains, Teknologi Dan Kesehatan', 0, 1, 'C');
        $pdf->ln(23);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'Amin Zakaria, S.Kep., Ners., M.Kes', 0, 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'NIDN. 0703077604', 0, 0, 'C');
        $pdf->ln(10);

        $pdf->Output('D', 'PA.pdf');
    }
    
    public function print_ttd(Request $request)
    {
        $id = $request->id;
        $sgas = Sgas::with('pengajaran', 'tahun_akademik', 'dosen.prodi')->where('id', $id)->first();

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
        $pdf = new Pdf(); //L For Landscape / P For Portrait
        $pdf->AddPage();

        // Header
        $title = "SURAT TUGAS";
        $pdf->SetFont('Arial', '', 11, 5);
        $w = $pdf->GetStringWidth($title) + 6;
        $pdf->SetX((210 - $w) / 2);
        $pdf->Cell($w, 15, $title, 0, 0, 'C');
        $pdf->Ln(4);
        $tahun = $sgas->semester == 'ganjil' ? $sgas->tahun_akademik->semester_ganjil : $sgas->tahun_akademik->semester_genap;
        $pdf->Cell(193, 15, 'Nomor : Sgas / ' . $sgas->no_plot . ' / ' . Bilangan::Roman((int)Carbon::parse($tahun)->format('m')) . ' / ' . Str::of($tahun)->substr(0, 4), 0, 0, 'C');
        $pdf->Ln(17);

        $pdf->Cell(1, 7, 'Menimbang', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(135, 7, 'Bahwa untuk melaksanakan kegiatan Tri Dharma di lingkungan Fakultas Sains, Teknologi, dan Kesehatan ITSK RS DR. Soepraoen Kesdam V/Brw Malang maka perlu dikeluarkan surat tugas.', 0, 'J', false, 10);
        $pdf->Ln(3);

        $pdf->Cell(1, 7, 'Dasar', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(135, 7, 'Rencana Operasional kegiatan pelaksanaan Tri Dharma perguruan tinggi bagi dosen Semester ' . Str::title($sgas->semester) . ' TA. ' . $sgas->tahun_akademik->tahun_akademik .' di lingkungan Fakultas Sains, Teknologi, dan Kesehatan ITSK RS DR. Soepraoen Kesdam V/Brw Malang.', 0, 'J', false, 10);

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
        $pdf->MultiCellIndent(135, 7, '1.   Seterimanya surat perintah ini ditugaskan sebagai Dosen Pengajar untuk melaksanakan kegiatan tri dharma perguruan tinggi bagi dosen Semester ' . Str::title($sgas->semester) . ' TA. ' . $sgas->tahun_akademik->tahun_akademik .' di lingkungan Fakultas Sains, Teknologi, dan Kesehatan ITSK RS DR. Soepraoen Kesdam V/Brw Malang;', 0, 'J', false, 0);
        $pdf->Ln(3);
        $pdf->Cell(52);
        $pdf->MultiCellIndent(135, 7, '2.   Lapor kepada Dekan Fakultas Sains, Teknologi, Dan Kesehatan RS dr. Soepraoen atas pelaksanaan surat tugas ini;', 0, 'J', false, 0);
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
        $pdf->Cell(98, 5, 'Fakultas Sains, Teknologi Dan Kesehatan', 0, 1, 'C');
        $pdf->ln(23);

        $pdf->Cell(97);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Image(asset('img/ttd8.png'), $x, $y - 30, 90);

        $pdf->ln();
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'Amin Zakaria, S.Kep., Ners., M.Kes', 0, 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'NIDN. 0703077604', 0, 0, 'C');
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
        $pdf->Cell(1, 7, 'Nomor : Sgas / ' . $sgas->no_plot . ' / ' . Bilangan::Roman((int)Carbon::parse($tahun)->format('m')) . ' / ' . Str::of($tahun)->substr(0, 4), 0, 0, 'L');
        $pdf->Ln(5);

        // Header
        $pdf->SetFont('Arial', 'BU', 12 , 5);
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
        $pdf->MultiCellIndent(100, 7, 'Dekan Fakultas Sains Teknologi dan Kesehatan', 0, 'J', false, 0);
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
        
        $pdf->Cell(1, 7, '5.    NIP / NIDN / NIDK', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, $sgas->dosen->nidn ?? '-', 0, 'J', false, 0);
        $pdf->Ln(5);
        
        $pdf->SetFont('Arial', 'B', 11, 5);
        $pdf->Cell(1, 7, 'I. Pengajaran', 0, 0, 'L');
        $pdf->Ln(7);
        
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(10, 7,'No', 1, 0, 'C');
        $pdf->Cell(25, 7,'Kode MA', 1, 0, 'C');
        $pdf->Cell(45, 7,'Matakuliah', 1, 0, 'C');
        $pdf->Cell(25, 7,'Prodi', 1, 0, 'C');
        $pdf->Cell(25, 7,'Semester', 1, 0, 'C');
        $pdf->Cell(20, 7,'Kelas', 1, 0, 'C');
        $pdf->Cell(20, 7,'Total Dosen', 1, 0, 'C');
        $pdf->Cell(20, 7,'Total', 1, 0, 'C');

        $pdf->SetFont('Arial', '', 8);
        foreach($pengajaran as $key => $v){
            $total = $v->total_sks * $v->kelas / $v->total_dosen;
            $pdf->ln();

            $pdf->SetWidths(Array(10,25,45,25,25,20,20,20));
            $pdf->SetLineHeight(5);
            $pdf->SetAligns(Array('C','L','L','L','C','C', 'C', 'C'));
            $pdf->Row(Array(
                $key+1,
                $v->matakuliah->kode_matakuliah,
                $v->matakuliah->nama_matakuliah,
                $v->prodi->nama_prodi,
                $v->semester,
                $v->kelas,
                $v->total_dosen,
                round($total, 2),
            ));
        }

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
        $pdf->Cell(98, 5, 'Fakultas Sains, Teknologi Dan Kesehatan', 0, 1, 'C');
        $pdf->ln(23);

        $pdf->Cell(97);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Image(asset('img/ttd8.png'), $x, $y - 30, 90);
        
        $pdf->ln();
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'Amin Zakaria, S.Kep., Ners., M.Kes', 0, 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'NIDN. 0703077604', 0, 0, 'C');
        $pdf->ln(10);

        $pdf->Output('D', 'PA.pdf');
    }
}
