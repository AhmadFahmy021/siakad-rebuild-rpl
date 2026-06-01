@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{-- <a href="{{ route('guru.create') }}" class="btn btn-outline-primary btn-sm">Add Guru</a> --}}
                </div>
                <h4 class="page-title">Edit Kelas</h4>
            </div>
        </div>
    </div>
    <div class="row col-12 ">
        <div class="card">
            <h4 class="card-header">Edit Kelas</h4>
            <div class="card-body">
                <form action="{{ url('tu/kelas/' . $kelas->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="kelas" class="form-label">Nama Kelas</label>
                        <input class="form-control @error('kelas')
                            is-invalid
                        @enderror" id="kelas" name="kelas" type="text" placeholder="Masukkan nama kelas" value="{{ old('kelas', $kelas->name) }}">
                        @error('kelas')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="guru" class="form-label">Guru Wali</label>
                        <select class="form-select @error('guru')
                            is-invalid
                        @enderror" id="guru" name="guru">
                            <option selected disabled>Choose a Guru Wali</option>
                            @foreach ($guru as $item)
                                <option value="{{ $item->id }}" @selected($kelas->guru_id == $item->id)>
                                    {{ $item->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('guru')
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
