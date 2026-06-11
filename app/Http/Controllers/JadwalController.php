<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\SiswaKelas;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jadwal = Jadwal::with(['kelas', 'matapelajaran', 'guru'])->get();
        $kelas = Kelas::all();
        $matapelajaran = MataPelajaran::all();
        $guru = Guru::all();
        confirmDelete("Delete Jadwal!","Apakah Anda yakin ingin menghapus jadwal ini?");
        return view('tu.jadwal.index', compact('jadwal', 'kelas', 'matapelajaran', 'guru'));
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
            'kelas' => 'required|exists:kelas,id',
            'matapelajaran' => 'required|exists:mata_pelajaran,id',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'guru' => 'required|exists:guru,id',
        ]);

        Jadwal::create([
            'kelas_id' => $request->kelas,
            'mata_pelajaran_id' => $request->matapelajaran,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'guru_id' => $request->guru,
        ]);

        Alert::success('Success', 'Jadwal created successfully');
        return redirect('/tu/jadwal');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jadwal $jadwal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jadwal $jadwal)
    {
        $kelas = Kelas::all();
        $matapelajaran = MataPelajaran::all();
        $guru = Guru::all();
        return view('tu.jadwal.edit', compact('jadwal', 'kelas', 'matapelajaran', 'guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'kelas' => 'required|exists:kelas,id',
            'matapelajaran' => 'required|exists:mata_pelajaran,id',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'guru' => 'required|exists:guru,id',
        ]);

        $req = [
            'kelas_id' => $request->kelas,
            'mata_pelajaran_id' => $request->matapelajaran,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'guru_id' => $request->guru,
        ];

        $jadwal->update($req);
        Alert::success('Berhasil', 'Jadwal berhasil diperbarui.');
        return redirect('/tu/jadwal');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        Alert::success('Berhasil', 'Jadwal berhasil dihapus.');
        return redirect('/tu/jadwal');
    }

    public function indexOrtu() {
        $siswaId = session('siswa_id');
        $siswaKelas = SiswaKelas::where('siswa_id', $siswaId)->with('kelas')->first();
        $jadwal = Jadwal::where('kelas_id', $siswaKelas->kelas->id)->with(['kelas', 'matapelajaran', 'guru'])->get();
        return view('ortu.jadwal.index', compact('jadwal'));
    }

    public function indexGuru()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        $jadwalRaw = collect();
        $scheduleMatrix = [];

        if ($guru) {
            $jadwalRaw = Jadwal::where('guru_id', $guru->id)
                ->with(['matapelajaran', 'kelas'])
                ->get();

            // Extract unique timeslots
            $uniqueTimeslots = [];
            foreach ($jadwalRaw as $j) {
                $start = \Carbon\Carbon::parse($j->jam_mulai)->format('H:i');
                $end = \Carbon\Carbon::parse($j->jam_selesai)->format('H:i');
                $timeLabel = $start . ' - ' . $end;
                $uniqueTimeslots[$timeLabel] = [
                    'start' => $j->jam_mulai,
                    'end' => $j->jam_selesai,
                ];
            }

            // Sort timeslots chronologically
            uksort($uniqueTimeslots, function($a, $b) use ($uniqueTimeslots) {
                return strtotime($uniqueTimeslots[$a]['start']) <=> strtotime($uniqueTimeslots[$b]['start']);
            });

            // Initialize matrix
            foreach ($uniqueTimeslots as $timeLabel => $times) {
                $scheduleMatrix[$timeLabel] = [
                    'Senin' => null,
                    'Selasa' => null,
                    'Rabu' => null,
                    'Kamis' => null,
                    'Jumat' => null,
                ];
            }

            // Populate matrix
            foreach ($jadwalRaw as $j) {
                $day = ucfirst(strtolower($j->hari));
                $start = \Carbon\Carbon::parse($j->jam_mulai)->format('H:i');
                $end = \Carbon\Carbon::parse($j->jam_selesai)->format('H:i');
                $timeLabel = $start . ' - ' . $end;

                if (isset($scheduleMatrix[$timeLabel]) && array_key_exists($day, $scheduleMatrix[$timeLabel])) {
                    $scheduleMatrix[$timeLabel][$day] = $j;
                }
            }
        }

        $totalJadwal = $jadwalRaw->count();

        return view('guru.jadwal', compact('scheduleMatrix', 'totalJadwal'));
    }
}
