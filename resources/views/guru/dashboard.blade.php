@extends('layouts.main')

@section('main')

<!-- ═══════════════════════════════════════════════════
     PAGE TITLE
════════════════════════════════════════════════════ -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                Dashboard Guru
            </h4>
            <div class="page-title-right">
                <small class="text-muted">
                    <i class="mdi mdi-calendar me-1"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </small>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════
     GREETING
════════════════════════════════════════════════════ -->
<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-primary d-flex align-items-center gap-2 py-2">
            <i class="mdi mdi-hand-wave fs-4"></i>
            <span>
                Selamat datang, <strong>{{ Auth::user()->name }}</strong>!
                Berikut ringkasan aktivitas mengajar kamu hari ini.
            </span>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════
     KARTU STATISTIK
     Data dikirim dari GuruDashboardController
════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4">

    {{-- Total Kelas --}}
    <div class="col-xl-3 col-md-6">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <i class="mdi mdi-google-classroom widget-icon bg-primary-subtle text-primary"></i>
                </div>
                <h6 class="text-muted fw-normal mt-0 mb-3">Total Kelas</h6>
                <h3 class="mt-3 mb-3 fw-bold">{{ $totalKelas ?? 0 }}</h3>
                <p class="mb-0 text-muted">
                    <span class="text-primary me-1">
                        <i class="mdi mdi-school"></i>
                    </span>
                    Kelas yang kamu ampu
                </p>
            </div>
        </div>
    </div>

    {{-- Total Tugas --}}
    <div class="col-xl-3 col-md-6">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <i class="mdi mdi-clipboard-text widget-icon bg-success-subtle text-success"></i>
                </div>
                <h6 class="text-muted fw-normal mt-0 mb-3">Total Tugas</h6>
                <h3 class="mt-3 mb-3 fw-bold">{{ $totalTugas ?? 0 }}</h3>
                <p class="mb-0 text-muted">
                    <span class="text-success me-1">
                        <i class="mdi mdi-check-circle"></i>
                    </span>
                    Tugas yang sudah dibuat
                </p>
            </div>
        </div>
    </div>

    {{-- Belum Dikoreksi --}}
    <div class="col-xl-3 col-md-6">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <i class="mdi mdi-pencil-box-multiple widget-icon bg-warning-subtle text-warning"></i>
                </div>
                <h6 class="text-muted fw-normal mt-0 mb-3">Belum Dikoreksi</h6>
                <h3 class="mt-3 mb-3 fw-bold">{{ $belumDikoreksi ?? 0 }}</h3>
                <p class="mb-0 text-muted">
                    <span class="text-warning me-1">
                        <i class="mdi mdi-alert-circle"></i>
                    </span>
                    Pengumpulan menunggu nilai
                </p>
            </div>
        </div>
    </div>

    {{-- Notifikasi Belum Dibaca --}}
    <div class="col-xl-3 col-md-6">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <i class="mdi mdi-bell widget-icon bg-danger-subtle text-danger"></i>
                </div>
                <h6 class="text-muted fw-normal mt-0 mb-3">Notifikasi</h6>
                <h3 class="mt-3 mb-3 fw-bold">{{ $totalNotifikasi ?? 0 }}</h3>
                <p class="mb-0 text-muted">
                    <span class="text-danger me-1">
                        <i class="mdi mdi-bell-ring"></i>
                    </span>
                    Notifikasi belum dibaca
                </p>
            </div>
        </div>
    </div>

</div>

<!-- ═══════════════════════════════════════════════════
     BARIS 2: TUGAS TERBARU + NOTIFIKASI
