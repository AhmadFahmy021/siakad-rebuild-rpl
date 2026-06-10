<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Siswa;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (!$siswa) {
            abort(403, 'Hanya siswa yang dapat mengakses halaman ini.');
        }

        // Get class IDs for the logged-in student
        $kelasIds = $siswa->kelas()->pluck('kelas.id')->toArray();

        // If specific task details are requested:
        if ($request->filled('id')) {
            $tugas = Tugas::where('status', 'PUBLISHED')
                ->whereIn('kelas_id', $kelasIds)
                ->with([
                    'pengumpulanTugas' => function ($query) use ($siswa) {
                        $query->where('siswa_id', $siswa->id);
                    },
                    'kelas',
                    'matapelajaran',
                    'guru.user',
                ])
                ->findOrFail($request->id);

            return view('siswa.tugas_detail', compact('tugas', 'siswa'));
        }

        // Otherwise get all assignments
        $tugasList = Tugas::where('status', 'PUBLISHED')
            ->whereIn('kelas_id', $kelasIds)
            ->with([
                'pengumpulanTugas' => function ($query) use ($siswa) {
                    $query->where('siswa_id', $siswa->id);
                },
                'kelas',
                'matapelajaran',
                'guru.user',
            ])
            ->orderBy('due_date', 'asc')
            ->get();

        return view('siswa.tugas', compact('tugasList', 'siswa'));
    }

    /**
     * Store a newly created resource submission in storage.
     */
    public function storeSubmission(Request $request, Tugas $tugas)
    {
        $request->validate([
            'file_jawaban' => 'nullable|file|mimes:pdf,zip,rar,doc,docx,png,jpg,jpeg|max:10240', // 10MB
            'link' => 'nullable|url|max:255',
            'catatan' => 'nullable|string|max:1000',
            'jawaban_teks' => 'nullable|string',
        ]);

        if (!$request->hasFile('file_jawaban') && !$request->filled('link') && !$request->filled('jawaban_teks')) {
            return back()->with('error', 'Harap unggah file jawaban, sertakan link, atau tulis jawaban teks!');
        }

        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (!$siswa) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Check if there is already a submission
        $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        // If already graded, cannot modify
        if ($pengumpulan && $pengumpulan->status === 'dinilai') {
            return back()->with('error', 'Tugas ini sudah dinilai dan tidak dapat diubah kembali.');
        }

        $filePath = $pengumpulan ? $pengumpulan->file_path : null;

        if ($request->hasFile('file_jawaban')) {
            // Delete old file if updating
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $file = $request->file('file_jawaban');
            $filename = time() . '_' . $siswa->id . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('tugas_jawaban', $filename, 'public');
        }

        $data = [
            'tugas_id' => $tugas->id,
            'siswa_id' => $siswa->id,
            'file_path' => $filePath,
            'link' => $request->link,
            'catatan' => $request->catatan,
            'jawaban_teks' => $request->jawaban_teks,
            'status' => 'sudah_mengumpulkan',
        ];

        if ($pengumpulan) {
            $pengumpulan->update($data);
        } else {
            PengumpulanTugas::create($data);
        }

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
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
    public function show(Tugas $tugas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tugas $tugas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tugas $tugas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tugas $tugas)
    {
        //
    }
}
