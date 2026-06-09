@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{-- <a href="{{ route('guru.create') }}" class="btn btn-outline-primary btn-sm">Add Guru</a> --}}
                </div>
                <h4 class="page-title">Edit Jadwal</h4>
            </div>
        </div>
    </div>
    <div class="row col-12 ">
        <div class="card">
            <h4 class="card-header">Edit Jadwal</h4>
            <div class="card-body">
                <form action="{{ url('tu/jadwal/' . $jadwal->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="kelas" class="form-label">Kelas</label>
                        <select class="form-select @error('kelas')
                            is-invalid
                        @enderror" id="kelas" name="kelas">
                            <option selected disabled>Choose a kelas</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id }}" @selected($jadwal->kelas_id == $k->id)>{{ $k->name }}</option>
                            @endforeach
                        </select>
                        @error('kelas')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="matapelajaran" class="form-label">Mata Pelajaran</label>
                        <select class="form-select @error('matapelajaran')
                            is-invalid
                        @enderror" id="matapelajaran" name="matapelajaran">
                            <option selected disabled>Choose a mata pelajaran</option>
                            @foreach ($matapelajaran as $mp)
                                <option value="{{ $mp->id }}" @selected($jadwal->mata_pelajaran_id == $mp->id)>{{ $mp->nama }}</option>
                            @endforeach
                        </select>
                        @error('matapelajaran')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="guru" class="form-label">Guru</label>
                        <select class="form-select @error('guru')
                            is-invalid
                        @enderror text-capitalize" id="guru" name="guru">
                            <option selected disabled>Choose a guru</option>
                            @foreach ($guru as $gr)
                                <option value="{{ $gr->id }}" @selected($jadwal->guru_id == $gr->id)>{{ $gr->user->name ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                        @error('guru')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="hari" class="form-label">Hari</label>
                        <select class="form-select @error('hari')
                            is-invalid
                        @enderror" id="hari" name="hari">
                            <option selected disabled>Choose a hari</option>
                            <option value="senin" @selected($jadwal->hari == 'senin')>Senin</option>
                            <option value="selasa" @selected($jadwal->hari == 'selasa')>Selasa</option>
                            <option value="rabu" @selected($jadwal->hari == 'rabu')>Rabu</option>
                            <option value="kamis" @selected($jadwal->hari == 'kamis')>Kamis</option>
                            <option value="jumat" @selected($jadwal->hari == 'jumat')>Jumat</option>
                            <option value="sabtu" @selected($jadwal->hari == 'sabtu')>Sabtu</option>
                            <option value="minggu" @selected($jadwal->hari == 'minggu')>Minggu</option>
                        </select>
                        @error('hari')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input class="form-control @error('jam_mulai')
                            is-invalid
                        @enderror" id="jam_mulai" name="jam_mulai" type="time" placeholder="Masukkan jam mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}">
                        @error('jam_mulai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input class="form-control @error('jam_selesai')
                            is-invalid
                        @enderror" id="jam_selesai" name="jam_selesai" type="time" placeholder="Masukkan jam selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}">
                        @error('jam_selesai')
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

