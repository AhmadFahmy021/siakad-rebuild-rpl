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

        $siswa = Siswa::where('nama_ortu', $request->namaorangtua)->first();
        // dd($siswa);
        if ($siswa && $siswa->nama_ortu === $request->namaorangtua) {
            // Simpan informasi siswa di session
            $user = User::where('id', $siswa->user_id)->first();
            if ($user && $user->name == $request->namasiswa) {
                // auth()->login($user);
                session(['ortu_login' => true, 'siswa_id' => $siswa->id, 'siswa_nama' => $user->name, 'nama_ortu' => $siswa->nama_ortu]);
                return redirect('/ortu/dashboard');
                // dd("Login berhasil! Nama Siswa: " . $user->name . ", Nama Orang Tua: " . $siswa->nama_ortu);
            }
        }

        return back()->withErrors(['namasiswa' => 'Nama siswa atau nama orang tua salah.', 'namaorangtua' => 'Nama siswa atau nama orang tua salah.'])->withInput();
    }

    public function logout() {
        // auth()->guard('ortu')->logout();
        session()->flush();
        return redirect('/ortu/login');
    }
}
