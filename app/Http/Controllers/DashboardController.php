<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\TataUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function redirectToLogin() {
        return redirect('/login');
    }

    public function indexAdmin()  {
        return view('admin.dashboard');
    }

    public function indexGuru()  {
        return view('guru.index');
    }

    public function indexSiswa()  {
        return view('siswa.dashboard');
    }

    public function indexTataUsaha()  {
        return view('tu.index');
    }
}
