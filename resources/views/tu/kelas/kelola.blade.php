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
                    <div class="mb-3">
                        <label class="form-label">Pilih Siswa</label>

                        @foreach ($siswa as $item)
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="siswa[]"
                                    value="{{ $item->id }}"
                                    id="siswa{{ $item->id }}"
                                    @checked(in_array($item->id, $siswaKelas->pluck('siswa_id')->toArray()))
                                >

                                <label
                                    class="form-check-label"
                                    for="siswa{{ $item->id }}"
                                >
                                    {{ $item->user->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
