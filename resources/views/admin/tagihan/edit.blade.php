@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{-- <a href="{{ route('guru.create') }}" class="btn btn-outline-primary btn-sm">Add Guru</a> --}}
                </div>
                <h4 class="page-title">Edit Tagihan</h4>
            </div>
        </div>
    </div>
    <div class="row col-12 ">
        <div class="card">
            <h4 class="card-header">Edit Tagihan</h4>
            <div class="card-body">
                <form action="{{ url('admin/tagihan/' . $tagihan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Nama Tagihan</label>
                        <input class="form-control @error('name')
                            is-invalid
                        @enderror" id="name" name="name" type="text" placeholder="Masukkan nama tagihan" value="{{ old('name', $tagihan->name) }}">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi')
                            is-invalid
                        @enderror" id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi">{{ old('deskripsi', $tagihan->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="category" class="form-label">Kategori</label>
                        <input class="form-control @error('category')
                            is-invalid
                        @enderror" id="category" name="category" type="text" placeholder="Masukkan kategori" value="{{ old('category', $tagihan->category) }}">
                        @error('category')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="nominal" class="form-label">Nominal</label>
                        <input class="form-control @error('nominal')
                            is-invalid
                        @enderror" id="nominal" name="nominal" type="number" placeholder="Masukkan nominal" value="{{ old('nominal', $tagihan->nominal) }}">
                        @error('nominal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="user" class="form-label">Kelas</label>
                        <select class="form-select @error('user')
                            is-invalid
                        @enderror" id="user" name="user">
                            <option selected value="">Choose a Kelas (jangan pilih kelas apapun jika tagihan digunakan untuk semua kelas)</option>
                            @foreach ($kelas as $kelas)
                                <option value="{{ $kelas->id }}" @selected($tagihan->kelas_id == $kelas->id)> Guru :  {{ $kelas->name }} | {{ $kelas->guru->user->name }}</option>
                            @endforeach
                        </select>
                        @error('user')
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