════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4">

    {{-- Tugas Terbaru --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-clipboard-list me-1 text-primary"></i>
                    Tugas Terbaru
                </h5>
                {{-- Link ke halaman manajemen tugas --}}
                <a href="{{ route('guru.tugas.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Judul Tugas</th>
                                <th>Kelas</th>
                                <th>Deadline</th>
                                <th class="text-center">Terkumpul</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tugasTerbaru ?? [] as $tugas)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ $tugas->judul }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $tugas->kelas->nama_kelas ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($tugas->deadline)->format('d/m/Y') }}
                                </td>
                                <td class="text-center">
                                    {{ $tugas->pengumpulans_count ?? 0 }} siswa
                                </td>
                                <td class="text-center">
                                    @if(\Carbon\Carbon::parse($tugas->deadline)->isPast())
                                        <span class="badge bg-danger">Berakhir</span>
                                    @else
                                        <span class="badge bg-success">Aktif</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="mdi mdi-clipboard-off fs-3 d-block mb-1"></i>
                                    Belum ada tugas dibuat.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Notifikasi Terbaru --}}
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-bell me-1 text-warning"></i>
                    Notifikasi
                </h5>
                <a href="{{ route('guru.notifikasi.index') }}" class="btn btn-sm btn-outline-warning">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($notifikasiTerbaru ?? [] as $notif)
                    <div class="list-group-item px-3 py-2
                        {{ !$notif->is_read ? 'list-group-item-warning' : '' }}">
                        <div class="d-flex gap-2 align-items-start">
                            {{-- Icon berdasarkan type notifikasi --}}
                            @if($notif->type === 'belum_dikoreksi')
                                <i class="mdi mdi-alert-circle text-danger mt-1 fs-5"></i>
                            @else
                                <i class="mdi mdi-file-document text-primary mt-1 fs-5"></i>
                            @endif
                            <div>
                                <p class="mb-0 fw-semibold small">{{ $notif->judul }}</p>
                                <p class="mb-0 text-muted" style="font-size:11px">
                                    {{ Str::limit($notif->pesan, 60) }}
                                </p>
                                <small class="text-muted">
                                    {{ $notif->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="mdi mdi-bell-off fs-3 d-block mb-1"></i>
                        <small>Tidak ada notifikasi.</small>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ═══════════════════════════════════════════════════
     BARIS 3: ABSENSI HARI INI + SHORTCUT MENU
════════════════════════════════════════════════════ -->
<div class="row g-3">

    {{-- Absensi Hari Ini --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-clipboard2-check me-1 text-success"></i>
                    Absensi Hari Ini
                    <small class="text-muted fw-normal ms-1">
                        ({{ now()->format('d/m/Y') }})
                    </small>
                </h5>
                <a href="{{ route('guru.absensi.index') }}" class="btn btn-sm btn-outline-success">
                    Kelola Absensi
                </a>
            </div>
            <div class="card-body">
                @forelse($absensiHariIni ?? [] as $kelas => $rekap)
                <div class="mb-3">
                    <p class="fw-semibold mb-2">
                        <i class="mdi mdi-google-classroom me-1"></i>{{ $kelas }}
                    </p>
                    <div class="row g-2 text-center">
                        <div class="col-3">
                            <div class="p-2 rounded bg-success-subtle">
                                <h5 class="mb-0 text-success fw-bold">{{ $rekap['hadir'] }}</h5>
                                <small class="text-muted">Hadir</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2 rounded bg-warning-subtle">
                                <h5 class="mb-0 text-warning fw-bold">{{ $rekap['izin'] }}</h5>
                                <small class="text-muted">Izin</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2 rounded bg-info-subtle">
                                <h5 class="mb-0 text-info fw-bold">{{ $rekap['sakit'] }}</h5>
                                <small class="text-muted">Sakit</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2 rounded bg-danger-subtle">
                                <h5 class="mb-0 text-danger fw-bold">{{ $rekap['alpha'] }}</h5>
                                <small class="text-muted">Alpha</small>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3">
                    <i class="mdi mdi-clipboard-off fs-3 d-block mb-1"></i>
                    Belum ada absensi hari ini.
                    <div class="mt-2">
                        <a href="{{ route('guru.absensi.create') }}"
                           class="btn btn-sm btn-success">
                            <i class="mdi mdi-plus me-1"></i> Isi Absensi Sekarang
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Shortcut Menu --}}
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-lightning-bolt me-1 text-info"></i>
                    Menu Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-2">

                    <div class="col-6">
                        <a href="{{ route('guru.tugas.index') }}"
                           class="btn btn-outline-primary w-100 py-3">
                            <i class="mdi mdi-clipboard-text fs-3 d-block mb-1"></i>
                            <small>Tugas</small>
                        </a>
                    </div>

                    <div class="col-6">
                        <a href="{{ route('guru.absensi.index') }}"
                           class="btn btn-outline-success w-100 py-3">
                            <i class="mdi mdi-clipboard2-check fs-3 d-block mb-1"></i>
                            <small>Absensi</small>
                        </a>
                    </div>

                    <div class="col-6">
                        <a href="{{ route('guru.statistik.index') }}"
                           class="btn btn-outline-info w-100 py-3">
                            <i class="mdi mdi-chart-bar fs-3 d-block mb-1"></i>
                            <small>Statistik</small>
                        </a>
                    </div>

                    <div class="col-6">
                        <a href="{{ route('guru.notifikasi.index') }}"
                           class="btn btn-outline-warning w-100 py-3">
                            <i class="mdi mdi-bell fs-3 d-block mb-1"></i>
                            <small>Notifikasi</small>
                            @if(($totalNotifikasi ?? 0) > 0)
                                <span class="badge bg-danger ms-1">
                                    {{ $totalNotifikasi }}
                                </span>
                            @endif
                        </a>
                    </div>

                    <div class="col-6">
                        <a href="{{ route('guru.profil.index') }}"
                           class="btn btn-outline-secondary w-100 py-3">
                            <i class="mdi mdi-account-circle fs-3 d-block mb-1"></i>
                            <small>Profil</small>
                        </a>
                    </div>

                    <div class="col-6">
                        <a href="{{ route('guru.export.excel', ['tugasId' => 'latest']) }}"
                           class="btn btn-outline-danger w-100 py-3">
                            <i class="mdi mdi-file-export fs-3 d-block mb-1"></i>
                            <small>Export Nilai</small>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

@endsection


@section('js')
{{-- Tidak perlu Chart.js di dashboard — statistik ada di halaman statistik --}}
{{-- SweetAlert otomatis dari @include('sweetalert::alert') di layout --}}
@endsection
