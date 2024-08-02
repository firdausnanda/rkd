<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Bilangan;
use App\Helpers\Pdf;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\PembimbinganPraktikLapangan;
use App\Models\Sgas;
use App\Models\TahunAkademik;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PKLController extends Controller
{
    public function index(Request $request)
    {
        // Pembatasan Sesuai dengan User masing - masing
        if (Auth::user()->roles[0]->name == 'user') {
            $dosen = Dosen::where('id', Auth::user()->id_dosen)->get();
        } else {
            $dosen = Dosen::where('is_active', 1)->get();
        }

        $matakuliah = Matakuliah::get();
        $ta = TahunAkademik::orderBy('tahun_akademik', 'desc')->get();

        if ($request->ajax()) {
            $akademik = PembimbinganPraktikLapangan::whereHas('sgas', function (Builder $query) use ($request) {
                $query->where('id_dosen', $request->dosen)
                    ->where('id_tahun_akademik', $request->ta)
                    ->where('semester', $request->semester);
            })->get();
            return ResponseFormatter::success($akademik, 'Data berhasil diambil!');
        }

        return view('pages.transaction.pkl', compact('dosen', 'ta', 'matakuliah'));
    }

    public function sgas(Request $request)
    {
        try {
            // Get Nomor Plot
            $no = Sgas::select('no_plot')->where('id_tahun_akademik', $request->ta)->where('semester', $request->semester)->get();

            // CekDosen
            $cekdosen = Dosen::with('prodi')->where('id', $request->dosen)->first();

            // Check if Sgas exist
            $ceksgas = Sgas::where('id_dosen', $request->dosen)->where('id_tahun_akademik', $request->ta)->where('semester', $request->semester)->first();
            if ($ceksgas == null || $ceksgas == '') {
                Sgas::create([
                    'id_dosen' => $request->dosen,
                    'id_tahun_akademik' => $request->ta,
                    'semester' => $request->semester,
                    'validasi' => 0,
                    'no_plot' => $no->count() + 1,
                    'homebase_dosen' => $cekdosen->id_prodi,
                    'jabatan_fungsional' => $cekdosen->jabatan_fungsional,
                    'jabatan_struktural' => $cekdosen->jabatan_struktural
                ]);
            }

            $sgas = Sgas::with('homebase', 'dosen')->where('id_dosen', $request->dosen)->where('id_tahun_akademik', $request->ta)->where('semester', $request->semester)->first();

            // Tahun Akademik
            $ta = TahunAkademik::where('id', $request->ta)->first();

            return ResponseFormatter::success([$cekdosen, $sgas, $ta], 'Data berhasil diambil!');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error!');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_mahasiswa' => 'required',
            'nim' => 'required',
            'tempat_kegiatan' => 'required',
            'tgl_mulai_kegiatan' => 'required',
            'tgl_selesai_kegiatan' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Data tidak sesuai', 422);
        }

        try {

            $pengajaran = PembimbinganPraktikLapangan::create([
                'id_sgas' => $request->sgas,
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'nim' => $request->nim,
                'tempat_kegiatan' => $request->tempat_kegiatan,
                'tgl_mulai_kegiatan' => $request->tgl_mulai_kegiatan,
                'tgl_selesai_kegiatan' => $request->tgl_selesai_kegiatan
            ]);

            return ResponseFormatter::success($pengajaran, 'Data Berhasil disimpan');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return ResponseFormatter::error($th->getMessage(), 'Server Error!');
        }
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nama_mahasiswa' => 'required',
            'nim' => 'required',
            'tempat_kegiatan' => 'required',
            'tgl_mulai_kegiatan' => 'required',
            'tgl_selesai_kegiatan' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Data tidak sesuai', 422);
        }

        try {

            $pengajaran = PembimbinganPraktikLapangan::where('id', $request->pkl)->update([
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'nim' => $request->nim,
                'tempat_kegiatan' => $request->tempat_kegiatan,
                'tgl_mulai_kegiatan' => $request->tgl_mulai_kegiatan,
                'tgl_selesai_kegiatan' => $request->tgl_selesai_kegiatan
            ]);

            return ResponseFormatter::success($pengajaran, 'Data Berhasil diupdate');
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th, 'Server Error!');
        }
    }

    public function delete(Request $request)
    {
        try {
            $pengajaran = PembimbinganPraktikLapangan::where('id', $request->id)->delete();
            return ResponseFormatter::success($pengajaran, 'Data Berhasil Dihapus!');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error!');
        }
    }

    public function print_all(Request $request)
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

        $pkl = PembimbinganPraktikLapangan::whereHas('sgas',  function (Builder $query) use ($id) {
            $query->where('id', $id);
        })->get();

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
        $pdf->Cell(193, 15, 'Nomor : Sgas / ' . $sgas->no_plot . '.4 / ' . Bilangan::Roman((int)Carbon::parse($tahun)->format('m')) . ' / ' . Str::of($tahun)->substr(0, 4) . ' / ' . $aliasFakultas, 0, 0, 'C');
        $pdf->Ln(12);

        $pdf->Cell(1, 7, 'Menimbang', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(135, 7, 'Bahwa dalam rangka melaksanakan aktivitas Tridharma Perguruan Tinggi yaitu pembimbingan praktik kerja lapangan bagi mahasiswa di ITSK RS dr. Soepraoen, maka perlu di keluarkan surat tugas pembimbingan praktik kerja lapangan.', 0, 'J', false, 10);
        $pdf->Ln(3);

        $pdf->Cell(1, 7, 'Dasar', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(135, 7, 'Permohonan penerbitan surat tugas dosen pembimbingan praktik kerja lapangan mahasiswa TA ' . $sgas->tahun_akademik->tahun_akademik . ' Prodi ' . $sgas->dosen->prodi->nama_prodi . ' ' . $fakultas . ' ITSK RS dr. Soepraoen Malang', 0, 'J', false, 10);

        $title = "DITUGASKAN";
        $pdf->SetFont('Arial', 'B', 11, 5);
        $w = $pdf->GetStringWidth($title) + 6;
        $pdf->SetX((210 - $w) / 2);
        $pdf->Cell($w, 15, $title, 0, 1, 'C');

        $pdf->SetFont('Arial', '', 11, 5);
        $pdf->Cell(1, 7, 'Kepada', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');

        $pdf->Cell(3);
        $pdf->Cell(1, 7, 'Nama Dosen Pembimbing', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(75, 7, $sgas->dosen->nama, 0, 'J', false, 0);
        $pdf->Ln(0);

        $pdf->Cell(50);
        $pdf->Cell(1, 7, 'NIDN', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(75, 7, $sgas->dosen->nidn, 0, 'J', false, 0);
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 11, 5);
        $pdf->Cell(1, 7, 'Untuk', 0, 0, 'L');
        $pdf->Cell(45);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(135, 7, '1.   Seterimanya Surat ini disamping tugas jabatan dan tanggung jawab sehari-hari ditunjuk sebagai dosen pembimbing Praktik Kerja Lapangan mahasiswa TA. ' . $sgas->tahun_akademik->tahun_akademik . ' Prodi ' . $sgas->dosen->prodi->nama_prodi . ';', 0, 'J', false, 0);
        $pdf->Ln(3);
        $pdf->Cell(52);
        $pdf->MultiCellIndent(135, 7, '2.   Lapor kepada Dekan ' . $fakultas . ' RS dr. Soepraoen atas pelaksanaan surat tugas ini;', 0, 'J', false, 0);
        $pdf->Ln(3);
        $pdf->Cell(52);
        $pdf->MultiCellIndent(135, 7, '3.   Melaksanakan tugas ini dengan seksama dan penuh rasa tanggung jawab.', 0, 'J', false, 0);
        $pdf->Ln(3);
        $pdf->Cell(52);
        $pdf->MultiCellIndent(135, 7, '4.   Nama Mahasiswa dan lahan PKL terlampir.', 0, 'J', false, 0);
        $pdf->Ln(3);

        // Ttd
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'Dikeluarkan di Malang,', 0, 1, 'C');
        $pdf->SetFont('Arial', 'U', 11, 5);
        $pdf->Cell(97);
        $pdf->Cell(98, 5, 'Pada Tanggal ' . Carbon::parse($tahun)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('j F Y'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 11, 5);
        $pdf->Cell(97);
        $pdf->Cell(98, 5, 'Dekan ' . $fakultas, 0, 1, 'C');
        $pdf->ln(18);

        $pdf->Cell(97);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        if ($aliasFakultas == 'FST') {
            $pdf->Image('./img/ttd11.png', $x, $y - 25, 90);
        } elseif ($aliasFakultas == 'FIK') {
            $pdf->Image('./img/ttd9.png', $x, $y - 30, 90);
        }
        $pdf->ln(1);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, $dekan, 0, 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(97);
        $pdf->Cell(98, 7, 'NIDN. ' . $nidn_dekan, 0, 1, 'C');

        $pdf->AddPage('L');
        $pdf->Cell(170);
        $pdf->Cell(98, 7, 'Lampiran Surat Tugas Pembimbing Praktek Kerja Lapangan', 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(170);
        $pdf->Cell(98, 7, 'Nomor : Sgas / ' . $sgas->no_plot . '.4 / ' . Bilangan::Roman((int)Carbon::parse($tahun)->format('m')) . ' / ' . Str::of($tahun)->substr(0, 4) . ' / ' . $aliasFakultas, 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(170);
        $pdf->Cell(98, 7, 'Tanggal : ' . Carbon::parse($tahun)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('j F Y'), 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(170);
        $pdf->Line(180, 26, 290, 26);
        $pdf->Ln(3);

        $title = "LAMPIRAN NAMA MAHASISWA DAN LAHAN PKL";
        $pdf->SetFont('Arial', 'BU', 11, 5);
        $w = $pdf->GetStringWidth($title) + 6;
        $pdf->SetX(($pdf->GetPageWidth() - $w) / 2);
        $pdf->Cell($w, 15, $title, 0, 1, 'C');

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
        $pdf->MultiCellIndent(100, 7, $sgas->dosen->jabatan_struktural ?? '-', 0, 'J', false, 0);
        $pdf->Ln(0);

        $pdf->Cell(1, 7, '4.    Pangkat/Gol', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, $sgas->dosen->jabatan_fungsional ?? '-', 0, 'J', false, 0);
        $pdf->Ln(0);

        $pdf->Cell(1, 7, '5.    NIDN', 0, 0, 'L');
        $pdf->Cell(60);
        $pdf->Cell(1, 7, ':', 0, 0, 'L');
        $pdf->Cell(5);
        $pdf->MultiCellIndent(100, 7, $sgas->dosen->nidn ?? '-', 0, 'J', false, 0);
        $pdf->Ln(0);

        $pdf->SetFont('Arial', '', 11, 5);
        $pdf->Cell(1, 7, 'Ditugaskan sebagai Pembimbing PKL atas nama mahasiswa :', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 11, 5);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(10, 7, 'No', 1, 0, 'C');
        $pdf->Cell(80, 7, 'Nama Mahasiswa', 1, 0, 'C');
        $pdf->Cell(35, 7, 'NIM', 1, 0, 'C');
        $pdf->Cell(85, 7, 'Lahan PKL', 1, 0, 'C');
        $pdf->Cell(65, 7, 'Tanggal', 1, 0, 'C');

        $pdf->SetFont('Arial', '', 11);

        foreach ($pkl as $key => $v) {

            $pdf->ln();

            $pdf->SetWidths(array(10, 80, 35, 85, 65));
            $pdf->SetLineHeight(5);
            $pdf->SetAligns(array('C', 'L', 'C', 'L', 'L'));
            $pdf->Row(array(
                $key + 1,
                $v->nama_mahasiswa,
                $v->nim,
                $v->tempat_kegiatan,
                Carbon::parse($v->tgl_mulai_kegiatan)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('j F Y') . ' - ' . Carbon::parse($v->tgl_selesai_kegiatan)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('j F Y'),
            ));
        }
        
        $pdf->ln(8);

        // Ttd
        $pdf->Cell(180);
        $pdf->Cell(98, 7, 'Dikeluarkan di Malang,', 0, 1, 'C');
        $pdf->SetFont('Arial', 'U', 11, 5);
        $pdf->Cell(180);
        $pdf->Cell(98, 5, 'Pada Tanggal ' . Carbon::parse($tahun)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('j F Y'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 11, 5);
        $pdf->Cell(180);
        $pdf->Cell(98, 5, 'Dekan ' . $fakultas, 0, 1, 'C');
        $pdf->ln(18);

        $pdf->Cell(180);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        if ($aliasFakultas == 'FST') {
            $pdf->Image('./img/ttd11.png', $x, $y - 25, 90);
        } elseif ($aliasFakultas == 'FIK') {
            $pdf->Image('./img/ttd9.png', $x, $y - 30, 90);
        }
        $pdf->ln(1);
        $pdf->Cell(180);
        $pdf->Cell(98, 7, $dekan, 0, 0, 'C');
        $pdf->ln(5);
        $pdf->Cell(180);
        $pdf->Cell(98, 7, 'NIDN. ' . $nidn_dekan, 0, 1, 'C');

        $pdf->Output('D', 'PA.pdf');
    }
}
