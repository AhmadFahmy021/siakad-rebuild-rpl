<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\SiswaKelas;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::with(('guru.user'))->get();
        $guru = Guru::with('user')->whereNotIn('id', $kelas->pluck('guru_id'))->get();
        confirmDelete("Delete Kelas!","Apakah Anda yakin ingin menghapus kelas ini?");
        return view('tu.kelas.index', compact('kelas', 'guru'));
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
            'kelas' => 'required|string|max:255',
            'guru' => 'required|exists:guru,id',
        ]);

        Kelas::create([
            'name' => $request->kelas,
            'guru_id' => $request->guru,
        ]);

        Alert::success('Success', 'Kelas created successfully');
        return redirect('/tu/kelas');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kelas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas)
    {
        $guru = Guru::with('user')->get();
        return view('tu.kelas.edit', compact('kelas', 'guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'kelas' => 'required|string|max:255',
            'guru' => 'required|exists:guru,id',
        ]);

        $kelas->update([
            'name' => $request->kelas,
            'guru_id' => $request->guru,
        ]);

        Alert::success('Success', 'Kelas updated successfully');
        return redirect('/tu/kelas');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        Alert::success('Success', 'Kelas deleted successfully');
        return redirect('/tu/kelas');
    }

    public function kelola(Kelas $kelas) {
        $siswaKelas = SiswaKelas::with('siswa.user')->where('kelas_id', $kelas->id)->get()->pluck('siswa_id');
        $siswa = Siswa::with('user')->whereNotIn('id', $siswaKelas)->get();
        return view('tu.kelas.kelola', compact('kelas', 'siswa', 'siswaKelas'));
    }
}
