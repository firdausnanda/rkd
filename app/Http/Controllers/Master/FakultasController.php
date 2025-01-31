<?php

namespace App\Http\Controllers\Master;

use App\Helpers\ResponseFormatter;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Dosen;

class FakultasController extends Controller
{
    public function index(Request $request)
    {
        $dosen = Dosen::all();

        if ($request->ajax()) {
            $data = Fakultas::all();
            return ResponseFormatter::success($data, 'Data Fakultas Berhasil Diambil');
        }

        return view('pages.master.fakultas', compact('dosen'));
    }

    public function store(Request $request)
    {
        $dekan = Dosen::where('id', $request->nama_dekan)->first();
        $data = Fakultas::create([
            'nama_fakultas' => $request->nama,
            'alias' => $request->alias,
            'dekan' => $dekan->nama,
            'nidn_dekan' => $dekan->nidn
        ]);
        return ResponseFormatter::success($data, 'Data Fakultas Berhasil Ditambahkan');
    }

    public function update(Request $request)
    {
        $dekan = Dosen::where('nidn', $request->nama_dekan)->first();
        $data = Fakultas::where('id', $request->id_fakultas)->update([
            'nama_fakultas' => $request->nama,
            'alias' => $request->alias,
            'dekan' => $dekan->nama,
            'nidn_dekan' => $dekan->nidn
        ]);

        return ResponseFormatter::success($data, 'Data Fakultas Berhasil Diupdate');
    }

    public function destroy(Request $request)
    {
        $data = Fakultas::where('id', $request->id)->delete();
        return ResponseFormatter::success($data, 'Data Fakultas Berhasil Dihapus');
    }
}
