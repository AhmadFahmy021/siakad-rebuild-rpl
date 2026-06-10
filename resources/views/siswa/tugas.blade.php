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
            <h4 class="header-title mb-3">Daftar Tugas Aktif</h4>
            
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
                <div class="row">
                    @foreach($tugasList as $tugas)
                        @php
                            $iconClass = 'mdi mdi-book-open-page-variant';
                            $bannerGradient = 'linear-gradient(135deg, #5b6df0 0%, #3b4cb8 100%)';
                            $textColor = 'text-blue';

                            $mapelName = $tugas->matapelajaran->nama ?? null;
                            $mapelLower = strtolower($mapelName ?? '');

                            if (str_contains($mapelLower, 'matematika') || str_contains($mapelLower, 'math')) {
                                $iconClass = 'mdi mdi-calculator';
                                $bannerGradient = 'linear-gradient(135deg, #3f51b5 0%, #1a237e 100%)';
                                $textColor = 'text-primary';
                            } elseif (str_contains($mapelLower, 'kimia') || str_contains($mapelLower, 'fisika') || str_contains($mapelLower, 'biologi') || str_contains($mapelLower, 'ipa')) {
                                $iconClass = 'mdi mdi-flask-outline';
                                $bannerGradient = 'linear-gradient(135deg, #00b4db 0%, #0083b0 100%)';
                                $textColor = 'text-info';
                            } elseif (str_contains($mapelLower, 'bahasa') || str_contains($mapelLower, 'sastra') || str_contains($mapelLower, 'indonesia') || str_contains($mapelLower, 'inggris')) {
                                $iconClass = 'mdi mdi-book-open-variant';
                                $bannerGradient = 'linear-gradient(135deg, #d32f2f 0%, #5d001e 100%)';
                                $textColor = 'text-danger';
                            } elseif (str_contains($mapelLower, 'sejarah') || str_contains($mapelLower, 'geografi') || str_contains($mapelLower, 'ips')) {
                                $iconClass = 'mdi mdi-earth';
                                $bannerGradient = 'linear-gradient(135deg, #ef6c00 0%, #e65100 100%)';
                                $textColor = 'text-warning';
                            } elseif (str_contains($mapelLower, 'komputer') || str_contains($mapelLower, 'informatika') || str_contains($mapelLower, 'tik') || str_contains($mapelLower, 'rpl')) {
                                $iconClass = 'mdi mdi-laptop';
                                $bannerGradient = 'linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%)';
                                $textColor = 'text-success';
                            }

                            $submission = $tugas->pengumpulanTugas->first();
                            $dueDate = \Carbon\Carbon::parse($tugas->due_date);
                            $isLate = $dueDate->isPast();
                            $remaining = $isLate ? 'Sudah Lewat Deadline' : $dueDate->diffForHumans(null, true) . ' Lagi';

                            $namaKelas = $tugas->kelas->name ?? '-';
                            $namaMapel = $tugas->matapelajaran->nama ?? 'Tugas Belajar';
                            $namaGuru = $tugas->guru->user->name ?? '-';
                        @endphp
                        
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card overflow-hidden shadow-sm h-100 border border-light">
                                
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
                                    <h4 class="mt-0 fw-bold mb-1 font-15">
                                        <a href="{{ route('siswa.tugas.index', ['id' => $tugas->id]) }}" class="text-dark hover-primary">{{ $tugas->title }}</a>
                                    </h4>

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
                                        <small class="{{ $isLate ? 'text-danger' : 'text-warning' }} fw-semibold">
                                            {{ $remaining }}
                                        </small>
                                    </div>
                                </div>

                                <!-- Card Footer Actions -->
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

                                    <div>
                                        @if($submission && $submission->status === 'dinilai')
                                            <a href="{{ route('siswa.tugas.index', ['id' => $tugas->id]) }}" class="btn btn-link p-0 fw-semibold text-blue font-13 text-decoration-none">
                                                Lihat Nilai <i class="mdi mdi-chevron-right"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('siswa.tugas.index', ['id' => $tugas->id]) }}" class="btn btn-link p-0 fw-semibold font-13 text-decoration-none" style="color: #5b6df0 !important;">
                                                Buka Tugas <i class="mdi mdi-chevron-right"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>
            @endif
        </div>
    </div>

    <!-- Custom style to clamp text overflow -->
    <style>
        .hover-primary:hover {
            color: #5b6df0 !important;
        }
        .text-overflow-3 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
    </style>
@endsection
