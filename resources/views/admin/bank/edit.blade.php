@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{-- <a href="{{ route('guru.create') }}" class="btn btn-outline-primary btn-sm">Add Guru</a> --}}
                </div>
                <h4 class="page-title">Edit Bank</h4>
            </div>
        </div>
    </div>
    <div class="row col-12 ">
        <div class="card">
            <h4 class="card-header">Edit Bank</h4>
            <div class="card-body">
                <form action="{{ url('admin/bank/' . $bank->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="nama_bank" class="form-label">Nama Bank</label>
                        <input class="form-control @error('nama_bank')
                            is-invalid
                        @enderror" id="nama_bank" name="nama_bank" type="text" placeholder="Masukkan nama bank" value="{{ old('nama_bank', $bank->nama_bank) }}">
                        @error('nama_bank')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="nomor_rekening" class="form-label">Nomor Rekening</label>
                        <input class="form-control @error('nomor_rekening')
                            is-invalid
                        @enderror" id="nomor_rekening" name="nomor_rekening" type="text" placeholder="Masukkan nomor rekening" value="{{ old('nomor_rekening', $bank->nomor_rekening) }}">
                        @error('nomor_rekening')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="nama_pemilik" class="form-label">Nama Pemilik</label>
                        <input class="form-control @error('nama_pemilik')
                            is-invalid
                        @enderror" id="nama_pemilik" name="nama_pemilik" type="text" placeholder="Masukkan nama pemilik" value="{{ old('nama_pemilik', $bank->nama_pemilik) }}">
                        @error('nama_pemilik')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
