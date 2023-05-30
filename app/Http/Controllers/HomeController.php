<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        switch (Auth::user()->roles[0]->name) {
            case 'superadmin':
                return redirect()->route('superadmin.index');
                break;
            case 'admin':
                return redirect()->route('admin.index');
                break;
            case 'prodi':
                return redirect()->route('prodi.index');
                break;
            case 'mwi':
                return redirect()->route('mwi.index');
                break;
            case 'bsdm':
                return redirect()->route('bsdm.index');
                break;
            case 'user':
                return redirect()->route('user.index');
                break;
            default:
                return redirect()->url('/login');
                break;
        }
    }
}
