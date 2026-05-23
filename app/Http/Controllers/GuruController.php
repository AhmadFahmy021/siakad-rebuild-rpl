<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class GuruController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gurus = Guru::with('user')->get();
        $users = User::all();
        confirmDelete("Delete Akses Guru!","Apakah Anda yakin ingin menghapus guru ini?");
        return view('admin.kelola.guru.index', compact('gurus', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user' => 'required|exists:users,id',
        ]);

        $req = [
            'user_id' => $request->user,
        ];

        if (Guru::where('user_id', $request->user)->exists()) {
            Alert::error('Gagal', 'Guru sudah terdaftar.');
            // Alert::toast('Guru sudah terdaftar.', 'error');
            return redirect()->route('guru.index');
        }

        Guru::create($req);
        Alert::success('Berhasil', 'Guru berhasil ditambahkan.');
        return redirect()->route('guru.index');
        }

    /**
     * Display the specified resource.
     */
    public function show(Guru $guru)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guru $guru)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guru $guru)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $guru)
    {
        $guru->delete();
        Alert::success('Berhasil', 'Guru berhasil dihapus.');
        return redirect()->route('guru.index');
    }
}
