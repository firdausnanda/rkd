<?php

namespace App\Exports;

use App\Models\Dosen;
use App\Models\SgasPengajaran;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class DosenExport implements FromView
{

    protected $ta, $semester;

    public function  __construct($semester, $ta)
    {
        $this->ta= $ta;
        $this->semester= $semester;
    }

    public function view(): View
    {
        dd($this->semester);
        $sgas = Dosen::join('sgas', 'm_dosen.id', '=', 'sgas.id_dosen')
                    ->join('sgas_pengajaran', 'sgas.id', '=', 'sgas_pengajaran.id_sgas')
                    ->join('m_matakuliah', 'sgas_pengajaran.id_matakuliah', '=', 'm_matakuliah.id')
                    ->join('m_prodi', 'm_matakuliah.kode_prodi', '=', 'm_prodi.kode_prodi')
                    ->where('sgas.semester', $this->semester)
                    ->where('sgas.id_tahun_akademik', $this->ta)
                    ->where('validasi', 1)
                    ->get();

        foreach ($sgas as $k => $v) {
            $totalDosen = SgasPengajaran::with('matakuliah', 'sgas')
                            ->whereHas('matakuliah', function (Builder $query) use ($v) {
                                $query->where('id', $v->id_matakuliah);
                            })
                            ->whereHas('sgas', function (Builder $query) use ($v) {
                                $query->where('id_tahun_akademik', $v->id_tahun_akademik);
                            })
                            ->where('semester', $v->semester)
                            ->count();
            
            $sgas[$k]->total_dosen = $totalDosen;
        }

        return view('pages.report.export.dosen', compact('sgas'));
    }
}
