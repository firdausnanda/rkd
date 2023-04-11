<?php

namespace App\Http\Controllers\Master;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $prodi = Prodi::all();
        if ($request->ajax()) {
            $dosen = Dosen::with('prodi')->get();
            return ResponseFormatter::success($dosen, 'Data Received Successfully!');
        }
        return view('pages.master.dosen', compact('prodi'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nidn' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'prodi' => 'required',
            'jabfung' => 'required|string|max:255',
            'status' => 'required|string|max:255'
          ]);
    
          if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Data gagal ditambahkan', 422);
          }

        try {
            $dosen = Dosen::create([
                'nidn' => $request->nidn,
                'nama' => $request->nama,
                'id_prodi' => $request->prodi,
                'jabatan_fungsional' => $request->jabfung,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
            ]);

            $user = User::create([
                'name' => $request->nama,
                'email' => $request->nidn,
                'id_dosen' => $dosen->id,
                'password' => Hash::make($request->nidn)
            ]);

            $user->syncRoles('user');   

            return ResponseFormatter::success($dosen, 'Data Berhasil Disimpan!');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error!');
        }
    }

    public function update(Request $request)
    {
        try {
            $update = Dosen::where('id', $request->id_dosen)->update([
                'nama' => $request->nama,
                'nidn' => $request->nidn,
                'id_prodi' => $request->prodi,
                'jabatan_fungsional' => $request->jabfung,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
            ]);

            $user = User::where('id_dosen', $request->id_dosen)->update([
                'name' => $request->nama,
            ]);

            return ResponseFormatter::success($update, 'Data Berhasil Diupdate');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error!');
        }
    }

    public function aktif(Request $request)
    {
      try {
        
        if ($request->aktif == 1) {
            $aktif = 0;
        }else {
            $aktif = 1;
        }
  
        $update = Dosen::where('id', $request->id)->update([
          'is_active' => $aktif 
        ]);

        return ResponseFormatter::success($update, 'Data Berhasil diupdate!');
      } catch (\Exception $e) {
        return ResponseFormatter::error($e, 'Server Error!');
      }
    }
}
