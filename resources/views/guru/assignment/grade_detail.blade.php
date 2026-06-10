@extends('layouts.main')

@section('main')
@php
    $namaMapel  = $tugas->matapelajaran->nama ?? 'Tugas';
    $namaKelas  = $tugas->kelas->name ?? '-';
    $namaSiswa  = $siswa->user->name ?? 'Siswa';
    $nisnSiswa  = $siswa->nisn ?? '-';
    $isGraded   = $submission && $submission->status === 'dinilai';
    $hasAnswer  = $submission && ($submission->file_path || $submission->link || $submission->jawaban_teks);

    // Initials avatar
    $words    = explode(' ', $namaSiswa);
    $initials = '';
    foreach ($words as $w) { $initials .= strtoupper(substr($w, 0, 1)); if (strlen($initials) >= 2) break; }
@endphp

    <!-- Breadcrumb & Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box py-3">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0 font-12">
                        <li class="breadcrumb-item"><a href="{{ route('assignment.index') }}">Assignments</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('assignment.grade', $tugas->id) }}">Grading: {{ $tugas->title }}</a></li>
                        <li class="breadcrumb-item active">{{ $namaSiswa }}</li>
                    </ol>
                </div>
                <div class="d-flex align-items-center gap-2 mb-1 mt-2">
                    <span class="badge rounded px-2 py-1 font-10 fw-bold" style="background:rgba(91,109,240,0.12); color:#5b6df0;">{{ $namaMapel }}</span>
                    <span class="badge bg-light text-dark rounded px-2 py-1 font-10"><i class="mdi mdi-google-classroom me-1"></i>{{ $namaKelas }}</span>
                    @if($isGraded)
                        <span class="badge bg-success text-white rounded px-2 py-1 font-10"><i class="mdi mdi-check me-1"></i>Sudah Dinilai</span>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('assignment.grade', $tugas->id) }}" class="btn btn-sm btn-light border font-12 px-3">
                        <i class="mdi mdi-arrow-left me-1"></i> Kembali
                    </a>
                    <div>
                        <h2 class="text-dark fw-bold m-0 font-20">Detail Jawaban Siswa</h2>
                        <p class="text-muted font-13 mb-0">{{ $tugas->title }} &bull; Due {{ \Carbon\Carbon::parse($tugas->due_date)->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show font-13" role="alert">
            <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show font-13" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>
            <ul class="mb-0 ps-3 mt-1">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- LEFT: Student Submission -->
        <div class="col-lg-8">

            <!-- Student Identity Card -->
            <div class="card shadow-sm border border-light mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold font-18"
                             style="width: 52px; height: 52px; background: linear-gradient(135deg,#5b6df0,#3b4cb8); color:#fff; flex-shrink:0;">
                            {{ $initials }}
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark m-0">{{ $namaSiswa }}</h5>
                            <span class="text-muted font-12">NISN: {{ $nisnSiswa }}</span>
                        </div>
                        <div class="ms-auto text-end">
                            @if(!$submission)
                                <span class="badge bg-soft-danger text-danger font-11 px-3 py-1 rounded">Belum Mengumpulkan</span>
                            @elseif($submission->status === 'sudah_mengumpulkan')
                                <span class="badge bg-soft-warning text-warning font-11 px-3 py-1 rounded">Sudah Mengumpulkan</span>
                            @elseif($isGraded)
                                <span class="badge bg-success text-white font-11 px-3 py-1 rounded">Sudah Dinilai</span>
                            @endif
                            @if($submission)
                                <div class="text-muted font-11 mt-1">
                                    <i class="fe-clock me-1"></i> Dikumpul: {{ \Carbon\Carbon::parse($submission->created_at)->translatedFormat('d M Y, H:i') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if(!$hasAnswer)
                <!-- No submission -->
                <div class="card shadow-sm border border-light">
                    <div class="card-body text-center py-5">
                        <i class="mdi mdi-clipboard-text-off-outline font-48 text-muted d-block mb-3"></i>
                        <h5 class="text-muted fw-normal">Siswa belum mengumpulkan jawaban apapun.</h5>
                        <p class="text-muted font-13">Anda tetap dapat memberikan nilai manual di bawah.</p>
                    </div>
                </div>
            @else
                <!-- Jawaban Teks -->
                @if($submission->jawaban_teks)
                    <div class="card shadow-sm border border-light mb-3">
                        <div class="card-body">
                            <h5 class="header-title mb-3 d-flex align-items-center gap-2">
                                <i class="mdi mdi-text-box-check-outline font-18" style="color:#5b6df0;"></i>
                                Jawaban Teks
                            </h5>
                            <div class="p-3 rounded border bg-light font-14" style="line-height: 1.8; min-height: 120px;">
                                {!! $submission->jawaban_teks !!}
                            </div>
                        </div>
                    </div>
                @endif

                <!-- File & Link -->
                @if($submission->file_path || $submission->link)
                    <div class="card shadow-sm border border-light mb-3">
                        <div class="card-body">
                            <h5 class="header-title mb-3 d-flex align-items-center gap-2">
                                <i class="mdi mdi-paperclip font-18" style="color:#5b6df0;"></i>
                                Lampiran Jawaban
                            </h5>
                            <div class="d-flex flex-wrap gap-3">
                                @if($submission->file_path)
                                    <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank"
                                       class="btn btn-outline-primary font-13 px-4 py-2 rounded d-flex align-items-center gap-2">
                                        <i class="fe-download font-16"></i>
                                        Unduh File Jawaban
                                        <small class="text-muted ms-1">({{ strtoupper(pathinfo($submission->file_path, PATHINFO_EXTENSION)) }})</small>
                                    </a>
                                @endif
                                @if($submission->link)
                                    <a href="{{ $submission->link }}" target="_blank"
                                       class="btn btn-outline-info font-13 px-4 py-2 rounded d-flex align-items-center gap-2">
                                        <i class="fe-external-link font-16"></i>
                                        Buka Link Jawaban
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Catatan dari Siswa -->
                @if($submission->catatan)
                    <div class="card shadow-sm border border-light mb-3">
                        <div class="card-body">
                            <h5 class="header-title mb-2 d-flex align-items-center gap-2">
                                <i class="mdi mdi-comment-text-outline font-18 text-muted"></i>
                                Catatan dari Siswa
                            </h5>
                            <p class="text-muted font-14 mb-0 fst-italic">"{{ $submission->catatan }}"</p>
                        </div>
                    </div>
                @endif
            @endif

        </div>

        <!-- RIGHT: Grading Panel -->
        <div class="col-lg-4">
            <div style="position: sticky; top: 80px;">
                <div class="card shadow-sm border border-light">
                <div class="card-body">
                    <h4 class="header-title mb-4 d-flex align-items-center gap-2">
                        <i class="mdi mdi-star-circle-outline font-18" style="color:#5b6df0;"></i>
                        Panel Penilaian
                    </h4>

                    @if($isGraded)
                        <!-- Already graded display -->
                        <div class="text-center py-3 mb-3 rounded" style="background: linear-gradient(135deg, rgba(91,109,240,0.08), rgba(59,76,184,0.05));">
                            <div class="font-12 text-muted fw-semibold text-uppercase mb-1" style="letter-spacing:0.5px;">Nilai Saat Ini</div>
                            <div class="fw-bold" style="font-size: 3rem; color: {{ $submission->nilai >= ($tugas->kkm ?? 75) ? '#2e7d32' : '#c62828' }};">
                                {{ $submission->nilai }}
                            </div>
                            <div class="text-muted font-13">dari {{ $tugas->max_score }} &bull; KKM {{ $tugas->kkm ?? 75 }}</div>
                            @if($submission->nilai >= ($tugas->kkm ?? 75))
                                <span class="badge bg-soft-success text-success mt-2 px-3 py-1 font-11">✓ Tuntas</span>
                            @else
                                <span class="badge bg-soft-danger text-danger mt-2 px-3 py-1 font-11">✗ Belum Tuntas</span>
                            @endif
                        </div>

                        @if($submission->feedback_guru)
                            <div class="mb-3">
                                <label class="text-muted font-12 fw-semibold d-block mb-1">Feedback Guru</label>
                                <div class="p-2 bg-light rounded border font-13 text-dark">{{ $submission->feedback_guru }}</div>
                            </div>
                        @endif

                        <div class="text-muted font-12 mb-3 text-center">
                            <i class="fe-lock me-1"></i> Nilai sudah dirilis. Edit di bawah untuk memperbarui.
                        </div>
                    @endif

                    <!-- Grading Form -->
                    <form action="{{ route('assignment.grade.detail.store', [$tugas->id, $siswa->id]) }}" method="POST">
                        @csrf

                        <!-- Nilai Input -->
                        <div class="mb-3">
                            <label for="nilai" class="form-label fw-semibold text-dark font-14">
                                Nilai
                                <small class="text-muted fw-normal">(Maks: {{ $tugas->max_score }}, KKM: {{ $tugas->kkm ?? 75 }})</small>
                            </label>
                            <div class="input-group">
                                <input type="number" id="nilai" name="nilai"
                                       class="form-control form-control-lg text-center fw-bold font-20 @error('nilai') is-invalid @enderror"
                                       value="{{ old('nilai', $submission->nilai ?? '') }}"
                                       min="0" max="{{ $tugas->max_score }}"
                                       placeholder="0"
                                       style="border-radius: 8px 0 0 8px; letter-spacing: 2px;"
                                       oninput="updateNilaiIndicator(this.value)">
                                <span class="input-group-text fw-semibold text-muted font-14" style="border-radius: 0 8px 8px 0;">
                                    / {{ $tugas->max_score }}
                                </span>
                            </div>
                            <!-- KKM Indicator bar -->
                            <div class="mt-2">
                                <div class="d-flex justify-content-between font-10 text-muted mb-1">
                                    <span>0</span>
                                    <span id="kkm-label" class="fw-semibold" style="color:#5b6df0;">KKM: {{ $tugas->kkm ?? 75 }}</span>
                                    <span>{{ $tugas->max_score }}</span>
                                </div>
                                <div class="progress" style="height: 6px; border-radius: 4px;">
                                    <div id="nilai-progress" class="progress-bar" role="progressbar"
                                         style="width: {{ $submission && $submission->nilai !== null ? round(($submission->nilai / $tugas->max_score) * 100) : 0 }}%; background: #5b6df0; transition: width 0.3s ease;"></div>
                                </div>
                                <div id="nilai-status" class="font-11 mt-1 fw-semibold text-center">
                                    @if($submission && $submission->nilai !== null)
                                        @if($submission->nilai >= ($tugas->kkm ?? 75))
                                            <span class="text-success">✓ Tuntas</span>
                                        @else
                                            <span class="text-danger">✗ Belum Tuntas</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @error('nilai')<div class="invalid-feedback font-12">{{ $message }}</div>@enderror
                        </div>

                        <!-- Feedback -->
                        <div class="mb-4">
                            <label for="feedback" class="form-label fw-semibold text-dark font-14">
                                Feedback / Komentar
                                <small class="text-muted fw-normal">(Opsional)</small>
                            </label>
                            <textarea id="feedback" name="feedback" class="form-control font-13" rows="4"
                                      placeholder="Berikan komentar atau catatan untuk siswa ini..."
                                      style="resize: vertical; border-radius: 8px;">{{ old('feedback', $submission->feedback_guru ?? '') }}</textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" name="action" value="release"
                                    class="btn btn-primary py-2 font-14 fw-semibold"
                                    style="background: linear-gradient(135deg,#5b6df0,#3b4cb8); border: none; border-radius: 8px;">
                                <i class="mdi mdi-send me-1"></i>
                                {{ $isGraded ? 'Perbarui & Rilis Nilai' : 'Rilis Nilai ke Siswa' }}
                            </button>
                            <button type="submit" name="action" value="save_draft"
                                    class="btn btn-outline-secondary py-2 font-13"
                                    style="border-radius: 8px;">
                                <i class="mdi mdi-content-save-outline me-1"></i> Simpan Draft
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Assignment Info Card -->
            <div class="card shadow-sm border border-light mt-3">
                <div class="card-body p-3">
                    <h6 class="fw-semibold text-dark mb-3 font-13">Info Tugas</h6>
                    <div class="d-flex flex-column gap-2 font-13 text-muted">
                        <div class="d-flex justify-content-between">
                            <span><i class="fe-book me-2"></i>Mapel</span>
                            <span class="text-dark fw-semibold">{{ $namaMapel }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="mdi mdi-google-classroom me-2"></i>Kelas</span>
                            <span class="text-dark fw-semibold">{{ $namaKelas }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="fe-star me-2"></i>Max Score</span>
                            <span class="text-dark fw-semibold">{{ $tugas->max_score }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="mdi mdi-target me-2"></i>KKM</span>
                            <span class="text-dark fw-semibold">{{ $tugas->kkm ?? 75 }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="fe-calendar me-2"></i>Deadline</span>
                            <span class="text-dark fw-semibold font-12">{{ \Carbon\Carbon::parse($tugas->due_date)->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            </div> <!-- End Sticky Wrapper -->
        </div>
    </div>

    <script>
        const maxScore  = {{ $tugas->max_score }};
        const kkm       = {{ $tugas->kkm ?? 75 }};

        function updateNilaiIndicator(val) {
            const num = parseInt(val) || 0;
            const pct = Math.min(Math.round((num / maxScore) * 100), 100);
            const bar = document.getElementById('nilai-progress');
            const status = document.getElementById('nilai-status');

            bar.style.width = pct + '%';
            if (num >= kkm) {
                bar.style.background = '#2e7d32';
                status.innerHTML = '<span class="text-success">✓ Tuntas</span>';
            } else if (val === '' || val === null) {
                bar.style.background = '#5b6df0';
                status.innerHTML = '';
            } else {
                bar.style.background = '#c62828';
                status.innerHTML = '<span class="text-danger">✗ Belum Tuntas</span>';
            }
        }
    </script>
@endsection
