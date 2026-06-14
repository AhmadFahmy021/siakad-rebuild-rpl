<?php
namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('user')->get();
        $users = User::all();
        confirmDelete("Delete Akses Guru!", "Apakah Anda yakin ingin menghapus guru ini?");
        return view('admin.kelola.guru.index', compact('gurus', 'users'));
    }

    public function create()
    {
        // Sudah pakai modal di index, tidak perlu halaman terpisah
    }

    public function store(Request $request)
    {
        $request->validate([
            'user' => 'required|exists:users,id',
        ]);

        if (Guru::where('user_id', $request->user)->exists()) {
            Alert::error('Gagal', 'Guru sudah terdaftar.');
            return redirect()->route('guru.index');
        }

        Guru::create(['user_id' => $request->user]);
        Alert::success('Berhasil', 'Guru berhasil ditambahkan.');
        return redirect()->route('guru.index');
    }

    public function show(Guru $guru)
    {
        // Tidak diperlukan — info sudah tampil di tabel index
    }

    /**
     * ── BARU: Isi method edit() ──────────────────────────────────────
     * Mengembalikan data guru dalam format JSON
     * Dipakai oleh modal edit via AJAX (tanpa pindah halaman)
     */
    public function edit(Guru $guru)
    {
        // Load relasi user agar nama & email ikut terkirim ke modal
        $guru->load('user');

        return response()->json([
            'id'       => $guru->id,
            'user_id'  => $guru->user_id,
            'name'     => $guru->user->name,
            'email'    => $guru->user->email,
            'username' => $guru->user->username,
        ]);
    }

    /**
     * ── BARU: Isi method update() ────────────────────────────────────
     * Ganti user yang terhubung ke akun guru ini
     */
    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'user' => 'required|exists:users,id',
        ]);

        // Cek apakah user yang dipilih sudah dipakai guru LAIN
        $sudahAda = Guru::where('user_id', $request->user)
            ->where('id', '!=', $guru->id) // kecualikan guru ini sendiri
            ->exists();

        if ($sudahAda) {
            Alert::error('Gagal', 'User ini sudah terdaftar sebagai guru lain.');
            return redirect()->route('guru.index');
        }

        $guru->update(['user_id' => $request->user]);
        Alert::success('Berhasil', 'Data guru berhasil diperbarui.');
        return redirect()->route('guru.index');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();
        Alert::success('Berhasil', 'Guru berhasil dihapus.');
        return redirect()->route('guru.index');
    }
}
