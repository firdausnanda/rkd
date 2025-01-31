<?php

namespace App\Http\Controllers\Master;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $prodi = Prodi::with('fakultas')->get();
            return ResponseFormatter::success($prodi, "Data berhasil diambil");
        }

        $fakultas = Fakultas::all();

        return view('pages.master.prodi', compact('fakultas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required',
            'fakultas' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Data tidak valid!');
        }

        try {
            $prodi = Prodi::create([
                'kode_prodi' => $request->kode,
                'nama_prodi' => $request->nama,
                'id_fakultas' => $request->fakultas,
            ]);

            return ResponseFormatter::success($prodi, 'Data Berhasil Disimpan!');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error!');
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required',
            'fakultas' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Data tidak valid!');
        }

        try {
            $prodi = Prodi::where('id', $request->id_prodi)->update([
                'kode_prodi' => $request->kode,
                'nama_prodi' => $request->nama,
                'id_fakultas' => $request->fakultas,
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
