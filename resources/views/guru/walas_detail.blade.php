@extends('layouts.main')

@section('main')
    <!-- Page Header and Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('guru/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('guru.walas.index') }}">Wali Kelas</a></li>
                        <li class="breadcrumb-item active text-primary">Detail Siswa</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Kelas Perwalian</h4>
            </div>
        </div>
    </div>

    <!-- Student Profile Header Card -->
    <div class="row mt-2">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border border-light overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-sm-row align-items-center gap-4">
                        <!-- Student Avatar/Initials -->
                        @php
                            $words = explode(' ', $siswa->user->name);
                            $initials = '';
                            foreach (array_slice($words, 0, 2) as $w) {
                                $initials .= strtoupper(substr($w, 0, 1));
                            }
                            $colors = ['#5b6df0', '#1abc9c', '#f1556c', '#f7b84b', '#4a81d4', '#6559cc'];
                            $bgColor = $colors[abs(crc32($siswa->id)) % count($colors)];
                        @endphp
                        
                        <div class="position-relative">
                            <div class="avatar-xl rounded-circle d-flex align-items-center justify-content-center text-white fw-bold font-24 shadow-sm" style="background-color: {{ $bgColor }}; width: 88px; height: 88px;">
                                {{ $initials }}
                            </div>
                        </div>

                        <!-- Profile Info -->
                        <div class="flex-grow-1 text-center text-sm-start">
                            <div class="d-flex flex-column flex-sm-row align-items-center gap-2 mb-2">
                                <h3 class="text-dark fw-bold m-0 font-22">{{ $siswa->user->name }}</h3>
                                @if($rank <= 5)
                                    <span class="badge bg-soft-blue text-blue font-11 rounded px-2" style="background-color: rgba(91, 109, 240, 0.15) !important; color: #5b6df0 !important;">Top {{ $rank }} Student</span>
                                @endif
                            </div>
                            
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-x-4 gap-y-1 font-13 text-muted">
                                <span><i class="mdi mdi-school-outline me-1"></i>Kelas: <strong>{{ $kelas->name }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Academic Performance Column -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border border-light h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="header-title text-dark fw-bold m-0 font-15">Academic Performance</h4>
                        <small class="text-muted">Semester 1 - 2025/2026 Academic Year</small>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge font-12 px-3 py-1-5" style="background-color: rgba(10, 191, 156, 0.12) !important; color: #0abf9c !important;">
                            Avg: {{ number_format($rataRata, 1) }}
                        </span>
                        <span class="badge font-12 px-3 py-1-5" style="background-color: rgba(91, 109, 240, 0.12) !important; color: #5b6df0 !important;">
                            Rank: {{ $rank }}/{{ $totalSiswa }}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr class="text-uppercase text-muted font-11 tracking-wider" style="letter-spacing: 0.5px;">
                                    <th>Subject Name</th>
                                    <th class="text-center">Score</th>
                                    <th class="text-center">KKM</th>
                                    <th class="text-center">Grade</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($nilaiTransformed as $n)
                                    @php
                                        // Grade Badge Color Class
                                        $grade = $n->grade;
                                        if (str_contains($grade, 'A')) {
                                            $badgeClass = 'bg-soft-success text-success';
                                        } elseif (str_contains($grade, 'B')) {
                                            $badgeClass = 'bg-soft-primary text-primary';
                                        } elseif (str_contains($grade, 'C')) {
                                            $badgeClass = 'bg-soft-warning text-warning';
                                        } else {
                                            $badgeClass = 'bg-soft-danger text-danger';
                                        }
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold text-dark font-13">{{ $n->subject_name }}</td>
                                        <td class="text-center font-13 fw-bold">{{ $n->score }}</td>
                                        <td class="text-center text-muted font-13">{{ $n->kkm }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $badgeClass }} font-11 px-2 py-0-5 rounded">
                                                {{ $grade }}
                                            </span>
                                        </td>
                                        <td class="text-muted font-12 text-wrap" style="max-width: 200px;">{{ $n->remarks }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <i class="mdi mdi-notebook-remove font-24 d-block mb-1 text-secondary"></i>
                                            Belum ada nilai terdaftar untuk siswa ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top text-center py-2">
                    <span class="text-muted font-12">Menampilkan semua mata pelajaran semester ini ({{ $nilaiTransformed->count() }} mapel)</span>
                </div>
            </div>
        </div>

        <!-- Notes Column -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border border-light h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex align-items-center gap-2">
                    <div class="avatar-xs bg-soft-blue rounded d-flex align-items-center justify-content-center" style="background-color: rgba(91, 109, 240, 0.12) !important;">
                        <i class="fe-edit-2 text-primary"></i>
                    </div>
                    <h4 class="header-title text-dark fw-bold m-0 font-14">Catatan Wali Kelas</h4>
                </div>

                <div class="card-body d-flex flex-column justify-content-between">
                    <form action="{{ route('guru.walas.catatan.store', $siswa->id) }}" method="POST" class="h-100 d-flex flex-column">
                        @csrf
                        <div class="form-group mb-3 flex-grow-1">
                            <label class="form-label text-uppercase text-muted font-10 tracking-wider fw-bold mb-2">Catatan Wali Kelas (Semester Notes)</label>
                            <textarea id="catatan-text" name="catatan" class="form-control bg-light bg-opacity-25" rows="10" maxlength="500" placeholder="Berikan catatan perkembangan, pencapaian, atau motivasi untuk siswa ini di akhir semester..." style="resize: none; border-color: #eef2f7 !important;">{{ $catatan->description ?? '' }}</textarea>
                            
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small id="char-count" class="text-muted font-11">0/500</small>
                                <a href="#" id="use-template" class="font-11 fw-semibold text-primary" style="text-decoration: none;">Use Template</a>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mt-auto pt-3 border-top">
                            <button type="submit" class="btn text-white px-3 flex-grow-1" style="background-color: #5b6df0; border-color: #5b6df0; font-weight: 600;">
                                Simpan Catatan
                            </button>
                            <a href="{{ route('guru.walas.index') }}" class="btn btn-outline-secondary px-3" style="font-weight: 600;">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('catatan-text');
            const charCount = document.getElementById('char-count');
            const useTemplateBtn = document.getElementById('use-template');

            function updateCharCount() {
                charCount.textContent = textarea.value.length + '/500';
            }

            // Init count
            updateCharCount();

            // Textarea input listener
            textarea.addEventListener('input', updateCharCount);

            // Use template logic
            useTemplateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const studentName = "{{ $siswa->user->name }}";
                const average = "{{ number_format($rataRata, 1) }}";
                
                // Realistic academic note template
                let templateText = "";
                if (parseFloat(average) >= 85) {
                    templateText = `${studentName} menunjukkan perkembangan yang sangat memuaskan di kelas perwalian ini dengan rata-rata nilai ${average}. Selalu aktif berpartisipasi dan menyelesaikan tugas tepat waktu. Terus pertahankan prestasimu di semester berikutnya!`;
                } else if (parseFloat(average) >= 75) {
                    templateText = `${studentName} menunjukkan perkembangan yang baik dengan rata-rata nilai ${average}. Cukup rajin dalam mengikuti pelajaran. Agar lebih fokus pada pelajaran tertentu dan pertahankan motivasi belajar!`;
                } else {
                    templateText = `${studentName} memerlukan motivasi dan bimbingan belajar tambahan untuk mendongkrak pencapaian akademiknya. Terus bersemangat, jangan ragu untuk bertanya, dan tingkatkan disiplin belajar!`;
                }

                textarea.value = templateText;
                updateCharCount();
            });
        });
    </script>
@endsection
