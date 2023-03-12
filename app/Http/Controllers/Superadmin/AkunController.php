<?php

namespace App\Http\Controllers\Superadmin;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AkunController extends Controller
{
    public function index(Request $request)
    {
        $role = Role::all();
        $prodi = Prodi::all();
        if ($request->ajax()) {
            $user = User::with('roles', 'prodi')->get();
            return ResponseFormatter::success($user, 'Data Received Succesfully!');
        }
        return view('pages.superadmin.akun', compact('role', 'prodi'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'role' => 'required',
          ]);
    
          if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Data gagal ditambahkan', 422);
          }

          try {
            $store = User::create([
              'name' => $request->name,
              'email' => $request->email,
              'kode_prodi' => $request->prodi,
              'password' => Hash::make($request->password)
            ]);
  
            $store->syncRoles($request->role);
  
            return ResponseFormatter::success($store, 'Data berhasil disimpan!');
          } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error!');
          }
          
        }
        
      public function update(Request $request)
      {
          $validator = Validator::make($request->all(), [
            'id_akun' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'role' => 'required',
          ]);
          
          if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Data gagal ditambahkan', 422);
          }
          
          try {
            $select = User::where('id', $request->id_akun)->first();
            $update = $select->update(['name' => $request->name, 'email' => $request->email]);
            $select->syncRoles($request->role);
            return ResponseFormatter::success($update, 'Data berhasil diupdate!');            
          } catch (\Exception $e) {
            return ResponseFormatter::error($e, 'Server Error!');
          }
        }
        
      public function reset(Request $request)
      {
          try {
            $update = User::where('id', $request->id)->update([
              'password' => Hash::make($request->password)
            ]);
            
            return ResponseFormatter::success($update, 'Data berhasil diupdate!');
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

        $update = User::where('id', $request->id)->update([
          'is_active' => $aktif 
        ]);
        
        return ResponseFormatter::success($update, 'Data Berhasil diupdate!');
      } catch (\Exception $e) {
        return ResponseFormatter::error($e, 'Server Error!');
      }
    }
}
