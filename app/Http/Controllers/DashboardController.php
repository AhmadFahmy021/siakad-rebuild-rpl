<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\TataUsaha;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\Nilai;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Konsultasi;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function redirectToLogin() {
        return redirect('/login');
    }

    public function indexAdmin()  {
        // Total Uang Tagihan dan yang sudah dibayarkan
        $totalTagihanUang = Tagihan::sum('nominal');
        $totalPembayaranSudah = Pembayaran::where('status', 'approved')->sum('nominal');
        $totalPembayaranBelum = $totalTagihanUang - $totalPembayaranSudah;

        // Pertumbuhan Guru dan Siswa (12 bulan terakhir)
        $guruData = [];
        $siswaData = [];
        $months = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M'); // Format bulan: Jan, Feb, etc
            $months[] = $month;

            // Count guru created in this month
            $guruData[] = Guru::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            // Count siswa created in this month
            $siswaData[] = Siswa::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        // Jumlah Total Tagihan yang sudah dibayarkan dari siswa per kelas/kategori
        $tagihanBayarPerKategori = Pembayaran::where('status', 'approved')
            ->with('tagihan')
            ->get()
            ->groupBy(function($item) {
                return $item->tagihan->category ?? 'Lainnya';
            })
            ->map(function($items) {
                return $items->sum('nominal');
            });

        return view('admin.dashboard', compact(
            'totalTagihanUang',
            'totalPembayaranSudah',
            'totalPembayaranBelum',
            'months',
            'guruData',
            'siswaData',
            'tagihanBayarPerKategori'
        ));
    }

    public function indexGuru()  {
        return view('guru.index');
    }

    public function indexSiswa()  {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (!$siswa) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $kelas = $siswa->kelas()->first();
        $kelasId = $kelas ? $kelas->id : null;

        // 1. Calculate Semester Average Grade (Rata-rata Nilai)
        $nilaiRaw = Nilai::where('siswa_id', $siswa->id)->get();
        $totalNilai = $nilaiRaw->sum('nilai');
        $countNilai = $nilaiRaw->count();
        $rataRataNilai = $countNilai > 0 ? ($totalNilai / $countNilai) : 0;

        // 2. Calculate Tugas Stats
        $totalTugas = $kelasId ? Tugas::where('kelas_id', $kelasId)->count() : 0;

        $selesaiTugas = PengumpulanTugas::where('siswa_id', $siswa->id)
            ->where(function ($query) {
                $query->where('status', 'sudah_mengumpulkan')
                      ->orWhere('status', 'dinilai');
            })->count();

        $aktifTugas = max(0, $totalTugas - $selesaiTugas);

        // Calculate tasks approaching deadline (< 24 hours remaining)
        $mendekatiDeadline = 0;
        if ($kelasId) {
            $now = \Carbon\Carbon::now();
            $upcomingTasksRaw = Tugas::where('kelas_id', $kelasId)
                ->where('due_date', '>', $now)
                ->get();

            foreach ($upcomingTasksRaw as $ut) {
                $submitted = PengumpulanTugas::where('siswa_id', $siswa->id)
                    ->where('tugas_id', $ut->id)
                    ->exists();
                if (!$submitted && \Carbon\Carbon::parse($ut->due_date)->diffInHours($now) <= 24) {
                    $mendekatiDeadline++;
                }
            }
        }

        // 3. Weekly Schedule Matrix (Monday to Friday, dynamic timeslots from DB)
        $scheduleMatrix = [];
        if ($kelasId) {
            $jadwalRaw = Jadwal::where('kelas_id', $kelasId)
                ->with(['matapelajaran', 'guru.user'])
                ->get();

            // Extract unique timeslots and sort them
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

            // Sort timeslots chronologically by start time
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
                $day = ucfirst(strtolower($j->hari)); // Senin, Selasa...
                $start = \Carbon\Carbon::parse($j->jam_mulai)->format('H:i');
                $end = \Carbon\Carbon::parse($j->jam_selesai)->format('H:i');
                $timeLabel = $start . ' - ' . $end;

                if (array_key_exists($day, $scheduleMatrix[$timeLabel] ?? [])) {
                    $scheduleMatrix[$timeLabel][$day] = $j;
                }
            }
        }

        // 4. Upcoming Tasks List (Not yet completed)
        $upcomingTasks = collect();
        if ($kelasId) {
            $now = \Carbon\Carbon::now();
            $upcomingTasks = Tugas::where('kelas_id', $kelasId)
                ->where('due_date', '>', $now)
                ->orderBy('due_date', 'asc')
                ->get()
                ->filter(function ($t) use ($siswa) {
                    return !PengumpulanTugas::where('siswa_id', $siswa->id)
                        ->where('tugas_id', $t->id)
                        ->where(function ($q) {
                            $q->where('status', 'sudah_mengumpulkan')
                              ->orWhere('status', 'dinilai');
                        })->exists();
                })->take(4); // limit to 4 like the mockup
        }

        return view('siswa.dashboard', compact(
            'siswa',
            'kelas',
            'rataRataNilai',
            'totalTugas',
            'selesaiTugas',
            'aktifTugas',
            'mendekatiDeadline',
            'scheduleMatrix',
            'upcomingTasks'
        ));
    }

    public function indexTataUsaha()  {
        // Jumlah Tagihan dan Total Uang yang sudah masuk
        $jumlahTagihan = Tagihan::count();
        $totalUangMasuk = Pembayaran::where('status', 'approve')->sum('nominal');
        $pembayaranPending = Pembayaran::where('status', 'pending')->count();
        $pembayaranApprove = Pembayaran::where('status', 'approved')->count();

        // Data pertumbuhan pembayaran (12 bulan terakhir)
        $pembayaranData = [];
        $months = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M'); // Format bulan: Jan, Feb, etc
            $months[] = $month;

            // Sum pembayaran yang sudah approve di bulan ini
            $pembayaranData[] = Pembayaran::where('status', 'approved')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('nominal');
        }

        // Semua Kelas dengan status pembayaran approve/pending
        $kelas = \App\Models\Kelas::all();

        $kelasStats = $kelas->map(function($k) {
            $totalPending = Pembayaran::where('kelas_id', $k->id)->where('status', 'pending')->count();
            $totalApprove = Pembayaran::where('kelas_id', $k->id)->where('status', 'approved')->count();

            return [
                'id' => $k->id,
                'nama' => $k->name,
                'pending' => $totalPending,
                'approve' => $totalApprove
            ];
        });

        return view('tu.index', compact(
            'jumlahTagihan',
            'totalUangMasuk',
            'pembayaranPending',
            'pembayaranApprove',
            'months',
            'pembayaranData',
            'kelas',
            'kelasStats'
        ));
    }

    // Wali Kelas Methods

    public function indexWalas()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        if (!$guru) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $kelas = Kelas::where('guru_id', $guru->id)->first();
        if (!$kelas) {
            return redirect()->route('dashboard.guru')->with('error', 'Anda bukan wali kelas dari kelas manapun.');
        }

        // Get students in this class
        $students = Siswa::whereHas('kelas', function ($query) use ($kelas) {
            $query->where('kelas_id', $kelas->id);
        })->with('user')->get();

        // Calculate average grade
        $totalSiswa = $students->count();
        
        $studentIds = $students->pluck('id');
        $nilaiRaw = Nilai::whereIn('siswa_id', $studentIds)->get()->groupBy('siswa_id');

        foreach ($students as $student) {
            $grades = $nilaiRaw->get($student->id);
            if ($grades && $grades->count() > 0) {
                $student->rata_rata_nilai = $grades->avg('nilai');
            } else {
                $student->rata_rata_nilai = 0;
            }
        }

        return view('guru.walas', compact('kelas', 'students', 'totalSiswa'));
    }

    public function showWalasSiswa($siswaId)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        if (!$guru) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $kelas = Kelas::where('guru_id', $guru->id)->first();
        if (!$kelas) {
            abort(403, 'Anda bukan wali kelas.');
        }

        $siswa = Siswa::where('id', $siswaId)->with('user')->firstOrFail();

        // Security check
        $belongsToClass = $siswa->kelas()->where('kelas.id', $kelas->id)->exists();
        if (!$belongsToClass) {
            abort(403, 'Siswa tidak berada di kelas Anda.');
        }

        // Calculate class rank
        $allClassStudents = Siswa::whereHas('kelas', function ($query) use ($kelas) {
            $query->where('kelas_id', $kelas->id);
        })->get();

        $studentIds = $allClassStudents->pluck('id');
        $allGrades = Nilai::whereIn('siswa_id', $studentIds)->get()->groupBy('siswa_id');

        foreach ($allClassStudents as $s) {
            $grades = $allGrades->get($s->id);
            $s->rata_rata_nilai = ($grades && $grades->count() > 0) ? $grades->avg('nilai') : 0;
        }

        $rankedStudents = $allClassStudents->sortByDesc('rata_rata_nilai')->values();
        $rank = 1;
        foreach ($rankedStudents as $index => $s) {
            if ($s->id === $siswa->id) {
                $rank = $index + 1;
                break;
            }
        }
        $totalSiswa = $allClassStudents->count();

        // Fetch grades for academic performance table
        $nilaiRaw = Nilai::where('siswa_id', $siswa->id)
            ->where('kelas_id', $kelas->id)
            ->with('matapelajaran')
            ->get();

        $nilaiTransformed = $nilaiRaw->map(function ($n) {
            $finalScore = $n->nilai;
            
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

            $remarks = 'Tuntas, terus tingkatkan prestasi belajar Anda.';
            if ($finalScore >= 90) {
                $remarks = 'Sangat memuaskan, luar biasa mempertahankan pencapaian.';
            } elseif ($finalScore < 75) {
                $remarks = 'Memerlukan usaha lebih besar dan bimbingan tambahan.';
            }

            return (object) [
                'subject_name' => $n->matapelajaran->nama ?? 'Tidak Diketahui',
                'score' => $finalScore,
                'kkm' => 75,
                'grade' => $predikat,
                'remarks' => $remarks,
            ];
        });

        $rataRata = $nilaiTransformed->count() > 0 ? $nilaiTransformed->avg('score') : 0;

        // Fetch latest note (from konsultasi table)
        $catatan = Konsultasi::where('siswa_id', $siswa->id)
            ->where('kelas_id', $kelas->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return view('guru.walas_detail', compact('kelas', 'siswa', 'nilaiTransformed', 'rataRata', 'rank', 'totalSiswa', 'catatan'));
    }

    public function storeWalasCatatan(Request $request, $siswaId)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        if (!$guru) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $kelas = Kelas::where('guru_id', $guru->id)->first();
        if (!$kelas) {
            abort(403, 'Anda bukan wali kelas.');
        }

        $siswa = Siswa::where('id', $siswaId)->firstOrFail();
        $belongsToClass = $siswa->kelas()->where('kelas.id', $kelas->id)->exists();
        if (!$belongsToClass) {
            abort(403, 'Siswa tidak berada di kelas Anda.');
        }

        $request->validate([
            'catatan' => 'required|string|max:500',
        ]);

        Konsultasi::updateOrCreate(
            [
                'siswa_id' => $siswa->id,
                'kelas_id' => $kelas->id,
            ],
            [
                'title' => 'Catatan Wali Kelas',
                'description' => $request->catatan,
            ]
        );

        if (class_exists(\RealRashid\SweetAlert\Facades\Alert::class)) {
            \RealRashid\SweetAlert\Facades\Alert::success('Berhasil', 'Catatan perkembangan siswa berhasil disimpan.');
        }

        return redirect()->route('guru.walas.siswa', $siswa->id)->with('success', 'Catatan perkembangan siswa berhasil disimpan.');
    }
}
