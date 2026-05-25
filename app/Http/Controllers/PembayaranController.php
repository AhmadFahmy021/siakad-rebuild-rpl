<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\SiswaKelas;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembayarans = Pembayaran::with('tagihan', 'siswa', 'kelas')->get();
        $siswa = Siswa::with('user')->get();
        $kelas = Kelas::all();
        // $tagihan = Tagihan::with('kelas')->get();
        confirmDelete("Delete Pembayaran!","Apakah Anda yakin ingin menghapus pembayaran ini?");
        return view('admin.pembayaran.index', compact('pembayarans', 'siswa', 'kelas'));
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
            'siswa' => 'required|exists:siswa,id',

            'nominal' => 'required|integer|min:0',

            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'tanggal' => 'required|date',

            'kelas' => 'required|exists:kelas,id',

            'semester' => 'required|string|max:20',

            'tagihan' => 'required|exists:tagihan,id',

            'status' => 'required|in:pending,approved,rejected',
        ]);

        $buktiPembayaran = null;

        if ($request->hasFile('bukti_pembayaran')) {

            $buktiPembayaran = $request
                ->file('bukti_pembayaran')
                ->store('bukti-pembayaran', 'public');

        }

        $req = [
            'kelas_id' => $request->kelas,
            'siswa_id' => $request->siswa,
            'tagihan_id' => $request->tagihan,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'semester' => $request->semester,
            'status' => $request->status,
            'bukti_pembayaran' => $buktiPembayaran,
        ];

        Pembayaran::create($req);
        Alert::success('Berhasil', 'Pembayaran berhasil ditambahkan.');
        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembayaran $pembayaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembayaran $pembayaran)
    {
        $siswa = Siswa::with('user')->get();
        $kelas = Kelas::all();
        $tagihan = Tagihan::with('kelas')->get();
        return view('admin.pembayaran.edit', compact('pembayaran', 'siswa', 'kelas', 'tagihan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'siswa' => 'sometimes|exists:siswa,id',

            'nominal' => 'sometimes|integer|min:0',

            'bukti_pembayaran' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',

            'tanggal' => 'sometimes|date',

            'kelas' => 'sometimes|exists:kelas,id',

            'semester' => 'sometimes|string|max:20',

            'tagihan' => 'sometimes|exists:tagihan,id',

            'status' => 'sometimes|in:pending,approved,rejected',
        ]);

        $buktiPembayaran = $pembayaran->bukti_pembayaran;

        if ($request->hasFile('bukti_pembayaran')) {
            if ($buktiPembayaran && Storage::disk('public')->exists($buktiPembayaran) ) {
                Storage::disk('public')
                    ->delete($buktiPembayaran);
            }
            $buktiPembayaran = $request
                ->file('bukti_pembayaran')
                ->store('bukti-pembayaran', 'public');

        }

        $req = [
            'kelas_id' => $request->kelas,
            'siswa_id' => $request->siswa,
            'tagihan_id' => $request->tagihan,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'semester' => $request->semester,
            'status' => $request->status,
            'bukti_pembayaran' => $buktiPembayaran,
        ];

        $pembayaran->update($req);
        Alert::success('Berhasil', 'Pembayaran berhasil diperbarui.');
        return redirect('admin/pembayaran');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        if ($pembayaran->bukti_pembayaran && Storage::disk('public')->exists($pembayaran->bukti_pembayaran) ) {
            Storage::disk('public')
                ->delete($pembayaran->bukti_pembayaran);
        }
        $pembayaran->delete();
        Alert::success('Berhasil', 'Pembayaran berhasil dihapus.');
        return redirect('admin/pembayaran');
    }


    public function getSiswaByKelas(string $kelasId)
    {

        $siswas = SiswaKelas::where('kelas_id', $kelasId)->with('siswa.user')->get();
        return response()->json($siswas);
    }

    public function getTagihanByKelasId(string $kelasId)
    {

        $tagihans = Tagihan::where('kelas_id', $kelasId)
                        ->orWhereNull('kelas_id')
                        ->get();
        return response()->json($tagihans);
    }
}
