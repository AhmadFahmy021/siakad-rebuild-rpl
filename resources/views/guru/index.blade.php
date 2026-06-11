@extends('layouts.main')
@section('main')
    @php
        $teacherName = Auth::user()->name;
        $totalKelas = $todaySchedule->pluck('kelas_id')->unique()->count();
    @endphp

    <style>
        /* ===== Welcome Banner ===== */
        .guru-welcome-banner {
            background: linear-gradient(135deg, #6C5CE7 0%, #7C6FF0 40%, #8B7FF8 100%);
            border-radius: 4px;
            padding: 32px 36px;
            color: #fff;
            position: relative;
            overflow: hidden;
            margin-bottom: 28px;
            box-shadow: 0 2px 8px rgba(108, 92, 231, 0.18);
        }
        .guru-welcome-banner::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }
        .guru-welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -60px;
            right: 80px;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }
        .guru-welcome-banner h2 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #fff;
        }
        .guru-welcome-banner p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 20px;
            max-width: 520px;
            line-height: 1.6;
        }
        .guru-welcome-banner .banner-illustration {
            position: absolute;
            right: 32px;
            bottom: 16px;
            width: 140px;
            height: 140px;
            opacity: 0.95;
            z-index: 1;
        }
        .guru-welcome-banner .btn-banner {
            padding: 8px 20px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .guru-welcome-banner .btn-banner-outline {
            background: rgba(255,255,255,0.2);
            border: 1.5px solid rgba(255,255,255,0.5);
            color: #fff;
            backdrop-filter: blur(4px);
        }
        .guru-welcome-banner .btn-banner-outline:hover {
            background: rgba(255,255,255,0.35);
            color: #fff;
            transform: translateY(-1px);
        }
        .guru-welcome-banner .btn-banner-solid {
            background: rgba(0,0,0,0.2);
            border: 1.5px solid rgba(0,0,0,0.15);
            color: #fff;
        }
        .guru-welcome-banner .btn-banner-solid:hover {
            background: rgba(0,0,0,0.35);
            color: #fff;
            transform: translateY(-1px);
        }

        /* ===== Section Cards ===== */
        .guru-section-card {
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            border: 1px solid #e8e8ef;
            padding: 0;
            margin-bottom: 28px;
        }
        .guru-section-card .section-header {
            padding: 20px 24px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f2f3f8;
        }
        .guru-section-card .section-header h4 {
            font-size: 16px;
            font-weight: 700;
            color: #2d3436;
            margin: 0;
        }
        .guru-section-card .section-header a {
            font-size: 13px;
            font-weight: 600;
            color: #6C5CE7;
            text-decoration: none;
        }
        .guru-section-card .section-header a:hover {
            color: #5a4bd1;
            text-decoration: underline;
        }
        .guru-section-card .section-body {
            padding: 16px 24px 24px;
        }

        /* ===== Schedule Table ===== */
        .guru-schedule-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .guru-schedule-table thead th {
            font-size: 11px;
            font-weight: 700;
            color: #a0a5b5;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 12px 16px;
            border-bottom: 2px solid #f2f3f8;
            background: transparent;
        }
        .guru-schedule-table tbody tr {
            transition: background 0.2s ease;
        }
        .guru-schedule-table tbody tr:hover {
            background: #fafbff;
        }
        .guru-schedule-table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f5f5fa;
            font-size: 14px;
            color: #2d3436;
        }
        .guru-schedule-table tbody tr:last-child td {
            border-bottom: none;
        }
        .schedule-time {
            font-weight: 700;
            font-size: 14px;
            color: #2d3436;
            white-space: nowrap;
        }
        .schedule-time.active-now {
            color: #6C5CE7;
        }
        .schedule-subject {
            font-weight: 600;
            font-size: 14px;
            color: #2d3436;
            margin-bottom: 2px;
        }
        .schedule-topic {
            font-size: 12px;
            color: #a0a5b5;
            margin: 0;
        }
        .schedule-kelas {
            font-weight: 600;
            font-size: 14px;
            color: #2d3436;
        }

        /* ===== Tugas Progress ===== */
        .tugas-progress-item {
            padding: 18px 0;
            border-bottom: 1px solid #f2f3f8;
        }
        .tugas-progress-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .tugas-progress-item:first-child {
            padding-top: 0;
        }
        .tugas-progress-title {
            font-weight: 700;
            font-size: 14px;
            color: #2d3436;
            margin-bottom: 2px;
        }
        .tugas-progress-meta {
            font-size: 12px;
            color: #a0a5b5;
            margin-bottom: 10px;
        }
        .tugas-progress-bar-wrap {
            position: relative;
            height: 8px;
            background: #f0f0f5;
            border-radius: 4px;
            overflow: hidden;
        }
        .tugas-progress-bar-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .tugas-progress-bar-fill.high {
            background: linear-gradient(90deg, #00b894, #00cec9);
        }
        .tugas-progress-bar-fill.medium {
            background: linear-gradient(90deg, #fdcb6e, #e17055);
        }
        .tugas-progress-bar-fill.low {
            background: linear-gradient(90deg, #e17055, #d63031);
        }
        .tugas-progress-count {
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }
        .tugas-progress-count.high {
            color: #00b894;
        }
        .tugas-progress-count.medium {
            color: #e17055;
        }
        .tugas-progress-count.low {
            color: #d63031;
        }

        /* ===== Empty State ===== */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }
        .empty-state i {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 12px;
            display: block;
        }
        .empty-state p {
            color: #a0a5b5;
            font-size: 14px;
            margin: 0;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .guru-welcome-banner {
                padding: 24px 20px;
            }
            .guru-welcome-banner h2 {
                font-size: 20px;
            }
            .guru-welcome-banner .banner-illustration {
                display: none;
            }
            .guru-schedule-table thead th,
            .guru-schedule-table tbody td {
                padding: 12px 10px;
                font-size: 13px;
            }
        }

        /* Spacing from topbar */
        .guru-dashboard-wrapper {
            padding-top: 12px;
        }
    </style>

    <!-- ===== Welcome Banner ===== -->
    <div class="guru-dashboard-wrapper"></div>
    <div class="row">
        <div class="col-12">
            <div class="guru-welcome-banner">
                <div class="position-relative" style="z-index: 2;">
                    <h2>Selamat Datang Kembali, {{ $teacherName }}!</h2>
                    <p>
                        Semangat mengajar hari ini. Anda memiliki {{ $totalSesiHariIni }} sesi kelas dan {{ $totalTugasPerluKoreksi }} tugas
                        perlu dikoreksi. Jangan lupa untuk memeriksa pengumuman kurikulum
                        terbaru.
                    </p>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('assignment.index') }}" class="btn btn-banner btn-banner-outline">
                            <i class="fe-calendar me-1"></i> Lihat Jadwal
                        </a>
                        <a href="{{ route('assignment.index') }}" class="btn btn-banner btn-banner-solid">
                            <i class="fe-edit me-1"></i> Input Nilai
                        </a>
                    </div>
                </div>

                <!-- Illustration SVG -->
                <div class="banner-illustration d-none d-md-block">
                    <svg viewBox="0 0 140 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Book/Teaching Illustration -->
                        <rect x="20" y="50" width="100" height="70" rx="8" fill="rgba(255,255,255,0.15)"/>
                        <rect x="30" y="60" width="80" height="50" rx="4" fill="rgba(255,255,255,0.1)"/>
                        <rect x="40" y="70" width="35" height="4" rx="2" fill="rgba(255,255,255,0.4)"/>
                        <rect x="40" y="80" width="50" height="3" rx="1.5" fill="rgba(255,255,255,0.25)"/>
                        <rect x="40" y="88" width="45" height="3" rx="1.5" fill="rgba(255,255,255,0.2)"/>
                        <rect x="40" y="96" width="30" height="3" rx="1.5" fill="rgba(255,255,255,0.15)"/>
                        <!-- Graduation cap -->
                        <polygon points="70,20 30,40 70,55 110,40" fill="rgba(255,255,255,0.3)"/>
                        <polygon points="70,55 90,47 90,60 70,68 50,60 50,47" fill="rgba(255,255,255,0.2)"/>
                        <line x1="105" y1="42" x2="105" y2="65" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
                        <circle cx="105" cy="67" r="3" fill="rgba(255,255,255,0.3)"/>
                        <!-- Star accents -->
                        <circle cx="18" cy="30" r="2" fill="rgba(255,255,255,0.35)"/>
                        <circle cx="125" cy="25" r="1.5" fill="rgba(255,255,255,0.25)"/>
                        <circle cx="15" cy="80" r="1.5" fill="rgba(255,255,255,0.2)"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Jadwal Mengajar Hari Ini ===== -->
    <div class="row">
        <div class="col-12">
            <div class="guru-section-card">
                <div class="section-header">
                    <h4>
                        <i class="mdi mdi-calendar-clock me-1" style="color: #6C5CE7;"></i>
                        Jadwal Mengajar Hari Ini
                    </h4>
                    <a href="{{ route('assignment.index') }}">Selengkapnya</a>
                </div>
                <div class="section-body">
                    @if($todaySchedule->isEmpty())
                        <div class="empty-state">
                            <i class="mdi mdi-calendar-blank-outline"></i>
                            <p>Tidak ada jadwal mengajar hari ini.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="guru-schedule-table">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">Waktu</th>
                                        <th style="width: 50%;">Mata Pelajaran</th>
                                        <th style="width: 30%;">Kelas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todaySchedule as $jadwal)
                                        @php
                                            $jamMulai = \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i');
                                            $jamSelesai = \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i');
                                            $now = \Carbon\Carbon::now();
                                            $isActiveNow = $now->between(
                                                \Carbon\Carbon::parse($jadwal->jam_mulai),
                                                \Carbon\Carbon::parse($jadwal->jam_selesai)
                                            );
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="schedule-time {{ $isActiveNow ? 'active-now' : '' }}">
                                                    {{ $jamMulai }} -<br>{{ $jamSelesai }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="schedule-subject">
                                                    {{ $jadwal->matapelajaran->nama ?? 'Mata Pelajaran' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="schedule-kelas">
                                                    {{ $jadwal->kelas->name ?? '-' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Progres Koreksi Tugas Aktif ===== -->
    <div class="row">
        <div class="col-12">
            <div class="guru-section-card">
                <div class="section-header">
                    <h4>
                        <i class="mdi mdi-clipboard-check-outline me-1" style="color: #6C5CE7;"></i>
                        Progres Koreksi Tugas Aktif
                    </h4>
                </div>
                <div class="section-body">
                    @if($activeTugas->isEmpty())
                        <div class="empty-state">
                            <i class="mdi mdi-clipboard-check-outline"></i>
                            <p>Belum ada tugas aktif yang perlu dikoreksi.</p>
                        </div>
                    @else
                        @foreach($activeTugas as $tugas)
                            @php
                                $totalSiswa = $tugas->total_siswa ?: 1;
                                $sudahKumpul = $tugas->sudah_kumpul ?? 0;
                                $percent = $totalSiswa > 0 ? round(($sudahKumpul / $totalSiswa) * 100) : 0;

                                $kelasName = $tugas->kelas->name ?? '-';

                                if ($percent >= 70) {
                                    $barClass = 'high';
                                } elseif ($percent >= 40) {
                                    $barClass = 'medium';
                                } else {
                                    $barClass = 'low';
                                }
                            @endphp
                            <div class="tugas-progress-item">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <div class="tugas-progress-title">
                                            {{ $tugas->title }} - {{ $kelasName }}
                                        </div>
                                        <div class="tugas-progress-meta">
                                            Batas Pengumpulan: {{ $tugas->batas_pengumpulan }}
                                        </div>
                                    </div>
                                    <span class="tugas-progress-count {{ $barClass }}">
                                        {{ $sudahKumpul }}/{{ $totalSiswa }} Siswa
                                    </span>
                                </div>
                                <div class="tugas-progress-bar-wrap">
                                    <div class="tugas-progress-bar-fill {{ $barClass }}" style="width: {{ $percent }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
