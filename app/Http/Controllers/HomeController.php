<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\TataUsaha;
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
        if (Admin::where('user_id', Auth::user()->id)->exists()) {
            return redirect('admin/dashboard');
        } else if (Guru::where('user_id', Auth::user()->id)->exists()) {
            return redirect('guru/dashboard');
        } else if (Siswa::where('user_id', Auth::user()->id)->exists()) {
            return redirect('siswa/dashboard');
        } else if (TataUsaha::where('user_id', Auth::user()->id)->exists()) {
            return redirect('tu/dashboard');
        }

        return view('auth.no-role');
    }
}
