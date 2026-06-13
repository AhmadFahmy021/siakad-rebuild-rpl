@extends('layouts.main')

@section('main')
    <!-- Page Title & Stats -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Tugas Saya</h4>
                <h5 class="text-muted fw-normal mt-1 mb-0">Kelola dan kumpulkan tugas belajar Anda di sini</h5>
            </div>
        </div>
    </div>

    @php
        $totalTugas = $tugasList->count();
        $selesai = $tugasList->filter(function($t) {
            $sub = $t->pengumpulanTugas->first();
            return $sub && ($sub->status === 'sudah_mengumpulkan' || $sub->status === 'dinilai');
        })->count();
        $belum = $totalTugas - $selesai;
    @endphp

    <!-- Dashboard Summary Stats Cards -->
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card widget-rounded-circle">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar-lg rounded-circle bg-soft-blue border-blue border" style="background-color: rgba(91, 109, 240, 0.15) !important;">
                                <i class="fe-book font-22 avatar-title text-blue" style="color: #5b6df0 !important;"></i>
                            </div>
                        </div>
                        <div class="col text-end">
                            <h3 class="text-dark mt-1">{{ $totalTugas }}</h3>
                            <p class="text-muted mb-0 text-truncate font-14">Total Tugas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card widget-rounded-circle">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar-lg rounded-circle bg-soft-danger border-danger border" style="background-color: rgba(241, 85, 108, 0.15) !important;">
                                <i class="fe-clock font-22 avatar-title text-danger"></i>
                            </div>
                        </div>
                        <div class="col text-end">
                            <h3 class="text-dark mt-1">{{ $belum }}</h3>
                            <p class="text-muted mb-0 text-truncate font-14">Belum Selesai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card widget-rounded-circle">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar-lg rounded-circle bg-soft-success border-success border" style="background-color: rgba(74, 193, 142, 0.15) !important;">
                                <i class="fe-check-circle font-22 avatar-title text-success"></i>
                            </div>
                        </div>
                        <div class="col text-end">
                            <h3 class="text-dark mt-1">{{ $selesai }}</h3>
                            <p class="text-muted mb-0 text-truncate font-14">Selesai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Assignments Section -->
    <div class="row mt-2">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                <h4 class="header-title m-0">Daftar Tugas Aktif</h4>
                @if(!$tugasList->isEmpty())
                    <div class="d-flex flex-column flex-sm-row gap-2 align-items-stretch align-items-sm-center">
                        <!-- Search -->
                        <div class="position-relative">
                            <i class="fe-search position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: #adb5bd;"></i>
                            <input type="text" id="searchTugas" class="form-control form-control-sm" placeholder="Cari tugas..." style="padding-left: 34px; min-width: 200px; border-color: #eef2f7;">
                        </div>
                        <!-- Filter Status -->
                        <select id="filterStatus" class="form-select form-select-sm" style="min-width: 160px; border-color: #eef2f7;">
                            <option value="all">Semua Status</option>
                            <option value="belum">Belum Selesai</option>
                            <option value="dikumpul">Sudah Dikumpul</option>
                            <option value="dinilai">Selesai / Dinilai</option>
                        </select>
                        <!-- Filter Mapel -->
                        <select id="filterMapel" class="form-select form-select-sm" style="min-width: 160px; border-color: #eef2f7;">
                            <option value="all">Semua Mata Pelajaran</option>
                            @php
                                $uniqueMapel = $tugasList->pluck('matapelajaran.nama')->unique()->filter()->sort();
                            @endphp
                            @foreach($uniqueMapel as $mapel)
                                <option value="{{ $mapel }}">{{ $mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
            
            @if($tugasList->isEmpty())
                <div class="card p-5 text-center">
                    <div class="py-4">
                        <div class="avatar-lg rounded-circle bg-soft-blue mx-auto mb-3 d-flex align-items-center justify-content-center">
                            <i class="fe-search font-28 text-blue"></i>
                        </div>
                        <h5 class="text-muted font-normal">Tidak ada tugas aktif untuk kelas Anda saat ini.</h5>
                    </div>
                </div>
            @else
                <!-- No results message (hidden by default) -->
                <div id="noResults" class="card p-5 text-center d-none">
                    <div class="py-4">
                        <div class="avatar-lg rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="background-color: rgba(241, 85, 108, 0.12);">
                            <i class="fe-search font-28" style="color: #f1556c;"></i>
                        </div>
                        <h5 class="text-muted fw-normal">Tidak ada tugas yang cocok dengan filter Anda.</h5>
                        <button class="btn btn-sm btn-outline-primary mt-2" onclick="resetFilters()">Reset Filter</button>
                    </div>
                </div>

                <div class="row" id="tugasGrid">
                    @foreach($tugasList as $tugas)
                        @php
                            // Pool warna & ikon yang di-assign otomatis berdasarkan ID mapel
                            $colorPool = [
                                ['gradient' => 'linear-gradient(135deg, #5b6df0 0%, #3b4cb8 100%)', 'text' => 'text-primary', 'icon' => 'mdi mdi-book-open-page-variant'],
                                ['gradient' => 'linear-gradient(135deg, #00b4db 0%, #0083b0 100%)', 'text' => 'text-info', 'icon' => 'mdi mdi-flask-outline'],
                                ['gradient' => 'linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%)', 'text' => 'text-success', 'icon' => 'mdi mdi-laptop'],
                                ['gradient' => 'linear-gradient(135deg, #d32f2f 0%, #5d001e 100%)', 'text' => 'text-danger', 'icon' => 'mdi mdi-book-open-variant'],
                                ['gradient' => 'linear-gradient(135deg, #ef6c00 0%, #e65100 100%)', 'text' => 'text-warning', 'icon' => 'mdi mdi-earth'],
                                ['gradient' => 'linear-gradient(135deg, #3f51b5 0%, #1a237e 100%)', 'text' => 'text-primary', 'icon' => 'mdi mdi-calculator'],
                                ['gradient' => 'linear-gradient(135deg, #6559cc 0%, #4a3fb5 100%)', 'text' => 'text-primary', 'icon' => 'mdi mdi-palette'],
                                ['gradient' => 'linear-gradient(135deg, #00838f 0%, #004d40 100%)', 'text' => 'text-info', 'icon' => 'mdi mdi-run'],
                                ['gradient' => 'linear-gradient(135deg, #ad1457 0%, #6a1b9a 100%)', 'text' => 'text-danger', 'icon' => 'mdi mdi-music'],
                                ['gradient' => 'linear-gradient(135deg, #f57c00 0%, #e64a19 100%)', 'text' => 'text-warning', 'icon' => 'mdi mdi-cog'],
                            ];

                            $mapelId = $tugas->matapelajaran->id ?? 0;
                            $poolIndex = abs(crc32((string) $mapelId)) % count($colorPool);
                            $chosen = $colorPool[$poolIndex];

                            $iconClass = $chosen['icon'];
                            $bannerGradient = $chosen['gradient'];
                            $textColor = $chosen['text'];

                            $submission = $tugas->pengumpulanTugas->first();
                            $dueDate = \Carbon\Carbon::parse($tugas->due_date);
                            $isLate = $dueDate->isPast();

                            $namaKelas = $tugas->kelas->name ?? '-';
                            $namaMapel = $tugas->matapelajaran->nama ?? 'Tugas Belajar';
                            $namaGuru = $tugas->guru->user->name ?? '-';
                            
                            // Determine status for filter
                            $statusKey = 'belum';
                            if ($submission && $submission->status === 'dinilai') {
                                $statusKey = 'dinilai';
                            } elseif ($submission && $submission->status === 'sudah_mengumpulkan') {
                                $statusKey = 'dikumpul';
                            }

                            $isSelesai = $statusKey !== 'belum';
                            
                            if ($isSelesai) {
                                $remaining = 'Tugas Selesai';
                            } else {
                                $remaining = $isLate ? 'Sudah Lewat Deadline' : $dueDate->diffForHumans(null, true) . ' Lagi';
                            }
                        @endphp
                        
                        <div class="col-md-6 col-lg-4 mb-4 tugas-item" data-title="{{ strtolower($tugas->title) }}" data-mapel="{{ $namaMapel }}" data-status="{{ $statusKey }}">
                            <a href="{{ route('siswa.tugas.index', ['id' => $tugas->id]) }}" class="text-decoration-none d-block h-100">
                                <div class="card tugas-card overflow-hidden shadow-sm h-100 border border-light">
                                    
                                    <!-- Card Header Banner -->
                                    <div class="position-relative p-3" style="background: {{$bannerGradient}}; height: 110px;">
                                        <!-- Subject Tag -->
                                        <span class="badge bg-white bg-opacity-25 text-white rounded px-2 py-1 font-11 fw-bold text-uppercase">
                                            {{ $namaMapel }}
                                        </span>
                                        <!-- Kelas Badge top-right -->
                                        <span class="position-absolute top-0 end-0 m-2 badge rounded px-2 py-1 font-10 fw-semibold" style="background: rgba(255,255,255,0.92); color: #3a3f5c;">
                                            <i class="mdi mdi-google-classroom me-1"></i>{{ $namaKelas }}
                                        </span>
                                        <!-- Floating Icon -->
                                        <div class="position-absolute end-0 bottom-0 translate-middle-x bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; margin-bottom: -22px; z-index: 2; border: 2px solid #fff;">
                                            <i class="{{ $iconClass }} font-20 {{ $textColor }}"></i>
                                        </div>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="card-body pt-4 pb-2">
                                        <h4 class="mt-0 fw-bold mb-1 font-15 text-dark">{{ $tugas->title }}</h4>

                                        <!-- Meta info: guru & kelas -->
                                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                            <span class="text-muted font-12 d-flex align-items-center gap-1">
                                                <i class="fe-user font-13 text-muted"></i> {{ $namaGuru }}
                                            </span>
                                            <span class="text-muted font-12">•</span>
                                            <span class="badge rounded-pill px-2 py-1 font-10 fw-semibold" style="background: rgba(91,109,240,0.1); color: #5b6df0;">
                                                <i class="mdi mdi-google-classroom me-1"></i>{{ $namaKelas }}
                                            </span>
                                        </div>

                                        <p class="text-muted font-13 mb-3 text-overflow-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 38px;">
                                            {{ $tugas->description }}
                                        </p>
                                        
                                        <hr class="my-2 bg-light">

                                        <!-- Due date & remaining -->
                                        <div class="d-flex align-items-center justify-content-between font-13 py-1">
                                            <span class="text-muted">
                                                <i class="mdi mdi-calendar-clock-outline me-1"></i> {{ $dueDate->translatedFormat('d M Y, H:i') }}
                                            </span>
                                            <small class="{{ $isSelesai ? 'text-success' : ($isLate ? 'text-danger' : 'text-warning') }} fw-semibold">
                                                {{ $remaining }}
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Card Footer -->
                                    <div class="card-footer bg-white border-top-0 d-flex align-items-center justify-content-between pt-1 pb-3">
                                        <div>
                                            @if(!$submission)
                                                <span class="badge bg-soft-danger text-danger rounded px-2 py-1 font-11"><i class="mdi mdi-circle-medium"></i> Belum Selesai</span>
                                            @elseif($submission->status === 'sudah_mengumpulkan')
                                                <span class="badge bg-soft-warning text-warning rounded px-2 py-1 font-11"><i class="mdi mdi-circle-medium"></i> Dikumpul</span>
                                            @elseif($submission->status === 'dinilai')
                                                <span class="badge bg-soft-success text-success rounded px-2 py-1 font-11"><i class="fe-check me-1"></i> Selesai</span>
                                            @endif
                                        </div>

                                        <span class="fw-semibold font-13" style="color: #5b6df0;">
                                            @if($submission && $submission->status === 'dinilai')
                                                Lihat Nilai <i class="mdi mdi-chevron-right"></i>
                                            @else
                                                Buka Tugas <i class="mdi mdi-chevron-right"></i>
                                            @endif
                                        </span>
                                    </div>

                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>
            @endif
        </div>
    </div>

    <!-- Custom style -->
    <style>
        .tugas-card {
            cursor: pointer;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .tugas-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(91, 109, 240, 0.18) !important;
            border-color: rgba(91, 109, 240, 0.3) !important;
        }
        .tugas-card:active {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(91, 109, 240, 0.12) !important;
        }
        .text-overflow-3 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
        .tugas-item {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .tugas-item.hidden {
            display: none !important;
        }
        #searchTugas:focus, #filterStatus:focus, #filterMapel:focus {
            border-color: #5b6df0 !important;
            box-shadow: 0 0 0 0.15rem rgba(91, 109, 240, 0.15);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchTugas');
            const filterStatus = document.getElementById('filterStatus');
            const filterMapel = document.getElementById('filterMapel');
            const tugasItems = document.querySelectorAll('.tugas-item');
            const noResults = document.getElementById('noResults');
            const tugasGrid = document.getElementById('tugasGrid');

            if (!searchInput) return;

            function applyFilters() {
                const query = searchInput.value.toLowerCase().trim();
                const status = filterStatus.value;
                const mapel = filterMapel.value;
                let visibleCount = 0;

                tugasItems.forEach(function(item) {
                    const title = item.getAttribute('data-title') || '';
                    const itemMapel = item.getAttribute('data-mapel') || '';
                    const itemStatus = item.getAttribute('data-status') || '';

                    const matchSearch = !query || title.includes(query);
                    const matchStatus = status === 'all' || itemStatus === status;
                    const matchMapel = mapel === 'all' || itemMapel === mapel;

                    if (matchSearch && matchStatus && matchMapel) {
                        item.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        item.classList.add('hidden');
                    }
                });

                if (noResults) {
                    noResults.classList.toggle('d-none', visibleCount > 0);
                }
                if (tugasGrid) {
                    tugasGrid.classList.toggle('d-none', visibleCount === 0);
                }
            }

            searchInput.addEventListener('input', applyFilters);
            filterStatus.addEventListener('change', applyFilters);
            filterMapel.addEventListener('change', applyFilters);

            window.resetFilters = function() {
                searchInput.value = '';
                filterStatus.value = 'all';
                filterMapel.value = 'all';
                applyFilters();
            };
        });
    </script>
@endsection
