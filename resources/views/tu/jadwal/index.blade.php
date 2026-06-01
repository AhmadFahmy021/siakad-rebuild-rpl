@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#add-jadwal-modal">Add Jadwal</button>
                </div>
                <h4 class="page-title">Jadwal</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Hari</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($jadwal as $item)
                                <tr>
                                    <td>{{ $item->kelas->name ?? 'N/A' }}</td>
                                    <td>{{ $item->matapelajaran->nama ?? 'N/A' }}</td>
                                    <td class="text-capitalize">{{ $item->guru->user->name ?? 'N/A' }}</td>
                                    <td class="text-capitalize">{{ $item->hari }}</td>
                                    <td>{{ $item->jam_mulai }}</td>
                                    <td>{{ $item->jam_selesai }}</td>
                                    <td>
                                        <a href="{{ url('tu/jadwal/' . $item->id . '/edit') }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                        {{-- <a href="{{ route('guru.destroy', $item->id) }}" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a> --}}
                                        <a href="{{ url('tu/jadwal/' . $item->id) }}" class="btn btn-outline-danger btn-sm @if (Auth::user()->id === $item->id)
                                            disabled
                                        @endif" data-confirm-delete="true">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
    <div id="add-jadwal-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="add-jadwal-modalLabel">Add Jadwal</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('tu/jadwal') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        {{-- <div class="form-group mb-3">
                            <label for="name" class="form-label">Nama Mata Pelajaran</label>
                            <input class="form-control @error('name')
                                is-invalid
                            @enderror" id="name" name="name" type="text" placeholder="Masukkan nama mata pelajaran">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div> --}}
                        <div class="form-group mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <select class="form-select @error('kelas')
                                is-invalid
                            @enderror" id="kelas" name="kelas">
                                <option selected disabled>Choose a kelas</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->name }}</option>
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
                                    <option value="{{ $mp->id }}">{{ $mp->nama }}</option>
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
                            @enderror" id="guru" name="guru">
                                <option selected disabled>Choose a guru</option>
                                @foreach ($guru as $gr)
                                    <option value="{{ $gr->id }}" class="text-capitalize">{{ $gr->user->name ?? 'N/A' }}</option>
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
                                <option value="senin">Senin</option>
                                <option value="selasa">Selasa</option>
                                <option value="rabu">Rabu</option>
                                <option value="kamis">Kamis</option>
                                <option value="jumat">Jumat</option>
                                <option value="sabtu">Sabtu</option>
                                <option value="minggu">Minggu</option>
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
                            @enderror" id="jam_mulai" name="jam_mulai" type="time" placeholder="Masukkan jam mulai" value="{{ old('jam_mulai') }}">
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
                            @enderror" id="jam_selesai" name="jam_selesai" type="time" placeholder="Masukkan jam selesai" value="{{ old('jam_selesai') }}">
                            @error('jam_selesai')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>


@endsection
