@extends('layouts.main')

@section('main')
    <!-- Page Title & Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Catatan Wali Kelas</h4>
                <h5 class="text-muted fw-normal mt-1 mb-0">Laporan rangkuman bimbingan dan perkembangan siswa semester ini.</h5>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="row mt-3">
        <!-- Left Main Column (Document Card) -->
        <div class="col-lg-8">
            <div class="card overflow-hidden border border-light shadow-sm">
                <!-- Card Header -->
                <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center pt-3 pb-0">
                    @if($feedbackDate)
                        <span class="badge bg-soft-blue text-blue rounded px-2 py-1 font-11 fw-bold text-uppercase" style="background-color: rgba(91, 109, 240, 0.12) !important; color: #5b6df0 !important;">
                            Tanggal Diberikan: {{ $feedbackDate }}
                        </span>
                    @endif
                </div>

                <div class="card-body pt-1">
                    <h4 class="text-dark fw-bold mb-4 font-16">{{ $feedbackTitle }}</h4>

                    <!-- Letter Box Container -->
                    <div class="border rounded p-4 position-relative bg-white" style="border: 1px solid #eef2f7 !important;">
                        
                        <!-- Document Watermarked background and header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="text-blue fw-bold font-13 text-uppercase tracking-wider m-0" style="color: #5b6df0 !important;">KETERANGAN WALI KELAS</h5>
                                <div style="width: 140px; height: 3px; background-color: #5b6df0; class: mt-1;"></div>
                            </div>
                            <!-- Small Stamp/Abstract Art icon on right -->
                            <div class="rounded overflow-hidden" style="width: 40px; height: 30px; opacity: 0.7;">
                                <svg width="40" height="30" viewBox="0 0 40 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="40" height="30" rx="3" fill="#a0aec0" />
                                    <path d="M5 25L15 15L25 22L35 10" stroke="white" stroke-width="2" />
                                </svg>
                            </div>
                        </div>

                        <!-- Identity Info Block -->
                        <h6 class="text-dark fw-bold mb-2 font-13">Identitas Siswa:</h6>
                        <div class="row font-14 mb-4">
                            <div class="col-md-6 mb-2">
                                <div class="row">
                                    <div class="col-4 text-muted">Nama:</div>
                                    <div class="col-8 fw-semibold text-dark">{{ $studentName }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="row">
                                    <div class="col-4 text-muted">Kelas:</div>
                                    <div class="col-8 fw-semibold text-dark">{{ $className }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Feedback/Catatan Block -->
                        <h6 class="text-dark fw-bold mb-2 font-13">Catatan Perkembangan:</h6>
                        <div class="alert alert-soft-blue p-3 rounded bg-light bg-opacity-50 text-dark border-0 mb-0" style="background-color: rgba(91, 109, 240, 0.06) !important; border-left: 4px solid #5b6df0 !important;">
                            <p class="mb-0 font-14 italic text-dark sp-line-2 fw-medium" style="font-style: italic; color: #3a4bb0 !important;">
                                "{{ $feedbackText }}"
                            </p>
                        </div>

                        <!-- Watermark Icon in background center (Graduate Cap) -->
                        <div class="position-absolute start-50 top-50 translate-middle pointer-events-none" style="opacity: 0.03; z-index: 1;">
                            <i class="mdi mdi-school font-100" style="font-size: 150px;"></i>
                        </div>
                    </div>
                </div>

                <!-- Footer disclaimer -->
                <div class="card-footer bg-light bg-opacity-50 border-top p-3 font-13 text-muted text-start">
                    Dokumen ini merupakan catatan resmi portal akademik.
                </div>
            </div>
        </div>

        <!-- Right Column Sidebar -->
        <div class="col-lg-4">
            <!-- Walas Profile Card -->
            <div class="card border border-light shadow-sm">
                <div class="card-body text-center">
                    <h5 class="header-title text-start mb-3 text-uppercase font-11 tracking-wider text-muted">INFORMASI WALI KELAS</h5>
                    
                    <div class="position-relative d-inline-block mx-auto mb-3">
                        <div class="rounded overflow-hidden" style="width: 80px; height: 80px;">
                            <div class="d-flex align-items-center justify-content-center w-100 h-100" style="background-color: #eef2f7;"><i class="mdi mdi-account" style="font-size: 48px; color: #8a9ab0; line-height: 1;"></i></div>
                        </div>
                        <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle" style="width: 14px; height: 14px; display: inline-block;"></span>
                    </div>

                    <h5 class="fw-semibold text-dark m-0">{{ $teacherName }}</h5>
                    <p class="text-muted font-13 mb-0 mt-1">{{ $teacherSubject }}</p>
                </div>
            </div>

            <!-- Informational Alert Box -->
            <div class="alert alert-soft-blue p-3 border-0 d-flex gap-3 align-items-start rounded shadow-none" style="background-color: rgba(91, 109, 240, 0.08) !important; color: #3a4bb0 !important;">
                <div class="avatar-sm bg-soft-blue rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="background-color: rgba(91, 109, 240, 0.15) !important;">
                    <i class="fe-info font-16 text-blue" style="color: #5b6df0 !important;"></i>
                </div>
                <div>
                    <h5 class="text-blue fw-bold m-0 font-14" style="color: #3b4cb8 !important;">Informasi</h5>
                    <p class="text-muted mb-0 font-13 mt-1" style="color: #4e5fad !important;">Catatan ini diperbarui setiap tengah semester dan akhir semester untuk memantau progres siswa.</p>
                </div>
            </div>
        </div>
    </div>
@endsection