@extends('layouts.main')
@section('main')
    @php
        $teacherName = Auth::user()->name;
    @endphp

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Jadwal Mengajar</h4>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="card widget-rounded-circle">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                <i class="fe-calendar font-22 avatar-title text-primary"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $totalJadwal }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Total Sesi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $hariMap = [
                0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa',
                3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu',
            ];
            $hariIni = $hariMap[\Carbon\Carbon::now()->dayOfWeek] ?? 'Senin';

            $jadwalHariIni = 0;
            foreach ($scheduleMatrix as $timeLabel => $days) {
                if (isset($days[$hariIni]) && $days[$hariIni] !== null) {
                    $jadwalHariIni++;
                }
            }

            // Unique kelas count
            $allKelas = collect();
            foreach ($scheduleMatrix as $timeLabel => $days) {
                foreach ($days as $day => $sched) {
                    if ($sched && $sched->kelas) {
                        $allKelas->push($sched->kelas->id);
                    }
                }
            }
            $uniqueKelas = $allKelas->unique()->count();

            // Unique mata pelajaran count
            $allMapel = collect();
            foreach ($scheduleMatrix as $timeLabel => $days) {
                foreach ($days as $day => $sched) {
                    if ($sched && $sched->matapelajaran) {
                        $allMapel->push($sched->matapelajaran->id);
                    }
                }
            }
            $uniqueMapel = $allMapel->unique()->count();
        @endphp

        <div class="col-md-4 col-xl-3">
            <div class="card widget-rounded-circle">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                <i class="fe-clock font-22 avatar-title text-success"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $jadwalHariIni }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Sesi Hari Ini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card widget-rounded-circle">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                <i class="fe-layers font-22 avatar-title text-info"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $uniqueKelas }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Kelas Diajar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card widget-rounded-circle">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                <i class="fe-book-open font-22 avatar-title text-warning"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $uniqueMapel }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Mata Pelajaran</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Schedule Matrix -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="header-title">
                            <i class="mdi mdi-calendar-week me-1 text-primary"></i>
                            Jadwal Mingguan — {{ $teacherName }}
                        </h4>
                        <span class="badge bg-soft-primary text-primary px-2 py-1">
                            {{ $hariIni }}, {{ \Carbon\Carbon::now()->format('d M Y') }}
                        </span>
                    </div>

                    @if(empty($scheduleMatrix))
                        <div class="text-center py-5">
                            <div class="avatar-lg bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-calendar-blank-outline font-28 text-muted"></i>
                            </div>
                            <h5 class="text-muted fw-normal">Belum ada jadwal mengajar yang tersedia.</h5>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered align-middle mb-0" style="border-color: #e5e8eb !important;">
                                <thead class="table-light">
                                    <tr class="text-uppercase text-muted font-11 text-center" style="letter-spacing: 0.5px;">
                                        <th style="width: 14%">Waktu</th>
                                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $day)
                                            <th class="{{ $day === $hariIni ? 'bg-soft-primary' : '' }}">
                                                {{ $day }}
                                                @if($day === $hariIni)
                                                    <span class="badge bg-primary ms-1" style="font-size: 9px;">Hari Ini</span>
                                                @endif
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $times = array_keys($scheduleMatrix);
                                    @endphp

                                    @forelse($times as $timeSlot)
                                        <tr>
                                            <td class="text-center fw-semibold text-dark font-13">{{ $timeSlot }}</td>

                                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $day)
                                                @php
                                                    $sched = $scheduleMatrix[$timeSlot][$day] ?? null;
                                                    $isToday = ($day === $hariIni);

                                                    $subjName = $sched->matapelajaran->nama ?? '';
                                                    $kelasName = $sched->kelas->name ?? '';

                                                    // Dynamic left border color based on subject
                                                    $borderColor = '#e2e8f0';
                                                    $bgTheme = 'transparent';

                                                    if ($subjName) {
                                                        $nameLower = strtolower($subjName);
                                                        if (str_contains($nameLower, 'matematika')) {
                                                            $borderColor = '#3f51b5';
                                                            $bgTheme = 'rgba(63, 81, 181, 0.05)';
                                                        } elseif (str_contains($nameLower, 'indonesia')) {
                                                            $borderColor = '#2e7d32';
                                                            $bgTheme = 'rgba(46, 125, 50, 0.05)';
                                                        } elseif (str_contains($nameLower, 'biologi') || str_contains($nameLower, 'ipa') || str_contains($nameLower, 'kimia') || str_contains($nameLower, 'fisika')) {
                                                            $borderColor = '#e65100';
                                                            $bgTheme = 'rgba(230, 81, 0, 0.05)';
                                                        } elseif (str_contains($nameLower, 'inggris')) {
                                                            $borderColor = '#00838f';
                                                            $bgTheme = 'rgba(0, 131, 143, 0.05)';
                                                        } elseif (str_contains($nameLower, 'agama')) {
                                                            $borderColor = '#c2185b';
                                                            $bgTheme = 'rgba(194, 24, 91, 0.05)';
                                                        } elseif (str_contains($nameLower, 'pjok') || str_contains($nameLower, 'olahraga')) {
                                                            $borderColor = '#00796b';
                                                            $bgTheme = 'rgba(0, 121, 107, 0.05)';
                                                        } elseif (str_contains($nameLower, 'seni') || str_contains($nameLower, 'budaya')) {
                                                            $borderColor = '#6a1b9a';
                                                            $bgTheme = 'rgba(106, 27, 154, 0.05)';
                                                        } elseif (str_contains($nameLower, 'ips') || str_contains($nameLower, 'sosial')) {
                                                            $borderColor = '#0288d1';
                                                            $bgTheme = 'rgba(2, 136, 209, 0.05)';
                                                        } else {
                                                            $borderColor = '#6C5CE7';
                                                            $bgTheme = 'rgba(108, 92, 231, 0.05)';
                                                        }
                                                    }
                                                @endphp

                                                @if($sched)
                                                    <td class="p-2 {{ $isToday ? 'bg-soft-primary' : '' }}" style="background-color: {{ $bgTheme }}; border-left: 3px solid {{ $borderColor }} !important;">
                                                        <span class="d-block fw-bold text-dark font-13 mb-1">{{ $subjName }}</span>
                                                        <small class="text-muted font-11 d-block">
                                                            <i class="mdi mdi-google-classroom me-1"></i>{{ $kelasName }}
                                                        </small>
                                                    </td>
                                                @else
                                                    <td class="text-center text-muted font-12 p-2 {{ $isToday ? 'bg-soft-primary' : '' }}">
                                                        —
                                                    </td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4 font-13">
                                                <i class="mdi mdi-calendar-blank font-20 d-block mb-1 text-secondary"></i>
                                                Belum ada jadwal mengajar.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Legend -->
                        <div class="mt-3 pt-2 border-top">
                            <small class="text-muted fw-semibold me-3">
                                <i class="mdi mdi-circle text-primary font-8 me-1"></i> Hari Ini
                            </small>
                            <small class="text-muted me-3">
                                <span style="display:inline-block; width:12px; height:3px; background:#3f51b5; border-radius:1px; margin-right:4px; vertical-align:middle;"></span> Matematika
                            </small>
                            <small class="text-muted me-3">
                                <span style="display:inline-block; width:12px; height:3px; background:#2e7d32; border-radius:1px; margin-right:4px; vertical-align:middle;"></span> B. Indonesia
                            </small>
                            <small class="text-muted me-3">
                                <span style="display:inline-block; width:12px; height:3px; background:#e65100; border-radius:1px; margin-right:4px; vertical-align:middle;"></span> IPA
                            </small>
                            <small class="text-muted me-3">
                                <span style="display:inline-block; width:12px; height:3px; background:#00838f; border-radius:1px; margin-right:4px; vertical-align:middle;"></span> B. Inggris
                            </small>
                            <small class="text-muted me-3">
                                <span style="display:inline-block; width:12px; height:3px; background:#6C5CE7; border-radius:1px; margin-right:4px; vertical-align:middle;"></span> Lainnya
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
