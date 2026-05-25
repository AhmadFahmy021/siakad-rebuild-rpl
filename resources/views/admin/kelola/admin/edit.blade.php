@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{-- <a href="{{ route('guru.create') }}" class="btn btn-outline-primary btn-sm">Add Guru</a> --}}
                </div>
                <h4 class="page-title">Kelola Admin</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    {{-- <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($siswas as $item)
                                <tr>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->user->email }}</td>
                                    <td>{{ $item->user->username }}</td>
                                    <td>
                                        <a href="{{ url('admin/kelola/siswa/' . $item->id . '/edit') }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                        <a href="{{ url('admin/kelola/siswa/' . $item->id) }}" class="btn btn-outline-danger btn-sm" data-confirm-delete="true">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div> --}}
    <div class="row col-12 ">
        <div class="card">
            <h4 class="card-header">Edit User</h4>
            <div class="card-body">
                <form action="{{ url('admin/kelola/account/admin/' . $admin->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input class="form-control" id="name" disabled type="text" value="{{ $admin->user->name }}" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input class="form-control" id="email" name="email" disabled type="email" value="{{ $admin->user->email }}" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input class="form-control" id="username" name="username" disabled type="text" value="{{ $admin->user->username }}" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="role" class="form-label">Role</label>
                        <input class="form-control @error('role')
                            is-invalid
                        @enderror" id="role" name="role" type="text" value="{{ $admin->role }}">
                        @error('role')
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

    <!-- end row-->
    {{-- <div id="add-siswa-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="add-siswa-modalLabel">Add Siswa</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('admin/kelola/siswa') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name</label>
                            <select class="form-select @error('user')
                                is-invalid
                            @enderror" id="name" name="user">
                                <option selected disabled>Choose a user</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} | {{ $user->email }} | {{ $user->username }}</option>
                                @endforeach
                            </select>
                            @error('user')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Orang Tua</label>
                            <input class="form-control @error('orang_tua')
                                is-invalid
                            @enderror" id="name" name="orang_tua" type="text" placeholder="Masukkan nama orang tua siswa">
                            @error('orang_tua')
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
    </div> --}}



@endsection
