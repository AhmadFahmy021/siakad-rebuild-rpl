@extends('layouts.main')

@section('main')
    <!-- Welcome & Breadcrumbs -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box py-3">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0 font-12">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Assignments</a></li>
                        <li class="breadcrumb-item active">Assignment</li>
                    </ol>
                </div>
                <h2 class="text-dark fw-bold m-0 font-22">Assignment</h2>
            </div>
        </div>
    </div>

    <!-- Stats Summary Row -->
    <div class="row mt-2">
        <!-- Total Active Card -->
        <div class="col-md-3 mb-3">
            <div class="card widget-rounded-circle shadow-sm border border-light h-100">
                <div class="card-body p-3">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto">
                            <div class="avatar-md rounded-circle d-flex align-items-center justify-content-center" style="background-color: rgba(91, 109, 240, 0.12); width: 48px; height: 48px;">
                                <i class="fe-book-open font-20 text-blue" style="color: #5b6df0 !important;"></i>
                            </div>
                        </div>
                        <div class="col text-end">
                            <span class="text-muted font-11 fw-bold text-uppercase d-block" style="letter-spacing: 0.5px;">TOTAL ACTIVE</span>
                            <h3 class="text-dark fw-bold m-0 mt-1 font-20">{{ sprintf("%02d", $totalActive) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Card -->
        <div class="col-md-3 mb-3">
            <div class="card widget-rounded-circle shadow-sm border border-light h-100">
                <div class="card-body p-3">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto">
                            <div class="avatar-md rounded-circle d-flex align-items-center justify-content-center" style="background-color: rgba(74, 193, 142, 0.12); width: 48px; height: 48px;">
                                <i class="fe-check-circle font-20 text-success"></i>
                            </div>
                        </div>
                        <div class="col text-end">
                            <span class="text-muted font-11 fw-bold text-uppercase d-block" style="letter-spacing: 0.5px;">COMPLETED</span>
                            <h3 class="text-dark fw-bold m-0 mt-1 font-20">{{ sprintf("%02d", $totalCompleted) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Need Grading Card -->
        <div class="col-md-3 mb-3">
            <div class="card widget-rounded-circle shadow-sm border border-light h-100">
                <div class="card-body p-3">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto">
                            <div class="avatar-md rounded-circle d-flex align-items-center justify-content-center" style="background-color: rgba(241, 85, 108, 0.12); width: 48px; height: 48px;">
                                <i class="fe-edit font-20 text-danger"></i>
                            </div>
                        </div>
                        <div class="col text-end">
                            <span class="text-muted font-11 fw-bold text-uppercase d-block" style="letter-spacing: 0.5px;">NEED GRADING</span>
                            <h3 class="text-dark fw-bold m-0 mt-1 font-20 text-danger">{{ sprintf("%02d", $needGrading) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avg Submission Card -->
        <div class="col-md-3 mb-3">
            <div class="card widget-rounded-circle shadow-sm border border-light h-100">
                <div class="card-body p-3">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto">
                            <div class="avatar-md rounded-circle d-flex align-items-center justify-content-center" style="background-color: rgba(0, 131, 143, 0.12); width: 48px; height: 48px;">
                                <i class="fe-users font-20 text-info"></i>
                            </div>
                        </div>
                        <div class="col text-end">
                            <span class="text-muted font-11 fw-bold text-uppercase d-block" style="letter-spacing: 0.5px;">AVG SUBMISSION</span>
                            <h3 class="text-dark fw-bold m-0 mt-1 font-20 text-info">{{ $avgSubmissionRate }}%</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignment Records Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border border-light">
                <div class="card-body">
                    <!-- Filters & Title Header -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3 pb-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <h4 class="header-title text-dark fw-bold m-0 font-15">Assignment Records</h4>
                            <span class="badge bg-soft-blue text-blue rounded-pill px-2 py-0-5 font-10 fw-bold" style="background-color: rgba(91, 109, 240, 0.1) !important; color: #5b6df0 !important;">SEMESTER 1 - 2026</span>
                        </div>
                        
                        <!-- Dropdown Filters -->
                        <form method="GET" action="{{ route('assignment.index') }}" class="d-flex gap-2 align-items-center">
                            <select name="class_id" class="form-select form-select-sm border font-12" style="width: 140px; border-radius: 4px;" onchange="this.form.submit()">
                                <option value="">All Classes</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>

                            <select name="status" class="form-select form-select-sm border font-12" style="width: 140px; border-radius: 4px;" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                <option value="DRAFT" {{ request('status') == 'DRAFT' ? 'selected' : '' }}>DRAFT</option>
                                <option value="PUBLISHED" {{ request('status') == 'PUBLISHED' ? 'selected' : '' }}>PUBLISHED</option>
                                <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>COMPLETED</option>
                            </select>
                        </form>
                    </div>

                    <!-- Records Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-centered align-middle mb-0 font-13" style="border-color: #e5e8eb !important;">
                            <thead class="table-light">
                                <tr class="text-uppercase text-muted font-10 tracking-wider" style="letter-spacing: 0.5px;">
                                    <th style="width: 35%">Title & Subject</th>
                                    <th style="width: 10%" class="text-center">Class</th>
                                    <th style="width: 15%">Due Date</th>
                                    <th style="width: 20%">Submissions</th>
                                    <th style="width: 12%" class="text-center">Status</th>
                                    <th style="width: 8%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $assignment)
                                    @php
                                        // Calculations for submissions progress
                                        $totalStudents = \App\Models\Siswa::where('kelas_id', $assignment->kelas_id)->count();
                                        $submittedCount = $assignment->pengumpulanTugas->whereIn('status', ['sudah_mengumpulkan', 'dinilai'])->count();
                                        $percent = $totalStudents > 0 ? round(($submittedCount / $totalStudents) * 100) : 0;
                                        
                                        $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                                        $statusClass = 'bg-soft-secondary text-secondary';
                                        if ($assignment->status === 'PUBLISHED') {
                                            $statusClass = 'bg-success text-white';
                                        } elseif ($assignment->status === 'COMPLETED') {
                                            $statusClass = 'bg-info text-white';
                                        } elseif ($assignment->status === 'DRAFT') {
                                            $statusClass = 'bg-secondary text-white';
                                        }
                                    @endphp
                                    <tr>
                                        <!-- Title & Subject -->
                                        <td>
                                            <a href="{{ route('assignment.grade', $assignment->id) }}" class="d-block fw-bold text-dark font-14 mb-0 hover-primary">
                                                {{ $assignment->title }}
                                            </a>
                                            <small class="text-muted font-11">
                                                {{ $assignment->matapelajaran->nama ?? 'Tidak Diketahui' }} &bull; {{ $assignment->tipe ?? 'Homework' }}
                                            </small>
                                        </td>
                                        <!-- Class Badge -->
                                        <td class="text-center">
                                            <span class="badge bg-soft-blue text-blue rounded-pill px-2-5 py-1 font-11 fw-bold" style="background-color: rgba(91, 109, 240, 0.12) !important; color: #5b6df0 !important;">
                                                {{ $assignment->kelas->name ?? 'Belum Ditentukan' }}
                                            </span>
                                        </td>
                                        <!-- Due Date -->
                                        <td>
                                            <span class="text-dark font-12">
                                                <i class="mdi mdi-calendar-clock text-muted me-1"></i> {{ $dueDate->translatedFormat('d M Y') }}
                                            </span>
                                        </td>
                                        <!-- Submissions Bar -->
                                        <td>
                                            @if($totalStudents > 0)
                                                <div class="d-flex align-items-center justify-content-between mb-1 font-11">
                                                    <span class="fw-semibold text-dark">{{ $submittedCount }} / {{ $totalStudents }}</span>
                                                    <span class="text-muted fw-bold">{{ $percent }}%</span>
                                                </div>
                                                <div class="progress progress-sm" style="height: 6px;">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%; background-color: #5b6df0;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @else
                                                <span class="text-muted font-12">No students</span>
                                            @endif
                                        </td>
                                        <!-- Status Badge -->
                                        <td class="text-center">
                                            <span class="badge {{ $statusClass }} font-10 px-2 py-0-5 rounded-pill text-uppercase">
                                                {{ $assignment->status }}
                                            </span>
                                        </td>
                                        <!-- Action Buttons -->
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('assignment.edit', $assignment->id) }}" class="action-icon text-muted hover-primary p-1" title="Edit Assignment">
                                                    <i class="mdi mdi-square-edit-outline font-18"></i>
                                                </a>
                                                <a href="{{ route('assignment.destroy', $assignment->id) }}" class="action-icon text-muted text-danger p-1" title="Delete Assignment" data-confirm-delete2="true" data-name="{{ $assignment->title }}">
                                                    <i class="mdi mdi-delete-outline font-18 text-danger"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="mdi mdi-clipboard-text-multiple-outline font-28 d-block mb-2 text-secondary"></i>
                                            Belum ada tugas atau catatan rekaman penugasan kelas Anda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($assignments->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top flex-wrap font-12">
                            <span class="text-muted">
                                Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }} of {{ $assignments->total() }} assignments
                            </span>
                            <div>
                                {{ $assignments->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Create Assignment Sticky/Footer Button -->
    <div class="row mt-2">
        <div class="col-12">
            <a href="{{ route('assignment.create') }}" class="btn btn-primary bg-gradient-primary rounded font-13 px-4 py-2" style="background-color: #5b6df0; border-color: #5b6df0;">
                <i class="mdi mdi-plus me-1"></i> Create New Assignment
            </a>
        </div>
    </div>

    <style>
        .hover-primary:hover {
            color: #5b6df0 !important;
        }
        .action-icon:hover {
            opacity: 0.85;
        }
    </style>
@endsection
