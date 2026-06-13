<?php

namespace App\Http\Controllers;

use App\Models\Bank;
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
        $role = request()->segment(1);
        return view("$role.pembayaran.index", compact('pembayarans', 'siswa', 'kelas'));
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
        return redirect()->back();
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
        $role = request()->segment(1);
        return view("$role.pembayaran.edit", compact('pembayaran', 'siswa', 'kelas', 'tagihan'));
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
        $role = request()->segment(1);
        return redirect("$role/pembayaran");
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
        $role = request()->segment(1);
        return redirect("$role/pembayaran");
    }


    public function approve(Pembayaran $pembayaran)
    {
        $pembayaran->update(['status' => 'approved']);
        Alert::success('Berhasil', 'Pembayaran berhasil di-approve.');
        return back();
    }

    public function reject(Pembayaran $pembayaran)
    {
        $pembayaran->update(['status' => 'rejected']);
        Alert::success('Berhasil', 'Pembayaran berhasil di-reject.');
        return back();
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

    public function getTagihanByTagihanId(string $tagihanId)
    {

        $tagihans = Tagihan::where('id', $tagihanId)->get();
        // dd($tagihans);
        return response()->json($tagihans);
    }


    // ================================ ORTU ====================================
    public function indexOrtu()
    {
            $siswaId = session('siswa_id');

            $siswaKelas = SiswaKelas::where('siswa_id', $siswaId)->with('kelas')->first();
            $tagihanLunas = Pembayaran::where('siswa_id', $siswaId)->where('status', 'approved')->pluck('tagihan_id');
            $tagihan = Tagihan::where(function ($query) use ($siswaKelas) {
                            $query->where('kelas_id', $siswaKelas->kelas_id)
                                ->orWhereNull('kelas_id');
                        })
                        ->whereNotIn('id', $tagihanLunas)
                        ->with('kelas')
                        ->get();
            $pembayaran = Pembayaran::where('siswa_id', $siswaId)->get()->keyBy('tagihan_id');
            return view("ortu.pembayaran.index", compact('tagihan', 'pembayaran'));
    }

    public function bayar($tagihan)
    {
        $tagihan = Tagihan::findOrFail($tagihan);
        $siswaId = session('siswa_id');
        $siswaKelas = SiswaKelas::where('siswa_id', $siswaId)->with('kelas')->first();
        $bank = Bank::all();
        $pembayaran = Pembayaran::where('siswa_id', $siswaId)->where('tagihan_id', $tagihan->id)->first();
        return view("ortu.pembayaran.bayar", compact('tagihan', 'siswaKelas', 'bank', 'pembayaran'));
    }

    public function bayarStore(Request $request, $tagihan)
    {
        $siswaId = session('siswa_id');
        $siswaKelas = SiswaKelas::where('siswa_id', $siswaId)->with('kelas')->first();

        $date = now()->format('Y-m-d H:i:s');
        $pembayaran = Pembayaran::where('siswa_id', $siswaId)->where('tagihan_id', $tagihan)->first();
        if ($pembayaran) {
            $request->validate([
                'nominal' => 'required|integer|min:0',
                'bukti_pembayaran' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
                'semester' => 'required|string|max:20',
                'bank' => 'required|exists:banks,id',
            ]);

            $buktiPembayaran = $pembayaran->bukti_pembayaran;

            if ($request->hasFile('bukti_pembayaran')) {
                if ($pembayaran->bukti_pembayaran && Storage::disk('public')->exists($pembayaran->bukti_pembayaran) ) {
                    Storage::disk('public')
                        ->delete($pembayaran->bukti_pembayaran);
                }

                $buktiPembayaran = $request
                    ->file('bukti_pembayaran')
                    ->store('bukti-pembayaran', 'public');
            }

            $pembayaran->update([
                'nominal' => $request->nominal,
                'tanggal' => $date,
                'semester' => $request->semester,
                'status' => 'pending',
                'bukti_pembayaran' => $buktiPembayaran,
                'bank_id' => $request->bank,
            ]);
            Alert::success('Berhasil', 'Pembayaran berhasil diajukan.');
            return redirect('ortu/pembayaran');
        }
        $request->validate([
            'nominal' => 'required|integer|min:0',
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'semester' => 'required|string|max:20',
            'bank' => 'required|exists:banks,id',
        ]);

        $buktiPembayaran = $request
            ->file('bukti_pembayaran')
            ->store('bukti-pembayaran', 'public');

        Pembayaran::create([
            'kelas_id' => $siswaKelas->kelas_id,
            'siswa_id' => $siswaId,
            'tagihan_id' => $tagihan,
            'nominal' => $request->nominal,
            'tanggal' => $date,
            'semester' => $request->semester,
            'status' => 'pending',
            'bukti_pembayaran' => $buktiPembayaran,
            'bank_id' => $request->bank,
        ]);

        Alert::success('Berhasil', 'Pembayaran berhasil diajukan.');
        return redirect('ortu/pembayaran');
    }
}
