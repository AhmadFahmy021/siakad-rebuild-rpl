<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tagihans = Tagihan::all();
        $kelas = Kelas::with(['guru', 'guru.user'])->get();
        confirmDelete("Delete Tagihan!","Apakah Anda yakin ingin menghapus tagihan ini?");
        return view('admin.tagihan.index', compact('tagihans', 'kelas'));
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
            'name' => 'required',
            'deskripsi' => 'required',
            'category' => 'required',
            'nominal' => 'required|integer|min:0',
        ]);

        $req = [
            'name' => $request->name,
            'deskripsi' => $request->deskripsi,
            'category' => $request->category,
            'nominal' => $request->nominal,
            'kelas_id' => $request->kelas ?: null, // null = berlaku untuk semua kelas
        ];

        Tagihan::create($req);
        Alert::success('Berhasil', 'Tagihan berhasil ditambahkan.');
        return redirect('admin/tagihan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tagihan $tagihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tagihan $tagihan)
    {
        $kelas = Kelas::with(['guru', 'guru.user'])->get();
        return view('admin.tagihan.edit', compact('tagihan', 'kelas'));
        // return view('admin.tagihan.edit', compact('tagihan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'name' => 'sometimes',
            'deskripsi' => 'sometimes',
            'category' => 'sometimes',
            'nominal' => 'sometimes|integer|min:0',
        ]);

        $req = [
            'name' => $request->name,
            'deskripsi' => $request->deskripsi,
            'category' => $request->category,
            'nominal' => $request->nominal,
            'kelas_id' => $request->kelas ?: null, // null = berlaku untuk semua kelas
        ];

        $tagihan->update($req);
        Alert::success('Berhasil', 'Tagihan berhasil diperbarui.');
        return redirect('admin/tagihan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tagihan $tagihan)
    {
        $tagihan->delete();
        Alert::success('Berhasil', 'Tagihan berhasil dihapus.');
        return redirect('admin/tagihan');
    }
}
