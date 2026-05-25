<?php

namespace App\Http\Controllers;

use App\Models\TataUsaha;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TataUsahaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tataUsahas = TataUsaha::with('user')->get();
        $users = User::all();
        confirmDelete("Delete Akses Tata Usaha!","Apakah Anda yakin ingin menghapus akses tata usaha ini?");
        return view('admin.kelola.tatausaha.index', compact('tataUsahas', 'users'));
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

        if (TataUsaha::where('user_id', $request->user)->exists()) {
            Alert::error('Gagal', 'Tata Usaha sudah terdaftar.');
            return redirect()->route('tu.index');
        }

        TataUsaha::create($req);
        Alert::success('Berhasil', 'Tata Usaha berhasil ditambahkan.');
        return redirect()->route('tu.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(TataUsaha $tataUsaha)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TataUsaha $tataUsaha)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TataUsaha $tataUsaha)
    {
        $request->validate([
            'user' => 'required|exists:users,id',
        ]);

        $req = [
            'user_id' => $request->user,
        ];

        if (TataUsaha::where('user_id', $request->user)->where('id', '!=', $tataUsaha->id)->exists()) {
            Alert::error('Gagal', 'Tata Usaha sudah terdaftar.');
            return redirect()->route('tu.index');
        }

        $tataUsaha->update($req);
        Alert::success('Berhasil', 'Tata Usaha berhasil diperbarui.');
        return redirect()->route('tu.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TataUsaha $tataUsaha)
    {
        $tataUsaha->delete();
        Alert::success('Berhasil', 'Tata Usaha berhasil dihapus.');
        return redirect()->route('tu.index');
    }
}
