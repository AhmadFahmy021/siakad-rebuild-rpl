@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{-- <a href="{{ route('guru.create') }}" class="btn btn-outline-primary btn-sm">Add Guru</a> --}}
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#add-user-modal">Add Tagihan</button>
                </div>
                <h4 class="page-title">Tagihan </h4>
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
                                <th>Nama Tagihan</th>
                                <th>Deskripsi</th>
                                <th>Kategori</th>
                                <th>Kelas</th>
                                <th>Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($tagihans as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->deskripsi }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td class="fw-bold">{{ $item->kelas_id ? $item->kelas->name : 'Semua Kelas' }}</td>
                                    {{-- <td>{{ $item->semua_kelas ? '✅' : '❌' }}</td> --}}
                                    <td>
                                        <a href="{{ url('admin/tagihan/' . $item->id . '/edit') }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                        {{-- <a href="{{ route('guru.destroy', $item->id) }}" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a> --}}
                                        <a href="{{ url('admin/tagihan/' . $item->id) }}" class="btn btn-outline-danger btn-sm " data-confirm-delete="true">Delete</a>
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
    <div id="add-user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="add-user-modalLabel">Add Tagihan</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('admin/tagihan') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="nama_bank" class="form-label">Nama Tagihan</label>
                            <input class="form-control @error('name')
                                is-invalid
                            @enderror" id="nama_bank" name="name" type="text" placeholder="Masukkan nama tagihan">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama_bank" class="form-label">Kategori</label>
                            <input class="form-control @error('category')
                                is-invalid
                            @enderror" id="nama_bank" name="category" type="text" placeholder="Masukkan kategori">
                            @error('category')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="nomor_rekening" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi')
                                is-invalid
                            @enderror" id="nomor_rekening" name="deskripsi" placeholder="Masukkan deskripsi"></textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Kelas</label>
                            <select class="form-select @error('user')
                                is-invalid
                            @enderror" id="name" name="user">
                                <option selected>Choose a Kelas (jangan pilih kelas apapun jika tagihan digunakan untuk semua kelas)</option>
                                @foreach ($kelas as $kelas)
                                    <option value="{{ $kelas->id }}"> Guru :  {{ $kelas->name }} | {{ $kelas->guru->user->name }}</option>
                                @endforeach
                            </select>
                            @error('user')
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
            </div>
        </div>
    </div>



@endsection
