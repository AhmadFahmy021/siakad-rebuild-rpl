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

        // 3. Weekly Schedule Matrix (Monday to Friday, 3 timeslots)
        $timeslots = [
            '07:30:00 - 09:00:00' => '07:30 - 09:00',
            '09:30:00 - 11:00:00' => '09:30 - 11:00',
            '11:00:00 - 12:30:00' => '11:00 - 12:30',
        ];

        $scheduleMatrix = [];
        foreach ($timeslots as $timeKey => $timeLabel) {
            $scheduleMatrix[$timeLabel] = [
                'Senin' => null,
                'Selasa' => null,
                'Rabu' => null,
                'Kamis' => null,
                'Jumat' => null,
            ];
        }

        if ($kelasId) {
            $jadwalRaw = Jadwal::where('kelas_id', $kelasId)
                ->with('mataPelajaran')
                ->get();

            foreach ($jadwalRaw as $j) {
                $day = ucfirst(strtolower($j->hari)); // Senin, Selasa...
                $start = $j->jam_mulai;
                $end = $j->jam_selesai;

                foreach ($timeslots as $timeRange => $timeLabel) {
                    [$tMulai, $tSelesai] = explode(' - ', $timeRange);
                    if (strtotime($start) >= strtotime($tMulai) && strtotime($end) <= strtotime($tSelesai)) {
                        $scheduleMatrix[$timeLabel][$day] = $j;
                        break;
                    }
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
}
