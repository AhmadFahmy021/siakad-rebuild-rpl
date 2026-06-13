<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;

class AuthOrtuController extends Controller
{
    public function index() {
        return view('ortu.auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'namasiswa' => 'required|string',
            'namaorangtua' => 'required|string',
        ]);

        // $credentials = $request->only('namasiswa', 'namaorangtua');

        // if (auth()->guard('ortu')->attempt($credentials)) {
        //     return redirect()->intended('/ortu/dashboard');
        // }

        $siswa = Siswa::whereHas('user', function ($query) use ($request) {
            $query->where('name', $request->namasiswa);
        })->where('nama_ortu', $request->namaorangtua)->first();

        if ($siswa) {
            session([
                'ortu_login' => true, 
                'siswa_id' => $siswa->id, 
                'siswa_nama' => $siswa->user->name, 
                'nama_ortu' => $siswa->nama_ortu
            ]);
            return redirect('/ortu/dashboard');
        }

        return back()->withErrors(['namasiswa' => 'Nama siswa atau nama orang tua salah.', 'namaorangtua' => 'Nama siswa atau nama orang tua salah.'])->withInput();
    }

    public function logout() {
        // auth()->guard('ortu')->logout();
        session()->flush();
        return redirect('/ortu/login');
    }
}
