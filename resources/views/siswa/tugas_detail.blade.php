@extends('layouts.main')

@section('main')
    @php
        $mapelName = $tugas->matapelajaran->nama ?? null;
        $mapelLower = strtolower($mapelName ?? '');
        $iconClass = 'mdi mdi-book-open-page-variant';
        $bannerGradient = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
        $bannerColor = 'blue';
        $avatarBg = 'bg-soft-blue';
        $textColor = 'text-blue';

        if (str_contains($mapelLower, 'matematika') || str_contains($mapelLower, 'math')) {
            $iconClass = 'mdi mdi-calculator';
            $bannerGradient = 'linear-gradient(135deg, #2b5876 0%, #4e4376 100%)';
            $bannerColor = 'blue';
            $avatarBg = 'bg-soft-blue';
            $textColor = 'text-blue';
        } elseif (str_contains($mapelLower, 'kimia') || str_contains($mapelLower, 'fisika') || str_contains($mapelLower, 'biologi') || str_contains($mapelLower, 'ipa')) {
            $iconClass = 'mdi mdi-flask-outline';
            $bannerGradient = 'linear-gradient(135deg, #2b9348 0%, #80b918 100%)';
            $bannerColor = 'info';
            $avatarBg = 'bg-soft-info';
            $textColor = 'text-info';
        } elseif (str_contains($mapelLower, 'bahasa') || str_contains($mapelLower, 'sastra') || str_contains($mapelLower, 'indonesia') || str_contains($mapelLower, 'inggris')) {
            $iconClass = 'mdi mdi-book-open-variant';
            $bannerGradient = 'linear-gradient(135deg, #a71d31 0%, #3f0d12 100%)';
            $bannerColor = 'danger';
            $avatarBg = 'bg-soft-danger';
            $textColor = 'text-danger';
        } elseif (str_contains($mapelLower, 'komputer') || str_contains($mapelLower, 'informatika') || str_contains($mapelLower, 'tik') || str_contains($mapelLower, 'rpl')) {
            $iconClass = 'mdi mdi-laptop';
            $bannerGradient = 'linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%)';
            $bannerColor = 'success';
            $avatarBg = 'bg-soft-success';
            $textColor = 'text-success';
        }

        $teacherName = $tugas->guru->user->name ?? ($tugas->kelas->guru->user->name ?? 'Guru');
        $namaKelas   = $tugas->kelas->name ?? '-';
        $namaMapel   = $tugas->matapelajaran->nama ?? 'Tugas Belajar';
        $submission  = $tugas->pengumpulanTugas->first();
        $dueDate     = \Carbon\Carbon::parse($tugas->due_date);
        $isLate      = $dueDate->isPast();
        $remaining   = $isLate ? 'Sudah Lewat Deadline' : $dueDate->diffForHumans(null, true) . ' Lagi';
    @endphp

    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box py-3">
                <div class="page-title-left">
                    <ol class="breadcrumb m-0 font-14">
                        <li class="breadcrumb-item"><a href="{{ route('siswa.tugas.index') }}" class="text-blue fw-medium"><i class="mdi mdi-arrow-left me-1"></i> Daftar Tugas</a></li>
                        <li class="breadcrumb-item active text-muted">Detail Tugas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-block-helper me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-block-helper me-2"></i> Harap periksa inputan Anda:
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>

    <!-- Detail Two Column Layout -->
    <div class="row">
        <!-- Left Main Column (Details & Form) -->
        <div class="col-lg-8">
            <!-- Assignment Detail Card -->
            <div class="card">
                <div class="card-body">
                    <!-- Badges -->
                    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                        @if(!$submission)
                            <span class="badge bg-danger rounded px-2 py-1 font-11">Belum Selesai</span>
                        @elseif($submission->status === 'sudah_mengumpulkan')
                            <span class="badge bg-warning text-dark rounded px-2 py-1 font-11">Sudah Mengumpulkan</span>
                        @elseif($submission->status === 'dinilai')
                            <span class="badge bg-success rounded px-2 py-1 font-11">Selesai</span>
                        @endif
                        <span class="badge rounded px-2 py-1 font-11 fw-semibold {{ $textColor }}" style="background:rgba(91,109,240,0.1);">{{ $namaMapel }}</span>
                        <span class="badge bg-light text-dark rounded px-2 py-1 font-11">
                            <i class="mdi mdi-google-classroom me-1"></i>{{ $namaKelas }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h3 class="mt-0 mb-3 fw-bold text-dark">{{ $tugas->title }}</h3>

                    <!-- Instructor & Deadline -->
                    <div class="d-flex flex-wrap gap-4 text-muted font-14 mb-4">
                        <span class="d-flex align-items-center">
                            <i class="fe-user me-2 font-16"></i> {{ $teacherName }}
                        </span>
                        <span class="d-flex align-items-center">
                            <i class="mdi mdi-google-classroom me-2 font-16"></i> Kelas {{ $namaKelas }}
                        </span>
                        <span class="d-flex align-items-center">
                            <i class="fe-calendar me-2 font-16"></i> Deadline: {{ $dueDate->translatedFormat('d M Y, H:i') }} WIB
                        </span>
                    </div>

                    <hr class="my-4">

                    <!-- Description -->
                    <h5 class="fw-semibold text-dark mb-2">Deskripsi Tugas</h5>
                    <p class="text-muted font-15 sp-line-2" style="white-space: pre-line;">
                        {{ $tugas->description }}
                    </p>

                    @if($tugas->file_path || $tugas->link)
                        <div class="mt-4 p-3 bg-light rounded d-flex flex-wrap gap-2 align-items-center justify-content-between">
                            <span class="text-dark fw-medium font-14"><i class="mdi mdi-attachment me-1 text-primary"></i> Lampiran Soal dari Guru:</span>
                            <div>
                                @if($tugas->file_path)
                                    <a href="{{ asset('storage/' . $tugas->file_path) }}" target="_blank" class="btn btn-sm btn-blue rounded-pill">
                                        <i class="fe-download me-1"></i> Unduh Soal
                                    </a>
                                @endif
                                @if($tugas->link)
                                    <a href="{{ $tugas->link }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill ms-1">
                                        <i class="fe-link me-1"></i> Link Referensi
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Submission Box Card -->
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-4">Pengumpulan Tugas</h4>

                    @if($submission && $submission->status === 'dinilai')
                        <div class="alert alert-soft-success p-3 rounded d-flex align-items-center">
                            <i class="fe-lock font-24 me-3 text-success"></i>
                            <div>
                                <h5 class="text-success fw-bold m-0">Tugas Terkunci</h5>
                                <p class="text-muted mb-0 font-13">Tugas ini sudah dinilai oleh guru Anda sehingga pengumpulan tidak dapat diubah kembali.</p>
                            </div>
                        </div>

                        {{-- Show submitted text answer if exists --}}
                        @if($submission->jawaban_teks)
                            <div class="mt-3">
                                <h6 class="fw-semibold text-dark mb-2"><i class="mdi mdi-text-box-outline me-1 text-blue"></i> Jawaban Teks Anda</h6>
                                <div class="p-3 bg-light rounded border font-14" style="white-space: pre-wrap; line-height: 1.7;">
                                    {!! $submission->jawaban_teks !!}
                                </div>
                            </div>
                        @endif
                    @else
                        <form action="{{ route('siswa.tugas.submit', $tugas->id) }}" method="POST" enctype="multipart/form-data" id="submissionForm">
                            @csrf

                            <!-- Rich Text Editor (Quill.js) Section -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark font-14">
                                    <i class="mdi mdi-pencil-box-outline me-1 text-primary"></i>
                                    Tulis Jawaban Langsung
                                    <small class="text-muted fw-normal">(Opsional jika sudah upload file)</small>
                                </label>
                                <div id="quill-editor" style="min-height: 220px; border-radius: 0 0 6px 6px; font-size: 14px;">@if($submission && $submission->jawaban_teks){!! $submission->jawaban_teks !!}@endif</div>
                                <input type="hidden" name="jawaban_teks" id="jawaban_teks_input">
                            </div>

                            <!-- Divider OR -->
                            <div class="d-flex align-items-center my-3">
                                <hr class="flex-grow-1">
                                <span class="mx-3 text-muted font-12 fw-semibold">ATAU LAMPIRKAN FILE / LINK</span>
                                <hr class="flex-grow-1">
                            </div>

                            <!-- Dashed Dropzone -->
                            <div class="dropzone-wrapper border border-dashed border-2 border-primary rounded p-4 text-center cursor-pointer bg-light bg-opacity-50" id="dropzoneClickable" style="border-color: #5b6df0 !important;">
                                <div class="dropzone-desc">
                                    <div class="avatar-md mx-auto mb-3 bg-soft-blue rounded d-flex align-items-center justify-content-center" style="background-color: rgba(91, 109, 240, 0.15) !important;">
                                        <i class="mdi mdi-cloud-upload font-28 text-blue" style="color: #5b6df0 !important;"></i>
                                    </div>
                                    <h5 class="fw-semibold text-dark font-16">Klik atau seret file ke sini</h5>
                                    <p class="text-muted font-13 mb-0">PDF, ZIP, Image, Docx (Maksimal 10MB)</p>
                                </div>
                                <input type="file" name="file_jawaban" class="d-none" id="file_jawaban_input">
                            </div>

                            <!-- File Selected Info -->
                            <div id="file_selection_info" class="mt-2 alert alert-soft-success py-2 px-3 rounded font-13 d-none">
                                <i class="fe-file-text me-1"></i> File terpilih: <span id="selected_file_name" class="fw-semibold"></span>
                            </div>

                            @if($submission && $submission->file_path)
                                <div class="mt-2 p-2 bg-soft-blue rounded d-flex align-items-center justify-content-between font-13">
                                    <span class="text-muted">
                                        <i class="fe-check-circle text-success me-1"></i> File Anda saat ini:
                                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="fw-semibold text-blue">Lihat Jawaban</a>
                                    </span>
                                    <small class="text-muted">Akan ditimpa jika mengunggah file baru</small>
                                </div>
                            @endif

                            <!-- Link Input -->
                            <div class="mb-3 mt-4">
                                <label for="link" class="form-label fw-semibold text-dark font-14">Link Alternatif <small class="text-muted">(Google Drive, GitHub, dll.)</small></label>
                                <input type="url" id="link" name="link" class="form-control" placeholder="https://..." value="{{ $submission ? $submission->link : old('link') }}">
                            </div>

                            <!-- Comment Input -->
                            <div class="mb-4">
                                <label for="catatan" class="form-label fw-semibold text-dark font-14">Catatan Tambahan untuk Guru <small class="text-muted">(Opsional)</small></label>
                                <textarea id="catatan" name="catatan" class="form-control" rows="3" placeholder="Tulis pesan/catatan mengenai pengerjaan tugas Anda...">{{ $submission ? $submission->catatan : old('catatan') }}</textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4 py-2" style="background-color: #5b6df0; border-color: #5b6df0;">
                                    <i class="mdi mdi-send me-1"></i> Kirim Tugas
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column Sidebar -->
        <div class="col-lg-4">

            <!-- Grade Result Card (shown when graded) -->
            @if($submission && $submission->status === 'dinilai')
                <div class="card border-0 mb-3 overflow-hidden" style="background: linear-gradient(135deg, #5b6df0 0%, #3b4cb8 100%);">
                    <div class="card-body p-4 text-white text-center">
                        <div class="font-12 fw-semibold text-uppercase mb-2" style="letter-spacing: 1px; opacity: 0.85;">Nilai Anda</div>
                        <div class="fw-bold mb-1" style="font-size: 3.5rem; line-height: 1;">{{ $submission->nilai }}</div>
                        <div class="font-14 mb-3" style="opacity: 0.75;">dari {{ $tugas->max_score ?? 100 }}</div>
                        @php $kkm = $tugas->kkm ?? 75; @endphp
                        @if($submission->nilai >= $kkm)
                            <span class="badge px-3 py-1 font-12 fw-semibold rounded-pill" style="background: rgba(255,255,255,0.25);">
                                <i class="mdi mdi-check-circle me-1"></i> Tuntas (KKM {{ $kkm }})
                            </span>
                        @else
                            <span class="badge px-3 py-1 font-12 fw-semibold rounded-pill" style="background: rgba(255,100,100,0.4);">
                                <i class="mdi mdi-close-circle me-1"></i> Belum Tuntas (KKM {{ $kkm }})
                            </span>
                        @endif
                        <!-- Score bar -->
                        <div class="mt-3">
                            <div class="progress" style="height: 6px; background: rgba(255,255,255,0.2); border-radius: 4px;">
                                <div class="progress-bar" style="width: {{ min(round(($submission->nilai / ($tugas->max_score ?? 100)) * 100), 100) }}%; background: rgba(255,255,255,0.85); border-radius: 4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feedback Guru Card -->
                <div class="card shadow-sm border border-light mb-3">
                    <div class="card-body">
                        <h5 class="header-title mb-3 d-flex align-items-center gap-2 font-14">
                            <span class="d-flex align-items-center justify-content-center rounded-circle" style="width:28px;height:28px;background:rgba(91,109,240,0.12);">
                                <i class="mdi mdi-comment-quote-outline font-15" style="color:#5b6df0;"></i>
                            </span>
                            Feedback dari Guru
                        </h5>

                        @if($submission->feedback_guru)
                            <div class="p-3 rounded font-14 text-dark" style="background: #f8f9ff; border-left: 3px solid #5b6df0; line-height: 1.7;">
                                "{{ $submission->feedback_guru }}"
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <img src="{{ asset('assets/images/users/user-1.jpg') }}" alt="guru" class="rounded-circle" style="width:22px;height:22px;object-fit:cover;">
                                <small class="text-muted font-11">{{ $teacherName }} &bull; {{ $namaMapel }}</small>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="mdi mdi-message-off-outline font-28 text-muted d-block mb-2"></i>
                                <p class="text-muted font-13 mb-0">Belum ada feedback dari guru.</p>
                            </div>
                        @endif
                    </div>
                </div>

            @else
                <!-- Submission Status Card (before graded) -->
                <div class="card shadow-sm border border-light mb-3">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Status Pengumpulan</h4>

                        <div class="mb-3">
                            <label class="text-muted font-13 mb-1">Status</label>
                            <div>
                                @if(!$submission)
                                    <h4 class="text-danger fw-bold m-0 font-18">Belum Mengumpulkan</h4>
                                @elseif($submission->status === 'sudah_mengumpulkan')
                                    <h4 class="text-warning fw-bold m-0 font-18">Menunggu Penilaian</h4>
                                    <p class="text-muted font-12 mb-0 mt-1"><i class="mdi mdi-clock-outline me-1"></i>Tugas sudah dikumpulkan, menunggu guru menilai.</p>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted font-13 mb-1">Nilai</label>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold font-18 text-muted"
                                     style="width:52px;height:52px;background:#f4f4f8;border:2px dashed #dce0f5;">
                                    —
                                </div>
                                <span class="text-muted font-13">Belum dinilai</span>
                            </div>
                        </div>

                        <div>
                            <label class="text-muted font-13 mb-1">Sisa Waktu</label>
                            <div>
                                <h5 class="{{ $isLate ? 'text-danger' : 'text-blue' }} fw-bold m-0 font-15">{{ $remaining }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Instructor Card -->
            <div class="card shadow-sm border border-light">
                <div class="card-body">
                    <h4 class="header-title text-start mb-3">Pengajar</h4>
                    <div class="d-flex align-items-center p-3 bg-light bg-opacity-50 rounded">
                        <div class="rounded-circle overflow-hidden flex-shrink-0" style="width: 46px; height: 46px;">
                            <img src="{{ asset('assets/images/users/user-1.jpg') }}" alt="teacher" class="img-fluid rounded-circle" style="width:46px;height:46px;object-fit:cover;">
                        </div>
                        <div class="text-start ms-3">
                            <h5 class="fw-semibold text-dark m-0 font-14">{{ $teacherName }}</h5>
                            <p class="text-muted mb-0 font-12">Guru {{ $namaMapel }}</p>
                        </div>
                    </div>
                    @if($submission && $submission->status !== 'dinilai')
                        <div class="mt-3 text-muted font-12 d-flex align-items-center gap-2">
                            <i class="mdi mdi-timer-sand font-16 text-warning"></i>
                            Deadline: {{ \Carbon\Carbon::parse($tugas->due_date)->translatedFormat('d M Y, H:i') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Quill.js CDN -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

    <!-- Dropzone & Quill JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ---- Quill Rich Text Editor ----
            const quillEditor = document.getElementById('quill-editor');
            if (quillEditor) {
                const quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    placeholder: 'Tulis jawaban Anda di sini... (Bold, italic, list, dll tersedia)',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'indent': '-1'}, { 'indent': '+1' }],
                            ['blockquote', 'code-block'],
                            [{ 'color': [] }, { 'background': [] }],
                            ['clean']
                        ]
                    }
                });

                // Sync quill content to hidden input before form submit
                const form = document.getElementById('submissionForm');
                if (form) {
                    form.addEventListener('submit', function() {
                        document.getElementById('jawaban_teks_input').value = quill.root.innerHTML;
                    });
                }

                // Style the Quill toolbar/container
                const qlContainer = document.querySelector('.ql-container');
                if (qlContainer) qlContainer.style.borderRadius = '0 0 6px 6px';
                const qlToolbar = document.querySelector('.ql-toolbar');
                if (qlToolbar) qlToolbar.style.borderRadius = '6px 6px 0 0';
            }

            // ---- Dropzone ----
            const dropzone = document.getElementById('dropzoneClickable');
            const fileInput = document.getElementById('file_jawaban_input');
            const fileInfo = document.getElementById('file_selection_info');
            const fileNameSpan = document.getElementById('selected_file_name');

            if(dropzone) {
                dropzone.addEventListener('click', function() {
                    fileInput.click();
                });

                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        fileNameSpan.innerText = file.name;
                        fileInfo.classList.remove('d-none');
                    } else {
                        fileInfo.classList.add('d-none');
                    }
                });

                dropzone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    dropzone.style.backgroundColor = 'rgba(91, 109, 240, 0.1)';
                });

                dropzone.addEventListener('dragleave', function() {
                    dropzone.style.backgroundColor = 'rgba(241, 243, 250, 0.5)';
                });

                dropzone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    dropzone.style.backgroundColor = 'rgba(241, 243, 250, 0.5)';
                    if(e.dataTransfer.files.length) {
                        fileInput.files = e.dataTransfer.files;
                        fileNameSpan.innerText = e.dataTransfer.files[0].name;
                        fileInfo.classList.remove('d-none');
                    }
                });
            }
        });
    </script>
@endsection
