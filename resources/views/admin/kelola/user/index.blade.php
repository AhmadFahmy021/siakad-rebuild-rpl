@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{-- <a href="{{ route('guru.create') }}" class="btn btn-outline-primary btn-sm">Add Guru</a> --}}
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#add-user-modal">Add Users</button>
                </div>
                <h4 class="page-title">Kelola Users</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- <h4 class="header-title">Basic Data Table</h4>
                    <p class="text-muted font-13 mb-4">
                        DataTables has most features enabled by default, so all you need to do to use it with your own tables is to call the construction
                        function:
                        <code>$().DataTable();</code>.
                    </p> --}}

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                {{-- <th>Orang Tua</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($users as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->username }}</td>
                                    {{-- <td>{{ $item->nama_ortu }}</td> --}}
                                    <td>
                                        <a href="{{ url('admin/kelola/user/' . $item->id . '/edit') }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                        {{-- <a href="{{ route('guru.destroy', $item->id) }}" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a> --}}
                                        <a href="{{ url('admin/kelola/user/' . $item->id) }}" class="btn btn-outline-danger btn-sm" data-confirm-delete="true">Delete</a>
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
                    <h4 class="modal-title" id="add-user-modalLabel">Add User</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('admin/kelola/user') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        {{-- <h6>Text in a modal</h6>
                        <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
                        <hr>
                        <h6>Overflowing text to show scroll behavior</h6>
                        <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                        <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p> --}}
                        {{-- <div class="form-group mb-3">
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
                        </div> --}}
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input class="form-control @error('name')
                                is-invalid
                            @enderror" id="name" name="name" type="text" placeholder="Masukkan nama user">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input class="form-control @error('email')
                                is-invalid
                            @enderror" id="email" name="email" type="email" placeholder="Masukkan email user">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input class="form-control @error('username')
                                is-invalid
                            @enderror" id="username" name="username" type="text" placeholder="Masukkan username user">
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @php
                            use Illuminate\Support\Str;
                        @endphp
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input class="form-control @error('password')
                                is-invalid
                            @enderror" id="password" name="password" type="text" value="{{ Str::random(8) }}" placeholder="Masukkan password user" disabled>
                            @error('password')
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
