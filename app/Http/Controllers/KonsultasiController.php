<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class KonsultasiController extends Controller
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

        $kelas = $siswa->kelas()->first();
        $waliKelas = $kelas ? $kelas->guru : null;

        // Fetch the latest wali kelas feedback/bimbingan
        $konsultasi = Konsultasi::where('siswa_id', $siswa->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // MVC: Move all presentation calculations to controller
        $studentName = Auth::user()->name;
        $className = $kelas->name ?? 'Belum Ditentukan';
        
        $teacherName = $waliKelas->user->name ?? 'Belum Ditentukan';
        $teacherSubject = $waliKelas ? 'Wali Kelas ' . $className . ' & Guru' : 'Belum Ada Wali Kelas';
        
        $feedbackTitle = $konsultasi->title ?? 'Catatan belum diberikan wali kelas';
        $feedbackDate = $konsultasi ? \Carbon\Carbon::parse($konsultasi->updated_at)->translatedFormat('d F Y') : null;
        $feedbackText = $konsultasi->description ?? 'Belum ada catatan perkembangan yang diberikan oleh wali kelas Anda untuk semester ini.';

        return view('siswa.konsultasi', compact(
            'studentName',  
            'className',  
            'teacherName', 
            'teacherSubject', 
            'feedbackTitle', 
            'feedbackDate', 
            'feedbackText'
        ));
    }

    public function indexOrtu()
    {
        $siswaId = session('siswa_id');
        $siswa = Siswa::find($siswaId);
        
        if (!$siswa) {
            abort(403, 'Hanya orang tua yang dapat mengakses halaman ini.');
        }

        $kelas = $siswa->kelas()->first();
        $waliKelas = $kelas ? $kelas->guru : null;

        // Fetch the latest wali kelas feedback/bimbingan
        $konsultasi = Konsultasi::where('siswa_id', $siswa->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // MVC: Move all presentation calculations to controller
        $studentName = $siswa->user->name; // Use student's name, not Auth::user() because Auth::user() is the parent
        $className = $kelas->name ?? 'Belum Ditentukan';
        
        $teacherName = $waliKelas->user->name ?? 'Belum Ditentukan';
        $teacherSubject = $waliKelas ? 'Wali Kelas ' . $className . ' & Guru' : 'Belum Ada Wali Kelas';
        
        $feedbackTitle = $konsultasi->title ?? 'Catatan belum diberikan wali kelas';
        $feedbackDate = $konsultasi ? \Carbon\Carbon::parse($konsultasi->updated_at)->translatedFormat('d F Y') : null;
        $feedbackText = $konsultasi->description ?? 'Belum ada catatan perkembangan yang diberikan oleh wali kelas Anda untuk semester ini.';

        return view('siswa.konsultasi', compact(
            'studentName',  
            'className',  
            'teacherName', 
            'teacherSubject', 
            'feedbackTitle', 
            'feedbackDate', 
            'feedbackText'
        ));
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
    public function show(Konsultasi $konsultasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Konsultasi $konsultasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Konsultasi $konsultasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Konsultasi $konsultasi)
    {
        //
    }
}
