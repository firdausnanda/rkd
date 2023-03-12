<?php

namespace App\Http\Controllers\Superadmin;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $prodi = Prodi::all();
            return ResponseFormatter::success($prodi, "Data berhasil diambil");
        }

        return view('pages.superadmin.prodi');
    }

    public function store(Request $request)
    {
        try {
            $prodi = Prodi::create([
                'kode_prodi' => $request->kode,
                'nama_prodi' => $request->nama,
            ]);

            return ResponseFormatter::success($prodi, 'Data Berhasil Disimpan!');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error!');
        }
    }

    public function update(Request $request)
    {
        try {
            $prodi = Prodi::where('id', $request->id_prodi)->update([
                'kode_prodi' => $request->kode,
                'nama_prodi' => $request->nama,
            ]);

            return ResponseFormatter::success($prodi, 'Data Berhasil Diedit!');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error!');
        }
    }

    public function delete(Request $request)
    {
        try {
            $prodi = Prodi::where('id', $request->id)->delete();
            return ResponseFormatter::success($prodi, 'Data Berhasil Dihapus!');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error!');
        }
    }
}
