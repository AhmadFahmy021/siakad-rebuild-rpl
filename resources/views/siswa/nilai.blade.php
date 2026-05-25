@extends('layouts.main')

@section('main')
<div class="container-fluid">

    <!-- Page Header Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Transkrip & Nilai</h4>
                <h5 class="text-muted fw-normal mt-1 mb-0">Pantau transkrip nilai akademik</h5>
            </div>
        </div>
    </div>

    <!-- Academic Transcript Card -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card overflow-hidden border border-light">
                <!-- Card Header -->
                <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center pt-3 pb-0">
                    <h4 class="header-title text-dark fw-bold m-0 font-16">Transkrip Nilai Akademik</h4>
                    <div class="d-flex align-items-center gap-2 font-13 text-muted fw-medium"></div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-uppercase text-muted font-11 tracking-wider" style="letter-spacing: 0.5px;">
                                    <th>Mata Pelajaran</th>
                                    <th class="text-center">Tugas (40%)</th>
                                    <th class="text-center">UTS (25%)</th>
                                    <th class="text-center">UAS (35%)</th>
                                    <th class="text-center">Nilai Akhir</th>
                                    <th class="text-center">Predikat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nilaiTransformed as $n)
                                    <tr>
                                        <td>
                                            <span class="d-block fw-semibold text-dark font-14">{{ $n->mata_pelajaran_nama }}</span>
                                        </td>
                                        <td class="text-center font-14 text-dark">{{ number_format($n->tugas, 1) }}</td>
                                        <td class="text-center font-14 text-dark">{{ number_format($n->uts, 1) }}</td>
                                        <td class="text-center font-14 text-dark">{{ number_format($n->uas, 1) }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-soft-blue text-blue font-13 px-2 py-1 rounded" style="background-color: rgba(91, 109, 240, 0.15) !important; color: #5b6df0 !important; font-size: 13px !important; font-weight: 600;">
                                                {{ number_format($n->nilai_akhir, 1) }}
                                            </span>
                                        </td>
                                        <td class="text-center fw-semibold font-14" style="color: #5b6df0 !important;">
                                            {{ $n->predikat }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Academic Summary Footer -->
                <div class="card-footer bg-white border-top p-3">
                    <div class="d-flex align-items-center gap-4">
                        <div>
                            <small class="text-muted font-11 text-uppercase fw-bold d-block" style="letter-spacing: 0.5px;">TOTAL NILAI</small>
                            <h3 class="text-dark fw-bold m-0 mt-1 font-20">{{ number_format($totalNilaiAkhir, 1) }}</h3>
                        </div>
                        <div class="border-end" style="width: 1px; height: 35px; background-color: #e5e8eb;"></div>
                        <div>
                            <small class="text-muted font-11 text-uppercase fw-bold d-block" style="letter-spacing: 0.5px;">RATA-RATA SEMESTER</small>
                            <h3 class="text-primary fw-bold m-0 mt-1 font-20" style="color: #5b6df0 !important;">{{ number_format($rataRata, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
