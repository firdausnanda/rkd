<?php

namespace App\Http\Controllers\Superadmin;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class TahunAkademikController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $ta = TahunAkademik::all();
            return ResponseFormatter::success($ta, 'Data berhasil diambil');
        }
        return view('pages.superadmin.ta');
    }

    public function store(Request $request)
    {
        try {
            $ta = TahunAkademik::create([
                'tahun_akademik' => $request->ta,
                'semester_ganjil' => $request->ganjil,
                'semester_genap' => $request->genap,
                'min' => $request->min,
                'max' => $request->max,
            ]);

            return ResponseFormatter::success($ta, 'Data Berhasil Disimpan');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error');
        }
    }

    public function update(Request $request)
    {
        try {
            $ta = TahunAkademik::where('id', $request->id_ta)->update([
                'tahun_akademik' => $request->ta,
                'semester_ganjil' => $request->ganjil,
                'semester_genap' => $request->genap
            ]);

            return ResponseFormatter::success($ta, 'Data Berhasil Disimpan');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error');
        }
    }
}
