@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{-- <a href="{{ route('guru.create') }}" class="btn btn-outline-primary btn-sm">Add Guru</a> --}}
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#add-bank-modal">Add Bank</button>
                </div>
                <h4 class="page-title">Bank </h4>
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
                                <th>Nama Bank</th>
                                <th>Nomor Rekening</th>
                                <th>Nama Pemilik</th>
                                {{-- <th>Orang Tua</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($banks as $item)
                                <tr>
                                    <td>{{ $item->nama_bank }}</td>
                                    <td>{{ $item->nomor_rekening }}</td>
                                    <td>{{ $item->nama_pemilik }}</td>
                                    {{-- <td>{{ $item->nama_ortu }}</td> --}}
                                    <td>
                                        <a href="{{ url('admin/bank/' . $item->id . '/edit') }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                        {{-- <a href="{{ route('guru.destroy', $item->id) }}" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a> --}}
                                        <a href="{{ url('admin/bank/' . $item->id) }}" class="btn btn-outline-danger btn-sm " data-confirm-delete="true">Delete</a>
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
    <div id="add-bank-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="add-user-modalLabel">Add User</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('admin/bank') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="nama_bank" class="form-label">Nama Bank</label>
                            <input class="form-control @error('nama_bank')
                                is-invalid
                            @enderror" id="nama_bank" name="nama_bank" type="text" placeholder="Masukkan nama bank">
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
                            @enderror" id="nomor_rekening" name="nomor_rekening" type="text" placeholder="Masukkan nomor rekening">
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
                            @enderror" id="nama_pemilik" name="nama_pemilik" type="text" placeholder="Masukkan nama pemilik">
                            @error('nama_pemilik')
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
