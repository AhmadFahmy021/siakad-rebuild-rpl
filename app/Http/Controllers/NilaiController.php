<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (!$siswa) {
            abort(403, 'Hanya siswa yang dapat mengakses halaman ini.');
        }

        // Fetch grades only for this student, with subject & class teacher info
        $nilaiRaw = Nilai::where('siswa_id', $siswa->id)
            ->with(['mataPelajaran', 'kelas.guru.user'])
            ->get();

        $totalNilaiAkhir = 0;
        $nilaiTransformed = $nilaiRaw->map(function ($n) use (&$totalNilaiAkhir) {
            $finalScore = $n->nilai;
            $totalNilaiAkhir += $finalScore;



            // Dynamic Predicate matching the mockup scale perfectly
            $predikat = 'D';
            if ($finalScore >= 95) {
                $predikat = 'A+';
            } elseif ($finalScore >= 90) {
                $predikat = 'A';
            } elseif ($finalScore >= 85) {
                $predikat = 'A-';
            } elseif ($finalScore >= 80) {
                $predikat = 'B+';
            } elseif ($finalScore >= 75) {
                $predikat = 'B';
            } elseif ($finalScore >= 70) {
                $predikat = 'B-';
            } elseif ($finalScore >= 65) {
                $predikat = 'C+';
            } elseif ($finalScore >= 60) {
                $predikat = 'C';
            }

            return (object) [
                'id' => $n->id,
                'mata_pelajaran_nama' => $n->mataPelajaran->nama ?? 'Tidak Diketahui',
                'nilai_akhir' => $finalScore,
                'predikat' => $predikat
            ];
        });

        $totalMataPelajaran = $nilaiTransformed->count();
        $rataRata = $totalMataPelajaran > 0 ? ($totalNilaiAkhir / $totalMataPelajaran) : 0;

        return view('siswa.nilai', compact('nilaiTransformed', 'totalNilaiAkhir', 'rataRata'));
    }

    public function indexOrtu()
    {
        $siswaId = session('siswa_id');
        $siswa = Siswa::find($siswaId);
        if (!$siswa) {
            abort(403, 'Hanya orang tua yang dapat mengakses halaman ini.');
        }

        // Fetch grades only for this student, with subject & class teacher info
        $nilaiRaw = Nilai::where('siswa_id', $siswa->id)
            ->with(['mataPelajaran', 'kelas.guru.user'])
            ->get();

        $totalNilaiAkhir = 0;
        $nilaiTransformed = $nilaiRaw->map(function ($n) use (&$totalNilaiAkhir) {
            $finalScore = $n->nilai;
            $totalNilaiAkhir += $finalScore;

            // Dynamic Predicate matching the mockup scale perfectly
            $predikat = 'D';
            if ($finalScore >= 95) {
                $predikat = 'A+';
            } elseif ($finalScore >= 90) {
                $predikat = 'A';
            } elseif ($finalScore >= 85) {
                $predikat = 'A-';
            } elseif ($finalScore >= 80) {
                $predikat = 'B+';
            } elseif ($finalScore >= 75) {
                $predikat = 'B';
            } elseif ($finalScore >= 70) {
                $predikat = 'B-';
            } elseif ($finalScore >= 65) {
                $predikat = 'C+';
            } elseif ($finalScore >= 60) {
                $predikat = 'C';
            }

            return (object) [
                'id' => $n->id,
                'mata_pelajaran_nama' => $n->mataPelajaran->nama ?? 'Tidak Diketahui',
                'nilai_akhir' => $finalScore,
                'predikat' => $predikat
            ];
        });

        $totalMataPelajaran = $nilaiTransformed->count();
        $rataRata = $totalMataPelajaran > 0 ? ($totalNilaiAkhir / $totalMataPelajaran) : 0;

        return view('siswa.nilai', compact('nilaiTransformed', 'totalNilaiAkhir', 'rataRata'));
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
    public function show(Nilai $nilai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nilai $nilai)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nilai $nilai)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nilai $nilai)
    {
        //
    }
}
