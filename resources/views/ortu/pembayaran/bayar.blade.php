@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{-- <a href="{{ route('guru.create') }}" class="btn btn-outline-primary btn-sm">Add Guru</a> --}}
                </div>
                <h4 class="page-title">Upload Pembayaran</h4>
            </div>
        </div>
    </div>
    <div class="row col-12 ">
        <div class="card">
            <h4 class="card-header">Upload Pembayaran</h4>
            <div class="card-body">
                <form action="{{ url('ortu/pembayaran/'. $tagihan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-12 col-lg-6">
                            <div class="form-group mb-3">
                                <label for="siswa" class="form-label">Nama Siswa</label>
                                <input class="form-control" id="siswa" name="siswa" type="text" placeholder="Masukkan nama siswa" value="{{ old('siswa', $siswaKelas->siswa->user->name) }}" readonly disabled>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 col-lg-6">
                            <div class="form-group mb-3">
                                <label for="kelas" class="form-label">Kelas</label>
                                <input class="form-control" id="kelas" name="kelas" type="text" placeholder="Masukkan nama kelas" value="{{ old('kelas', $siswaKelas->kelas->name) }}" readonly disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="tagihan" class="form-label">Tagihan</label>
                        <input class="form-control" id="tagihan" name="tagihan" type="text" placeholder="Masukkan nama tagihan" value="{{ old('tagihan', $tagihan->name) }}" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-12 col-lg-6">
                            <div class="form-group mb-3">
                                <label for="nominal" class="form-label">Nominal Yang Harus Dibayar</label>
                                <input class="form-control" id="nominal" name="nominal" type="text" placeholder="Masukkan nominal yang harus dibayar" value="{{ old('nominal', $tagihan->nominal) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 col-lg-6">
                            <div class="form-group mb-3">
                                <label for="semester" class="form-label">Semester</label>
                                <input class="form-control" id="semester" name="semester" type="text" placeholder="Masukkan semester" value="{{ old('semester', optional($pembayaran)->semester) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="overflow-auto d-md-none">
                                <ul class="nav nav-tabs flex-nowrap text-nowrap">
                                    @foreach ($bank as $item)
                                        <li class="nav-item">
                                            <a href="#bank-{{ $loop->iteration }}"
                                            data-bs-toggle="tab"
                                            class="nav-link {{ $loop->first ? 'active' : '' }}">
                                                {{ $item->nama_bank }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="d-none d-md-block">
                                <ul class="nav nav-tabs">
                                    @foreach ($bank as $item)
                                        <li class="nav-item">
                                            <a href="#bank-{{ $loop->iteration }}"
                                            data-bs-toggle="tab"
                                            class="nav-link {{ $loop->first ? 'active' : '' }}">
                                                {{ $item->nama_bank }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="tab-content mb-3">
                                @foreach ($bank as $item)
                                    <div class="tab-pane {{ $loop->first ? 'show active' : '' }} pl-4"
                                        id="bank-{{ $loop->iteration }}">
                                        <p>Nama Bank: {{ $item->nama_bank }}</p>
                                        <p>Nomor Rekening: {{ $item->nomor_rekening }}</p>
                                        <p>Atas Nama: {{ $item->nama_pemilik }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="bank" class="form-label">Bank</label>
                        <select class="form-select @error('bank')
                            is-invalid
                        @enderror" id="bank" name="bank">
                            <option selected>Choose a Bank</option>
                            @foreach ($bank as $item)
                                <option value="{{ $item->id }}" @selected($item->id == optional($pembayaran)->bank_id)>
                                    {{ $item->nama_bank }}
                                </option>
                            @endforeach
                        </select>
                        @error('bank')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
                        @if (optional($pembayaran)->bukti_pembayaran)

                                <div class="mb-2">
                                    <img
                                        src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}"
                                        class="img-thumbnail"
                                        style="max-width: 150px;">
                                </div>

                            @endif
                        <input class="form-control @error('bukti_pembayaran')
                            is-invalid
                        @enderror" id="bukti_pembayaran" name="bukti_pembayaran" type="file" accept=".jpg,.jpeg,.png">
                        @error('bukti_pembayaran')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>



                    <button type="submit" class="btn btn-primary">Bayar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
