@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{-- <a href="{{ route('guru.create') }}" class="btn btn-outline-primary btn-sm">Add Guru</a> --}}
                </div>
                <h4 class="page-title">Edit Mata Pelajaran</h4>
            </div>
        </div>
    </div>
    <div class="row col-12 ">
        <div class="card">
            <h4 class="card-header">Edit Mata Pelajaran</h4>
            <div class="card-body">
                <form action="{{ url('tu/matapelajaran/' . $mataPelajaran->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Nama Mata Pelajaran</label>
                        <input class="form-control @error('name')
                            is-invalid
                        @enderror" id="name" name="name" type="text" placeholder="Masukkan nama mata pelajaran" value="{{ old('name', $mataPelajaran->nama) }}">
                        @error('name')
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

