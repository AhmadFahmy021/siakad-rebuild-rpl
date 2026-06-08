@extends('layouts.main')
@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard Tata Usaha</h4>
            </div>
        </div>
    </div>

    <!-- KPI Cards Row 1 -->
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                <i class="fe-file-text font-22 avatar-title text-primary"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $jumlahTagihan }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Jumlah Tagihan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                <i class="fe-check-circle font-22 avatar-title text-success"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1">Rp. <span data-plugin="counterup">{{ $totalUangMasuk }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Total Uang Masuk</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                <i class="fe-clock font-22 avatar-title text-info"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ Pembayaran::where('status', 'pending')->count() }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Pembayaran Pending</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                <i class="fe-book font-22 avatar-title text-warning"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $mataPelajaran->count() }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Total Mata Pelajaran</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Pertumbuhan Hasil Pembayaran (12 Bulan Terakhir)</h4>
                    <div dir="ltr">
                        <div id="payment-growth-chart" class="mt-4" style="height: 350px;" data-colors="#1abc9c"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Ringkasan Status Pembayaran</h4>
                    <div id="payment-status-chart" class="mt-4" style="height: 250px;" data-colors="#1abc9c,#f1556c"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mata Pelajaran Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Daftar Mata Pelajaran - Status Pembayaran</h4>
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover table-nowrap m-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Mata Pelajaran</th>
                                    <th class="text-center">Pending</th>
                                    <th class="text-center">Sudah Di-Approve</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mataPelajaranStats as $mp)
                                    <tr>
                                        <td>
                                            <h5 class="m-0 fw-normal">{{ $mp['nama'] }}</h5>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark">{{ $mp['pending'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $mp['approve'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <strong>{{ $mp['pending'] + $mp['approve'] }}</strong>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            Tidak ada data mata pelajaran
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
    <script>
        // Pertumbuhan Pembayaran Chart
        var paymentGrowthOptions = {
            chart: {
                type: 'area',
                toolbar: { show: false },
                sparkline: { enabled: false }
            },
            series: [{
                name: 'Pembayaran (Rp)',
                data: {!! json_encode($pembayaranData) !!}
            }],
            xaxis: {
                categories: {!! json_encode($months) !!}
            },
            colors: ['#1abc9c'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100, 100, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 }
        };
        var paymentGrowthChart = new ApexCharts(document.querySelector("#payment-growth-chart"), paymentGrowthOptions);
        paymentGrowthChart.render();

        // Status Pembayaran Chart (Donut)
        var pendingCount = {{ $pembayaranPending }};
        var approveCount = {{ $pembayaranApprove }};

        var paymentStatusOptions = {
            chart: { type: 'donut' },
            series: [pendingCount, approveCount],
            labels: ['Pending', 'Sudah Di-Approve'],
            colors: ['#f1556c', '#1abc9c'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%'
                    }
                }
            },
            dataLabels: { enabled: true }
        };
        var paymentStatusChart = new ApexCharts(document.querySelector("#payment-status-chart"), paymentStatusOptions);
        paymentStatusChart.render();

        // Counter animation
        $('[data-plugin="counterup"]').each(function() {
            var $this = $(this),
                from = parseInt($this.attr('data-from'), 10) || 0,
                to = parseInt($this.text(), 10),
                speed = 2000,
                refreshInterval = 100,
                increase = (to - from) / (speed / refreshInterval);

            var loopCount = 0,
                checkMax = speed / refreshInterval,
                current = from;

            var interval = setInterval(function() {
                current += increase;
                loopCount++;
                if (loopCount >= checkMax) {
                    current = to;
                }
                $this.text(Math.floor(current).toLocaleString());
                if (loopCount >= checkMax) {
                    clearInterval(interval);
                }
            }, refreshInterval);
        });
    </script>
@endpush
