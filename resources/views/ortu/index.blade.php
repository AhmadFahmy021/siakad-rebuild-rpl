@extends('layouts.main')
@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                </div>
                <h4 class="page-title">Dashboard Orang Tua (Siswa: {{ $siswa->user->name }})</h4>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row">
        <!-- Unpaid Bills Card -->
        <div class="col-md-6 col-xl-4">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-danger border-danger border">
                                <i class="fe-dollar-sign font-22 avatar-title text-danger"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1">
                                    Rp <span data-counter data-value="{{ $totalTagihanBelumBayar }}">
                                        {{ number_format($totalTagihanBelumBayar, 0, ',', '.') }}
                                    </span>
                                </h3>
                                <p class="text-muted mb-1 text-truncate">Tagihan Belum Dibayar</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <!-- Total Schedules Card -->
        <div class="col-md-6 col-xl-4">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                <i class="fe-book-open font-22 avatar-title text-primary"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1">
                                    <span data-counter data-value="{{ $totalJadwal }}">
                                        {{ $totalJadwal }}
                                    </span> Sesi
                                </h3>
                                <p class="text-muted mb-1 text-truncate">Total Jadwal Anak</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <!-- Closest Schedule Card -->
        <div class="col-md-6 col-xl-4">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                <i class="fe-calendar font-22 avatar-title text-warning"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="text-end">
                                @if($upcomingJadwal)
                                    <h5 class="text-dark mt-1 mb-0 text-truncate" title="{{ $upcomingJadwal->matapelajaran?->nama ?? 'Tidak ada mapel' }}">
                                        {{ $upcomingJadwal->matapelajaran?->nama ?? 'Tidak ada mapel' }}
                                    </h5>
                                    <p class="text-muted mb-0 text-truncate">
                                       <span class="text-capitalize">{{ $upcomingJadwal->hari }}</span>  ({{ substr($upcomingJadwal->jam_mulai, 0, 5) }} - {{ substr($upcomingJadwal->jam_selesai, 0, 5) }})
                                    </p>
                                @else
                                    <h4 class="text-dark mt-1 text-truncate">Tidak ada</h4>
                                @endif
                                <p class="text-muted mb-1 text-truncate mt-1">Jadwal Terdekat</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->
    </div>
    <!-- end row-->

    <div class="row">
        <!-- Weekly Schedule Matrix -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Jadwal Pelajaran Anak</h4>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center font-13">
                            <thead class="table-dark">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Senin</th>
                                    <th>Selasa</th>
                                    <th>Rabu</th>
                                    <th>Kamis</th>
                                    <th>Jumat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($scheduleMatrix as $timeLabel => $days)
                                    <tr>
                                        <td class="fw-bold align-middle">{{ $timeLabel }}</td>
                                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $day)
                                            <td class="align-middle">
                                                @if ($days[$day])
                                                    <div class="p-1 rounded bg-soft-info text-info">
                                                        <span class="d-block fw-bold text-truncate" title="{{ $days[$day]->matapelajaran?->nama ?? 'Tidak ada mapel' }}">
                                                            {{ $days[$day]->matapelajaran?->nama ?? 'Tidak ada mapel' }}
                                                        </span>
                                                        <small class="d-block text-muted text-truncate" title="{{ $days[$day]->guru?->user?->name ?? 'Tidak ada guru' }}">
                                                            {{ $days[$day]->guru?->user?->name ?? 'Tidak ada guru' }}
                                                        </small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Payments History -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Riwayat Pembayaran (Approved)</h4>

                    <div class="table-responsive">
                        <table class="table table-borderless table-nowrap table-hover table-centered m-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Tagihan</th>
                                    <th>Tanggal Pembayaran</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatPembayaran as $item)
                                    <tr>
                                        <td>
                                            <h5 class="m-0 fw-normal text-truncate" style="max-width: 150px;" title="{{ $item->tagihan->name ?? 'Tagihan' }}">
                                                {{ $item->tagihan->name ?? 'Tagihan' }}
                                            </h5>
                                        </td>
                                        <td>
                                            {{ $item->tanggal ? $item->tanggal->format('d M Y') : '-' }}
                                        </td>
                                        <td class="fw-bold">
                                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-success text-success">Approved</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Belum ada riwayat pembayaran yang disetujui.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Counterup Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.jQuery) {
                jQuery('[data-counter]').each(function() {
                    var $this = jQuery(this);
                    var val = parseFloat($this.attr('data-value'));
                    $this.text('0');
                    jQuery({ countNum: 0 }).animate({ countNum: val }, {
                        duration: 1000,
                        easing: 'swing',
                        step: function() {
                            if (val % 1 === 0) {
                                $this.text(Math.round(this.countNum).toLocaleString('id-ID'));
                            } else {
                                $this.text(this.countNum.toFixed(2));
                            }
                        },
                        complete: function() {
                            if (val % 1 === 0) {
                                $this.text(Math.round(this.countNum).toLocaleString('id-ID'));
                            } else {
                                $this.text(this.countNum.toFixed(2));
                            }
                        }
                    });
                });
            }
        });
    </script>
@endsection
