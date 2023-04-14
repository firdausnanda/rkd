<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo()
    {
        switch (Auth::user()->roles[0]->name) {
            case 'superadmin':
              $this->redirectTo = '/superadmin';
              return $this->redirectTo;
              break;
            case 'admin':
              $this->redirectTo = '/admin';
              return $this->redirectTo;
              break;
            case 'prodi':
              $this->redirectTo = '/prodi';
              return $this->redirectTo;
              break;
            case 'mwi':
              $this->redirectTo = '/mwi';
              return $this->redirectTo;
              break;
            case 'bsdm':
              $this->redirectTo = '/bsdm';
              return $this->redirectTo;
              break;
            case 'user':
              $this->redirectTo = '/user';
              return $this->redirectTo;
              break;
            default:
              $this->redirectTo = '/login';
              return $this->redirectTo;
              break;
          }
    }

    // public function validateLogin(Request $request)
    // {
    //     $user = User::where($this->username(), $request->input($this->username()))->first();
    //     if ($user->is_active == 0) {
    //         throw ValidationException::withMessages([$this->username() => __('The account is inactive')]);
    //     }
        
    //     $request->validate([
    //         $this->username() => 'required|string',
    //         'password' => 'required|string',
    //     ]);
    // }
}
