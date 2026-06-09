@extends('layouts.main')
@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard Admin</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                <i class="fe-file-text font-22 avatar-title text-primary"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <p class="text-muted mb-0 font-12">Rp.</p>
                                <h4 class="text-dark mt-0 mb-1 font-18" style="white-space: nowrap;"><span data-counter data-value="{{ $totalTagihanUang }}">{{ number_format($totalTagihanUang, 0, ',', '.') }}</span></h4>
                                <p class="text-muted mb-1 text-truncate">Total Tagihan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                <i class="fe-check-circle font-22 avatar-title text-success"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <p class="text-muted mb-0 font-12">Rp.</p>
                                <h4 class="text-dark mt-0 mb-1 font-18" style="white-space: nowrap;"><span data-counter data-value="{{ $totalPembayaranSudah }}">{{ number_format($totalPembayaranSudah, 0, ',', '.') }}</span></h4>
                                <p class="text-muted mb-1 text-truncate" title="Sudah Dibayarkan">Sudah Dibayarkan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                <i class="fe-alert-circle font-22 avatar-title text-warning"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                @php $belumBayar = max(0, $totalPembayaranBelum); @endphp
                                <p class="text-muted mb-0 font-12">Rp.</p>
                                <h4 class="text-dark mt-0 mb-1 font-18" style="white-space: nowrap;"><span data-counter data-value="{{ $belumBayar }}">{{ number_format($belumBayar, 0, ',', '.') }}</span></h4>
                                <p class="text-muted mb-1 text-truncate" title="Belum Dibayarkan">Belum Dibayarkan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                <i class="fe-bar-chart-2 font-22 avatar-title text-info"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                @php $persen = $totalTagihanUang > 0 ? min(100, round(($totalPembayaranSudah / $totalTagihanUang * 100), 1)) : 0; @endphp
                                <h3 class="text-dark mt-1 font-18" style="white-space: nowrap;">{{ $persen }}%</h3>
                                <p class="text-muted mb-1 text-truncate" title="Persentase Bayar">Persentase Bayar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Pertumbuhan Guru (12 Bulan Terakhir)</h4>
                    <div dir="ltr">
                        <div id="guru-growth-chart" class="mt-4" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Pertumbuhan Siswa (12 Bulan Terakhir)</h4>
                    <div dir="ltr">
                        <div id="siswa-growth-chart" class="mt-4" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Rincian Pembayaran Per Kategori Tagihan</h4>
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover table-nowrap m-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kategori Tagihan</th>
                                    <th class="text-end">Jumlah Pembayaran (Rp)</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tagihanBayarPerKategori as $kategori => $nominal)
                                    <tr>
                                        <td><h5 class="m-0 fw-normal">{{ $kategori }}</h5></td>
                                        <td class="text-end"><h5 class="m-0">Rp. {{ number_format($nominal, 0, ',', '.') }}</h5></td>
                                        <td class="text-center"><span class="badge bg-success">Sudah Approve</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">Tidak ada data pembayaran yang sudah di-approve</td>
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

{{-- Ganti @push('scripts') -> @section('js') karena layout pakai @yield('js'), bukan @stack('scripts') --}}
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var sharedLineOptions = {
                chart: { type: 'line', toolbar: { show: false } },
                stroke: { curve: 'smooth', width: 2 },
                dataLabels: { enabled: false },
                grid: { borderColor: '#eef2f7' },
                markers: { size: 4 },
                xaxis: { categories: {!! json_encode($months) !!} }
            };

            var guruGrowthOptions = Object.assign({}, sharedLineOptions, {
                series: [{ name: 'Jumlah Guru', data: {!! json_encode($guruData) !!} }],
                colors: ['#4a81d4'],
                tooltip: {
                    y: { formatter: function(val) { return val + ' Guru'; } }
                }
            });
            new ApexCharts(document.querySelector("#guru-growth-chart"), guruGrowthOptions).render();

            var siswaGrowthOptions = Object.assign({}, sharedLineOptions, {
                series: [{ name: 'Jumlah Siswa', data: {!! json_encode($siswaData) !!} }],
                colors: ['#1abc9c'],
                tooltip: {
                    y: { formatter: function(val) { return val + ' Siswa'; } }
                }
            });
            new ApexCharts(document.querySelector("#siswa-growth-chart"), siswaGrowthOptions).render();

            // Counter animation — baca dari data-value, pakai data-counter agar tidak bentrok dengan plugin bawaan template
            $('[data-counter]').each(function() {
                var $this = $(this),
                    to   = parseInt($this.attr('data-value'), 10) || 0,
                    from = 0,
                    speed = 1500,
                    refreshInterval = 50,
                    steps = speed / refreshInterval,
                    increase = to / steps;

                var loopCount = 0, current = from;
                var interval = setInterval(function() {
                    current += increase;
                    loopCount++;
                    if (loopCount >= steps) {
                        current = to;
                        clearInterval(interval);
                    }
                    $this.text(Math.round(current).toLocaleString('id-ID'));
                }, refreshInterval);
            });
        });
    </script>
@endsection
