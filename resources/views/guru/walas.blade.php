@extends('layouts.main')

@section('main')
    <!-- Page Header and Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('guru/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active text-primary">Wali Kelas</li>
                    </ol>
                </div>
                <h4 class="page-title">Manajemen Kelas Perwalian</h4>
            </div>
        </div>
    </div>

    <!-- Top Summary Cards -->
    <div class="row mt-2">
        <!-- Class Info Card -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border border-light h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="avatar-lg rounded-circle d-flex align-items-center justify-content-center me-3" style="background-color: rgba(91, 109, 240, 0.1); width: 64px; height: 64px;">
                        <i class="fe-home font-28 text-primary"></i>
                    </div>
                    <div>
                        <span class="text-muted font-12 fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.5px;">KELAS TERDAFTAR</span>
                        <h3 class="text-dark fw-bold m-0 font-22">Kelas {{ $kelas->name }}</h3>
                        <span class="text-success font-13 fw-semibold d-block mt-1">Tahun Ajaran 2025/2026</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Count Card -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border border-light h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="avatar-lg rounded-circle d-flex align-items-center justify-content-center me-3" style="background-color: rgba(10, 191, 156, 0.1); width: 64px; height: 64px;">
                        <i class="fe-users font-28 text-success"></i>
                    </div>
                    <div>
                        <span class="text-muted font-12 fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.5px;">TOTAL SISWA</span>
                        <h3 class="text-dark fw-bold m-0 font-22">{{ $totalSiswa }} Siswa</h3>
                        <span class="text-muted font-13 d-block mt-1">Terdaftar aktif di sistem sekolah</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student List Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border border-light">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="header-title text-dark fw-bold m-0 font-16">Daftar Siswa Kelas {{ $kelas->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatable" class="table table-hover dt-responsive nowrap w-100 align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-uppercase text-muted font-11 tracking-wider" style="letter-spacing: 0.5px;">
                                    <th class="text-center" style="width: 8%">NO</th>
                                    <th class="text-start">NAMA SISWA</th>
                                    <th class="text-center">RATA-RATA NILAI</th>
                                    <th class="text-center" style="width: 15%">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $idx => $student)
                                    @php
                                        // Grade Badge Color Class
                                        $avgGrade = $student->rata_rata_nilai;
                                        if ($avgGrade >= 85) {
                                            $badgeStyle = 'background-color: rgba(10, 191, 156, 0.12) !important; color: #0abf9c !important; font-weight: 700;';
                                        } elseif ($avgGrade >= 75) {
                                            $badgeStyle = 'background-color: rgba(91, 109, 240, 0.12) !important; color: #5b6df0 !important; font-weight: 700;';
                                        } elseif ($avgGrade > 0) {
                                            $badgeStyle = 'background-color: rgba(241, 85, 108, 0.12) !important; color: #f1556c !important; font-weight: 700;';
                                        } else {
                                            $badgeStyle = 'background-color: rgba(148, 163, 184, 0.12) !important; color: #64748b !important; font-weight: 700;';
                                        }
                                    @endphp
                                    <tr>
                                        <!-- No -->
                                        <td class="text-center font-14 text-muted fw-semibold">{{ sprintf("%02d", $idx + 1) }}</td>
                                        
                                        <!-- Student Profile & Icon -->
                                         <td class="text-start">
                                             <div class="d-flex align-items-center gap-3">
                                                 <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #eef2f7; width: 36px; height: 36px; min-width: 36px;">
                                                     <i class="mdi mdi-account" style="font-size: 22px; color: #8a9ab0; line-height: 1;"></i>
                                                 </div>
                                                <div>
                                                    <span class="d-block fw-bold text-dark font-14">{{ $student->user->name }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Average Grade -->
                                        <td class="text-center">
                                            <span class="badge px-3 py-1-5 rounded font-13" style="{{ $badgeStyle }}">
                                                {{ $avgGrade > 0 ? number_format($avgGrade, 1) : '-' }}
                                            </span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="text-center">
                                            <a href="{{ route('guru.walas.siswa', $student->id) }}" class="btn btn-sm text-white px-3" style="background-color: #5b6df0; border-color: #5b6df0; border-radius: 4px; font-weight: 600;">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <i class="fe-users font-28 d-block mb-2 text-secondary"></i>
                                            Belum ada data siswa di kelas perwalian Anda.
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
@endsection
