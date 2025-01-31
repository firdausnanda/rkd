<?php

namespace App\Http\Controllers\Control;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
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
    $fakultas = Fakultas::all();

    if ($request->ajax()) {
      $user = User::with('roles', 'prodi', 'fakultas')
        ->whereHas('roles',  function (Builder $query) use ($request) {
          $query->where('name', $request->role);
        })->get();
      return ResponseFormatter::success($user, 'Data Received Succesfully!');
    }
    return view('pages.control.akun', compact('role', 'prodi', 'fakultas'));
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
        'password' => Hash::make($request->password)
      ]);

      if ($request->role == 'admin') {
        $store->update([
          'id_fakultas' => $request->fakultas
        ]);
      }

      if ($request->role == 'prodi') {
        $store->update([
          'kode_prodi' => $request->prodi
        ]);
      }

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
      if ($request->role == 'admin') {
        $select->update([
          'id_fakultas' => $request->fakultas
        ]);
      }

      if ($request->role == 'prodi') {
        $select->update([
          'kode_prodi' => $request->prodi
        ]);
      }
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
      } else {
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
