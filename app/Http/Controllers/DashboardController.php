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

        // 3. Weekly Schedule Matrix (Monday to Friday)
        $scheduleMatrix = [];
        $timeslots = [];

        if ($kelasId) {
            $jadwalRaw = Jadwal::where('kelas_id', $kelasId)
                ->with('mataPelajaran')
                ->get();

            // Generate unique timeslots from data
            foreach ($jadwalRaw as $j) {
                $timeLabel = substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5);
                $timeslots[$timeLabel] = $timeLabel;
            }
            ksort($timeslots);
        }

        // Fallback to default slots if empty
        if (empty($timeslots)) {
            $timeslots = [
                '07:30 - 09:00' => '07:30 - 09:00',
                '09:30 - 11:00' => '09:30 - 11:00',
                '11:00 - 12:30' => '11:00 - 12:30',
            ];
        }

        foreach ($timeslots as $timeLabel) {
            $scheduleMatrix[$timeLabel] = [
                'Senin' => null,
                'Selasa' => null,
                'Rabu' => null,
                'Kamis' => null,
                'Jumat' => null,
            ];
        }

        if ($kelasId && isset($jadwalRaw)) {
            foreach ($jadwalRaw as $j) {
                $day = ucfirst(strtolower($j->hari)); // Senin, Selasa...
                $timeLabel = substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5);
                if (isset($scheduleMatrix[$timeLabel])) {
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
        // dd($jumlahTagihan);
        $totalUangMasuk = Pembayaran::where('status', 'approved')->sum('nominal');
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
        $kelas = Kelas::all();
        // dd($kelas->count());
        $totalKelas = $kelas->count();


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
            'kelasStats',
            'totalKelas'
        ));
    }

    public function indexOrtu()
    {
        $siswaId = session('siswa_id');
        if (!$siswaId) {
            return redirect('/ortu/login');
        }

        $siswa = Siswa::with('user')->find($siswaId);
        if (!$siswa) {
            session()->flush();
            return redirect('/ortu/login');
        }

        $kelas = $siswa->kelas()->first();
        $kelasId = $kelas ? $kelas->id : null;

        // 1. Calculate unpaid bills (total tagihan belum dibayar)
        $tagihanLunas = Pembayaran::where('siswa_id', $siswaId)->where('status', 'approved')->pluck('tagihan_id');
        $tagihanBelumBayar = Tagihan::where(function ($query) use ($kelasId) {
                if ($kelasId) {
                    $query->where('kelas_id', $kelasId);
                }
                $query->orWhereNull('kelas_id');
            })
            ->whereNotIn('id', $tagihanLunas)
            ->get();

        $totalTagihanBelumBayar = $tagihanBelumBayar->sum('nominal');

        // 2. Approved payments history (riwayat pembayaran yang sudah diapprove)
        $riwayatPembayaran = Pembayaran::where('siswa_id', $siswaId)
            ->where('status', 'approved')
            ->with('tagihan')
            ->orderBy('tanggal', 'desc')
            ->get();

        // 3. Weekly schedule matrix (data jadwal anak)
        $scheduleMatrix = [];
        $timeslots = [];
        $totalJadwal = 0;
        $upcomingJadwal = null;

        if ($kelasId) {
            $totalJadwal = Jadwal::where('kelas_id', $kelasId)->count();
            $jadwals = Jadwal::where('kelas_id', $kelasId)->with(['matapelajaran', 'guru.user'])->get();

            // Generate unique timeslots from data
            foreach ($jadwals as $j) {
                $timeLabel = substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5);
                $timeslots[$timeLabel] = $timeLabel;
            }
            ksort($timeslots);
        }

        // Fallback to default slots if empty
        if (empty($timeslots)) {
            $timeslots = [
                '07:30 - 09:00' => '07:30 - 09:00',
                '09:30 - 11:00' => '09:30 - 11:00',
                '11:00 - 12:30' => '11:00 - 12:30',
            ];
        }

        foreach ($timeslots as $timeLabel) {
            $scheduleMatrix[$timeLabel] = [
                'Senin' => null,
                'Selasa' => null,
                'Rabu' => null,
                'Kamis' => null,
                'Jumat' => null,
            ];
        }

        if ($kelasId && isset($jadwals)) {
            // Populate schedule matrix
            foreach ($jadwals as $j) {
                $day = ucfirst(strtolower($j->hari)); // Senin, Selasa...
                $timeLabel = substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5);
                if (isset($scheduleMatrix[$timeLabel])) {
                    $scheduleMatrix[$timeLabel][$day] = $j;
                }
            }

            // 4. Closest upcoming schedule from now
            $now = Carbon::now();
            $currentDayOfWeek = $now->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
            $currentTime = $now->format('H:i:s');
            $daysOrder = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

            $sortedJadwals = $jadwals->sortBy(function ($jadwal) use ($currentDayOfWeek, $currentTime, $daysOrder) {
                $scheduleDayIndex = array_search(ucfirst(strtolower($jadwal->hari)), $daysOrder);
                if ($scheduleDayIndex === false) {
                    $scheduleDayIndex = 1;
                }
                $dayDiff = $scheduleDayIndex - $currentDayOfWeek;
                if ($dayDiff < 0) {
                    $dayDiff += 7;
                } elseif ($dayDiff === 0 && strcmp($jadwal->jam_mulai, $currentTime) < 0) {
                    $dayDiff += 7;
                }
                return sprintf('%d_%s', $dayDiff, $jadwal->jam_mulai);
            });

            $upcomingJadwal = $sortedJadwals->first();
        }

        return view('ortu.index', compact(
            'siswa',
            'kelas',
            'totalTagihanBelumBayar',
            'riwayatPembayaran',
            'totalJadwal',
            'upcomingJadwal',
            'scheduleMatrix'
        ));
    }
}
