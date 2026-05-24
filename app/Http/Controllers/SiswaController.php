<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::all()->pluck('user_id');
        // dd($admins);
        $siswas = Siswa::with('user')->get();
        $siswasUserIds = $siswas->pluck('user_id');
        $users = User::whereNotIn('id', $admins)->whereNotIn('id', $siswasUserIds)->get();
        // dd($users);
        return view('admin.kelola.siswa.index', compact('siswas', 'users'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        return view('admin.kelola.siswa.edit', compact('siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'orang_tua' => 'required|string|max:255',
        ]);

        $siswa->update([
            'nama_ortu' => $request->orang_tua,
        ]);
        Alert::success('Berhasil', 'Data siswa ' . strtoupper($siswa->user->name) . ' berhasil diperbarui.');
        return redirect()->route('siswa.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        Alert::success('Berhasil', 'Data siswa ' . strtoupper($siswa->user->name) . ' berhasil dihapus.');
        return redirect()->route('siswa.index');
    }
}
