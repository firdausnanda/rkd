<?php

namespace App\Http\Controllers\Master;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Matakuliah;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MatakuliahController extends Controller
{
    public function index(Request $request)
    {
        $prodi = Prodi::all();
        if ($request->ajax()) {
            $matakuliah = Matakuliah::with('prodi')->where('kode_prodi', $request->id)->get();
            return ResponseFormatter::success($matakuliah, 'Data Berhasil diambil!');
        }
        return view('pages.master.matakuliah', compact('prodi'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_matkul' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'sks' => 'required',
            't' => 'required',
            'p' => 'required',
            'k' => 'required',
            'kurikulum' => 'required',
            'prodi' => 'required',
          ]);
    
          if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Data gagal ditambahkan', 422);
          }

        try {
            $matkul = Matakuliah::create([  
                'kode_matakuliah' => $request->kode_matkul,
                'nama_matakuliah' => Str::upper($request->nama),
                'sks' => $request->sks,
                't' => $request->t,
                'p' => $request->p,
                'k' => $request->k,
                'kurikulum' => $request->kurikulum,
                'kode_prodi' => $request->prodi
            ]);
            return ResponseFormatter::success($matkul, 'Data berhasil disimpan');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error');
        }
    }

    public function update(Request $request)
    {
        try {
            $matkul = Matakuliah::where('id', $request->id_matakuliah)->update([  
                'kode_matakuliah' => $request->kode_matkul,
                'nama_matakuliah' => Str::upper($request->nama),
                'sks' => $request->sks,
                't' => $request->t,
                'p' => $request->p,
                'k' => $request->k,
                'kurikulum' => $request->kurikulum,
                'kode_prodi' => $request->prodi
            ]);

            return ResponseFormatter::success($matkul, 'Data Berhasil diupdate');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error');
        }
    }

    public function delete(Request $request)
    {
        try {
            $matkul = Matakuliah::where('id', $request->id)->delete();
            return ResponseFormatter::success($matkul, 'Data Berhasil dihapus');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error');
        }
    }
}
