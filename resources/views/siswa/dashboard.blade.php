@extends('layouts.main')

@section('main')
    @php
        $studentName = Auth::user()->name;
        $className = $kelas->name ?? 'Belum Ditentukan';
    @endphp

    <!-- Welcome Message -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box py-3">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <h2 class="text-dark fw-bold m-0 font-24">Halo, {{ $studentName }}!</h2>
                    <span class="badge bg-soft-blue text-blue rounded-pill px-3 py-1 font-12 fw-bold" style="background-color: rgba(91, 109, 240, 0.15) !important; color: #5b6df0 !important;">Kelas {{ $className }}</span>
                </div>
                <h5 class="text-muted fw-normal mt-1 mb-0">Selamat datang kembali di dashboard akademik Anda.</h5>
            </div>
        </div>
    </div>

    <!-- Stats Summary Row -->
    <div class="row mt-2">
        <!-- Average Score Card -->
        <div class="col-md-4 mb-3">
            <div class="card widget-rounded-circle shadow-sm border border-light h-100">
                <div class="card-body p-3">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <span class="text-muted font-11 fw-bold text-uppercase d-block" style="letter-spacing: 0.5px;">RATA-RATA NILAI SEMESTER INI</span>
                            <h3 class="text-dark fw-bold m-0 mt-2 font-22">{{ $rataRataNilai > 0 ? number_format($rataRataNilai, 2) : '0.00' }}</h3>
                        </div>
                        <div class="col-auto">
                            <div class="avatar-md rounded-circle d-flex align-items-center justify-content-center" style="background-color: rgba(91, 109, 240, 0.15); width: 48px; height: 48px;">
                                <i class="mdi mdi-star font-22 text-blue" style="color: #5b6df0 !important;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Tasks Card -->
        <div class="col-md-4 mb-3">
            <div class="card widget-rounded-circle shadow-sm border border-light h-100">
                <div class="card-body p-3">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <span class="text-muted font-11 fw-bold text-uppercase d-block" style="letter-spacing: 0.5px;">TUGAS AKTIF</span>
                            <h3 class="text-dark fw-bold m-0 mt-2 font-22">{{ sprintf("%02d", $aktifTugas) }}</h3>
                            <small class="text-danger fw-semibold mt-1 d-block font-12">{{ $mendekatiDeadline }} mendekati deadline</small>
                        </div>
                        <div class="col-auto">
                            <div class="avatar-md rounded-circle d-flex align-items-center justify-content-center" style="background-color: rgba(241, 85, 108, 0.15); width: 48px; height: 48px;">
                                <i class="mdi mdi-alert-outline font-22 text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Tasks Card -->
        <div class="col-md-4 mb-3">
            <div class="card widget-rounded-circle shadow-sm border border-light h-100">
                <div class="card-body p-3">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <span class="text-muted font-11 fw-bold text-uppercase d-block" style="letter-spacing: 0.5px;">TOTAL TUGAS SELESAI</span>
                            <h3 class="text-dark fw-bold m-0 mt-2 font-22">{{ $selesaiTugas }} <span class="font-14 text-muted">/ {{ $totalTugas }}</span></h3>
                            
                            @php
                                $percent = $totalTugas > 0 ? ($selesaiTugas / $totalTugas * 100) : 0;
                            @endphp
                            <div class="progress mt-2 progress-sm" style="height: 5px; width: 80%;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%; background-color: #5b6df0;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="avatar-md rounded-circle d-flex align-items-center justify-content-center" style="background-color: rgba(57, 175, 209, 0.15); width: 48px; height: 48px;">
                                <i class="mdi mdi-school font-22 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inner Two Column Grid -->
    <div class="row mt-2">
        
        <!-- Left Column: Class Schedule -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border border-light h-100">
                <!-- Card Header -->
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex align-items-center gap-2">
                    <div class="avatar-xs bg-soft-blue rounded d-flex align-items-center justify-content-center" style="background-color: rgba(91, 109, 240, 0.12) !important;">
                        <i class="fe-calendar text-blue" style="color: #5b6df0 !important;"></i>
                    </div>
                    <div>
                        <h4 class="header-title text-dark fw-bold m-0 font-14">Jadwal Semester Ganjil</h4>
                        <small class="text-muted">Kelas {{ $className }}</small>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-centered align-middle mb-0" style="border-color: #e5e8eb !important;">
                            <thead class="table-light">
                                <tr class="text-uppercase text-muted font-11 tracking-wider text-center" style="letter-spacing: 0.5px;">
                                    <th style="width: 15%">Waktu</th>
                                    <th>Senin</th>
                                    <th>Selasa</th>
                                    <th>Rabu</th>
                                    <th>Kamis</th>
                                    <th>Jumat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $times = array_keys($scheduleMatrix);
                                @endphp

                                @forelse($times as $idx => $timeSlot)
                                    <!-- Render Class slots -->
                                    <tr>
                                        <!-- Time slot -->
                                        <td class="text-center font-13 fw-semibold text-dark">{{ $timeSlot }}</td>
                                        
                                        <!-- Days -->
                                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $day)
                                            @php
                                                $sched = $scheduleMatrix[$timeSlot][$day] ?? null;
                                                $subjName = $sched->matapelajaran->nama ?? '';
                                                
                                                // Dynamic left border color based on subject name
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
                                                    }
                                                }
                                            @endphp

                                            @if($sched)
                                                <td class="p-2" style="background-color: {{ $bgTheme }}; border-left: 3px solid {{ $borderColor }} !important;">
                                                    <span class="d-block fw-bold text-dark font-13 mb-0">{{ $subjName }}</span>
                                                    @if($sched->guru && $sched->guru->user)
                                                        <small class="text-muted font-11 d-block mt-1 text-truncate" title="{{ $sched->guru->user->name }}">
                                                            <i class="mdi mdi-account-outline me-1"></i> {{ $sched->guru->user->name }}
                                                        </small>
                                                    @else
                                                        <small class="text-muted font-11 d-block mt-1">
                                                            <i class="mdi mdi-map-marker-outline me-1"></i> R. -
                                                        </small>
                                                    @endif
                                                </td>
                                            @else
                                                <!-- Empty cell -->
                                                <td class="text-center text-muted font-12 p-2">
                                                    -
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>


                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4 font-13">
                                            <i class="mdi mdi-calendar-blank font-20 d-block mb-1 text-secondary"></i>
                                            Belum ada jadwal pelajaran untuk kelas ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Upcoming Tasks -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border border-light h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h4 class="header-title text-dark fw-bold m-0 font-14">
                            <i class="mdi mdi-clipboard-text-outline me-1 text-danger"></i> Tugas Mendatang
                        </h4>
                        <span class="badge bg-soft-danger text-danger rounded-pill px-2 py-0-5 font-11 fw-bold">
                            {{ $upcomingTasks->count() }}
                        </span>
                    </div>

                    @if($upcomingTasks->isEmpty())
                        <div class="text-center py-5">
                            <div class="avatar-lg bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                                <i class="fe-check-square font-24 text-muted"></i>
                            </div>
                            <h5 class="text-muted font-normal">Semua tugas Anda selesai!</h5>
                        </div>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($upcomingTasks as $t)
                                @php
                                    $subjTag = $t->matapelajaran ? $t->matapelajaran->nama : 'Tugas';
                                    $tagColor = 'bg-primary';

                                    $dueDate = \Carbon\Carbon::parse($t->due_date);
                                    $now = \Carbon\Carbon::now();
                                    
                                    if ($now->greaterThan($dueDate)) {
                                        $remDaysText = 'Terlewat';
                                    } else {
                                        $daysRemaining = intval($now->copy()->startOfDay()->diffInDays($dueDate->copy()->startOfDay(), false));
                                        if ($daysRemaining == 0) {
                                            $remDaysText = 'Hari Ini';
                                        } elseif ($daysRemaining == 1) {
                                            $remDaysText = 'Besok';
                                        } else {
                                            $remDaysText = $daysRemaining . ' Hari Lagi';
                                        }
                                    }
                                @endphp
                                
                                <div class="border rounded p-3 bg-light bg-opacity-25" style="border: 1px solid #eef2f7 !important;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge {{ $tagColor }} text-white font-10 px-2 py-0-5 rounded text-uppercase" style="font-size: 10px !important;">
                                            {{ $subjTag }}
                                        </span>
                                        <small class="text-danger fw-semibold font-11">
                                            {{ $remDaysText }}
                                        </small>
                                    </div>
                                    <h5 class="text-dark fw-bold font-13 mt-0 mb-2 text-overflow-3" style="line-height: 1.4;">
                                        {{ $t->title }}
                                    </h5>
                                    <p class="text-muted font-12 mb-3 text-overflow-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">
                                        {{ $t->description }}
                                    </p>

                                    <!-- Submit Action Button -->
                                    <div class="text-end">
                                        <a href="{{ route('siswa.tugas.index', ['id' => $t->id]) }}" class="btn btn-xs btn-danger font-11 rounded px-3 py-1 bg-gradient-danger" style="background-color: #f1556c; border-color: #f1556c; font-size: 11px;">
                                            Submit Tugas
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="text-center mt-3 pt-2 border-top">
                        <a href="{{ route('siswa.tugas.index') }}" class="text-blue font-13 fw-semibold">
                            Lihat Semua Tugas <i class="mdi mdi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
