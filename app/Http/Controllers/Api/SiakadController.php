<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Bilangan;
use App\Helpers\Pdf;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Sgas;
use App\Models\SgasPengajaran;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
                        
        if ($sgas && $sgas->pengajaran) {
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
        }

        return ResponseFormatter::success($sgas, 'Data berhasil diambil!', 200);
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
        $pdf->Cell(15, 7,'Semester', 1, 0, 'C');
        $pdf->Cell(15, 7,'SKS', 1, 0, 'C');
        $pdf->Cell(15, 7,'Kelas', 1, 0, 'C');
        $pdf->Cell(20, 7,'Total Dosen', 1, 0, 'C');
        $pdf->Cell(20, 7,'Total', 1, 0, 'C');

        $pdf->SetFont('Arial', '', 8);
        $totalall = 0;
        foreach($pengajaran as $key => $v){
            // $total = $v->total_sks * $v->kelas / $v->total_dosen;
            $total = $v->matakuliah->sks * $v->kelas / $v->total_dosen;
            $totalall = $totalall + $total;
            $pdf->ln();

            $pdf->SetWidths(Array(10,25,45,25,15,15,15,20,20));
            $pdf->SetLineHeight(5);
            $pdf->SetAligns(Array('C','L','L','L','C','C', 'C', 'C', 'C'));
            $pdf->Row(Array(
                $key+1,
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
        $pdf->Image('./img/ttd8.png', $x, $y - 30, 90);

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
        $pdf->Cell(15, 7,'Semester', 1, 0, 'C');
        $pdf->Cell(15, 7,'SKS', 1, 0, 'C');
        $pdf->Cell(15, 7,'Kelas', 1, 0, 'C');
        $pdf->Cell(20, 7,'Total Dosen', 1, 0, 'C');
        $pdf->Cell(20, 7,'Total', 1, 0, 'C');

        $pdf->SetFont('Arial', '', 8);
        $totalall = 0;
        foreach($pengajaran as $key => $v){
            // $total = $v->total_sks * $v->kelas / $v->total_dosen;
            $total = $v->matakuliah->sks * $v->kelas / $v->total_dosen;
            $totalall = $totalall + $total;
            $pdf->ln();

            $pdf->SetWidths(Array(10,25,45,25,15,15,15,20,20));
            $pdf->SetLineHeight(5);
            $pdf->SetAligns(Array('C','L','L','L','C','C', 'C', 'C', 'C'));
            $pdf->Row(Array(
                $key+1,
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
        $pdf->Cell(98, 5, 'Fakultas Sains, Teknologi Dan Kesehatan', 0, 1, 'C');
        $pdf->ln(23);

        $pdf->Cell(97);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Image('./img/ttd8.png', $x, $y - 30, 90);
        
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
