<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.dashboard');
    }

    function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'pass_lama' => 'required|string',
			'pass_baru' => 'required|min:8',
			'pass_baru_ulangi' => 'required|min:8',
		]);
		
		if ($validator->fails()) {
			return ResponseFormatter::error($validator->errors(), 'Request invalid', 422);
		}

        try {
			$user = User::find($request->id);
            if ($request->pass_baru != $request->pass_baru_ulangi) {
                return ResponseFormatter::error(['password' => ['Password baru tidak sama']], 'Password baru tidak sama', 422);
            }
			if (Hash::check($request->pass_lama, $user->password)) {
				$user->password = Hash::make($request->pass_baru);
				$user->save();
				return ResponseFormatter::success($user, 'Update Password Success');
			} else {
				return ResponseFormatter::error(['password' => ['Password lama salah']], 'Password lama salah', 422);
			}
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			return ResponseFormatter::error($e->getMessage(), 'Update Password Failed', 500);
		}
    }
}
