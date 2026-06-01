<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matapelajaran = MataPelajaran::all();
        confirmDelete("Delete Mata Pelajaran!","Apakah Anda yakin ingin menghapus mata pelajaran ini?");
        return view('tu.matapelajaran.index', compact('matapelajaran'));
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
            'name' => 'required|string|max:255',
        ]);

        MataPelajaran::create([
            'nama' => $request->name,
        ]);

        Alert::success('Success', 'Mata Pelajaran created successfully');
        return redirect('/tu/matapelajaran');
    }

    /**
     * Display the specified resource.
     */
    public function show(MataPelajaran $mataPelajaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MataPelajaran $mataPelajaran)
    {
        return view('tu.matapelajaran.edit', compact('mataPelajaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $req = [
            'name' => $request->name,
        ];

        $mataPelajaran->update($req);
        Alert::success('Berhasil', 'Mata Pelajaran berhasil diperbarui.');
        return redirect('/tu/matapelajaran');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MataPelajaran $mataPelajaran)
    {
        $mataPelajaran->delete();
        Alert::success('Berhasil', 'Mata Pelajaran berhasil dihapus.');
        return redirect('/tu/matapelajaran');
    }
}
