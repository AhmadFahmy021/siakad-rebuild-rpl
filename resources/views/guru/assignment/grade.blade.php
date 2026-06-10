@extends('layouts.main')

@section('main')
    <!-- Breadcrumbs & Header Actions -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box py-3">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0 font-12">
                        <li class="breadcrumb-item"><a href="{{ route('assignment.index') }}">Assignments</a></li>
                        <li class="breadcrumb-item active">Grading</li>
                    </ol>
                </div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-soft-blue text-blue rounded px-2 py-0-5 font-10 fw-bold" style="background-color: rgba(91, 109, 240, 0.12) !important; color: #5b6df0 !important;">ASSIGNMENT {{ $tugas->matapelajaran->nama ?? 'Pelajaran' }}</span>
                    <span class="text-muted font-12">&bull; Due {{ \Carbon\Carbon::parse($tugas->due_date)->translatedFormat('d M Y, H:i') }}</span>
                </div>
                <h2 class="text-dark fw-bold m-0 font-22">Grading: {{ $tugas->title }}</h2>
                <p class="text-muted font-13 mb-0">Grade submissions for Kelas {{ $tugas->kelas->name ?? '-' }} ({{ $tugas->matapelajaran->nama ?? '-' }}).</p>
            </div>
        </div>
    </div>

    <!-- Grading Header Cards -->
        
        <!-- Grading Header Cards & Actions -->
        <div class="row mt-2">
            <!-- Stats Columns -->
            <div class="col-12">
                <div class="row">
                    <!-- Turned In -->
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card shadow-sm border border-light h-100 mb-0">
                            <div class="card-body p-2-5 d-flex align-items-center gap-2">
                                <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center bg-soft-blue" style="width: 38px; height: 38px; background-color: rgba(91, 109, 240, 0.1) !important;">
                                    <i class="mdi mdi-inbox-arrow-down font-18 text-blue" style="color: #5b6df0 !important;"></i>
                                </div>
                                <div>
                                    <span class="text-muted font-10 fw-bold text-uppercase d-block" style="letter-spacing: 0.5px;">TURNED IN</span>
                                    <h4 class="text-dark fw-bold m-0 mt-0-5 font-16">{{ $totalTurnedIn }} <span class="font-11 text-muted">/ {{ $totalStudents }}</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Graded -->
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card shadow-sm border border-light h-100 mb-0">
                            <div class="card-body p-2-5 d-flex align-items-center gap-2">
                                <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center bg-soft-success" style="width: 38px; height: 38px; background-color: rgba(74, 193, 142, 0.1) !important;">
                                    <i class="mdi mdi-checkbox-marked-circle-outline font-18 text-success"></i>
                                </div>
                                <div>
                                    <span class="text-muted font-10 fw-bold text-uppercase d-block" style="letter-spacing: 0.5px;">GRADED</span>
                                    <h4 class="text-dark fw-bold m-0 mt-0-5 font-16">{{ $gradedCount }} <span class="font-11 text-muted">/ {{ $totalTurnedIn }}</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Late -->
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card shadow-sm border border-light h-100 mb-0">
                            <div class="card-body p-2-5 d-flex align-items-center gap-2">
                                <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center bg-soft-warning" style="width: 38px; height: 38px; background-color: rgba(241, 180, 76, 0.1) !important;">
                                    <i class="mdi mdi-clock-alert-outline font-18 text-warning"></i>
                                </div>
                                <div>
                                    <span class="text-muted font-10 fw-bold text-uppercase d-block" style="letter-spacing: 0.5px;">LATE</span>
                                    <h4 class="text-dark fw-bold m-0 mt-0-5 font-16">{{ $lateCount }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Avg Score -->
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card shadow-sm border border-light h-100 mb-0">
                            <div class="card-body p-2-5 d-flex align-items-center gap-2">
                                <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center bg-soft-info" style="width: 38px; height: 38px; background-color: rgba(0, 131, 143, 0.1) !important;">
                                    <i class="mdi mdi-chart-line font-18 text-info"></i>
                                </div>
                                <div>
                                    <span class="text-muted font-10 fw-bold text-uppercase d-block" style="letter-spacing: 0.5px;">AVG SCORE</span>
                                    <h4 class="text-dark fw-bold m-0 mt-0-5 font-16">{{ $avgScore }} <span class="font-11 text-muted">/ {{ $tugas->max_score }}</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    <!-- Student Submissions Card -->
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show font-13" role="alert">
                    <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <div class="card shadow-sm border border-light">
                <div class="card-body">
                    <!-- Table Title & Tab filters -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3 pb-2 border-bottom">
                        <h4 class="header-title text-dark fw-bold m-0 font-15">Submission List</h4>
                        <div class="d-flex gap-2">
                            <div class="btn-group btn-group-sm rounded-pill" style="border: 1px solid #e2e8f0; padding: 2px; background-color: #f8f9fa;">
                                <a href="{{ route('assignment.grade', ['tugas' => $tugas->id, 'tab' => 'all']) }}" class="btn rounded-pill font-11 px-3 {{ $tab === 'all' ? 'btn-white shadow-sm text-dark fw-bold' : 'btn-light text-muted border-0 bg-transparent' }}">All</a>
                                <a href="{{ route('assignment.grade', ['tugas' => $tugas->id, 'tab' => 'ungraded']) }}" class="btn rounded-pill font-11 px-3 {{ $tab === 'ungraded' ? 'btn-white shadow-sm text-dark fw-bold' : 'btn-light text-muted border-0 bg-transparent' }}">Ungraded</a>
                                <a href="{{ route('assignment.grade', ['tugas' => $tugas->id, 'tab' => 'missing']) }}" class="btn rounded-pill font-11 px-3 {{ $tab === 'missing' ? 'btn-white shadow-sm text-dark fw-bold' : 'btn-light text-muted border-0 bg-transparent' }}">Missing</a>
                            </div>
                        </div>
                    </div>

                    <!-- Submissions Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-centered align-middle mb-0 font-13" style="border-color: #e5e8eb !important;">
                            <thead class="table-light">
                                <tr class="text-uppercase text-muted font-10 tracking-wider" style="letter-spacing: 0.5px;">
                                    <th style="width: 5%" class="text-center">No</th>
                                    <th style="width: 32%">Student Name</th>
                                    <th style="width: 15%" class="text-center">Status</th>
                                    <th style="width: 18%">Jawaban</th>
                                    <th style="width: 12%" class="text-center">Nilai</th>
                                    <th style="width: 18%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @forelse($studentsGradingList as $record)
                                    @php
                                        $statusClass = 'bg-soft-secondary text-secondary';
                                        if ($record->status === 'SUBMITTED')     $statusClass = 'bg-soft-success text-success';
                                        elseif ($record->status === 'GRADED')    $statusClass = 'bg-success text-white';
                                        elseif ($record->status === 'LATE')      $statusClass = 'bg-soft-warning text-warning';
                                        elseif ($record->status === 'MISSING')   $statusClass = 'bg-soft-danger text-danger';
                                        elseif ($record->status === 'NOT STARTED') $statusClass = 'bg-soft-secondary text-muted';

                                        $words = explode(" ", $record->name);
                                        $initials = "";
                                        foreach ($words as $w) {
                                            $initials .= strtoupper(substr($w, 0, 1));
                                            if(strlen($initials) >= 2) break;
                                        }

                                        $hasAnswer = $record->file || $record->link || isset($record->jawaban_teks) && $record->jawaban_teks;
                                    @endphp
                                    <tr>
                                        <!-- NO -->
                                        <td class="text-center text-muted font-12">{{ sprintf("%02d", $no++) }}</td>

                                        <!-- STUDENT NAME (clickable) -->
                                        <td>
                                            <a href="{{ route('assignment.grade.detail', [$tugas->id, $record->siswa_id]) }}" class="d-flex align-items-center gap-2 text-decoration-none hover-primary">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold font-12" style="width: 34px; height: 34px; background-color: rgba(91,109,240,0.12); color: #5b6df0; flex-shrink:0;">
                                                    {{ $initials }}
                                                </div>
                                                <div>
                                                    <span class="d-block fw-semibold text-dark font-13 mb-0">{{ $record->name }}</span>
                                                    <small class="text-muted font-11">NISN: {{ $record->nisn }}</small>
                                                </div>
                                            </a>
                                        </td>

                                        <!-- STATUS -->
                                        <td class="text-center">
                                            <span class="badge {{ $statusClass }} font-10 px-2 py-1 rounded">
                                                {{ $record->status }}
                                            </span>
                                        </td>

                                        <!-- JAWABAN INDICATOR -->
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                @if($record->file)
                                                    <span class="font-11 text-success d-flex align-items-center gap-1">
                                                        <i class="fe-file-text"></i> File
                                                    </span>
                                                @endif
                                                @if($record->link)
                                                    <span class="font-11 text-info d-flex align-items-center gap-1">
                                                        <i class="fe-link"></i> Link
                                                    </span>
                                                @endif
                                                @if(isset($record->jawaban_teks) && $record->jawaban_teks)
                                                    <span class="font-11 text-primary d-flex align-items-center gap-1">
                                                        <i class="mdi mdi-text-box-outline"></i> Teks
                                                    </span>
                                                @endif
                                                @if(!$hasAnswer)
                                                    <span class="text-muted font-11">—</span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- NILAI -->
                                        <td class="text-center">
                                            @if($record->nilai !== null)
                                                <span class="fw-bold font-15 {{ $record->nilai >= ($tugas->kkm ?? 75) ? 'text-success' : 'text-danger' }}">
                                                    {{ $record->nilai }}
                                                </span>
                                                <span class="text-muted font-11">/{{ $tugas->max_score }}</span>
                                            @else
                                                <span class="text-muted font-12">—</span>
                                            @endif
                                        </td>

                                        <!-- AKSI -->
                                        <td class="text-center">
                                            <a href="{{ route('assignment.grade.detail', [$tugas->id, $record->siswa_id]) }}"
                                               class="btn btn-sm font-12 px-3 py-1 rounded"
                                               style="{{ $record->status === 'GRADED' ? 'background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7;' : 'background:rgba(91,109,240,0.1); color:#5b6df0; border:1px solid #c5caf8;' }}">
                                                <i class="mdi mdi-{{ $record->status === 'GRADED' ? 'eye' : 'pencil' }} me-1"></i>
                                                {{ $record->status === 'GRADED' ? 'Lihat Nilai' : 'Beri Nilai' }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="fe-user font-28 d-block mb-2 text-secondary"></i>
                                            Tidak ada data siswa ditemukan untuk kelas ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <style>
        .hover-primary:hover {
            color: #5b6df0 !important;
        }
        .btn-white {
            background-color: #fff;
            border-color: #e2e8f0;
            color: #1a202c;
        }
        .btn-white:hover {
            background-color: #f7fafc;
        }
        .text-overflow-3 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection
