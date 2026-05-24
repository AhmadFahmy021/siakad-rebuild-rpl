@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{-- <a href="{{ route('guru.create') }}" class="btn btn-outline-primary btn-sm">Add Guru</a> --}}
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#add-siswa-modal">Add Siswa</button>
                </div>
                <h4 class="page-title">Kelola Siswa</h4>
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
                                <th>Orang Tua</th>
                                <th>Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($siswas as $item)
                                <tr>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->user->email }}</td>
                                    <td>{{ $item->user->username }}</td>
                                    <td>{{ $item->nama_ortu }}</td>
                                    <td>
                                        <a href="{{ url('admin/kelola/siswa/' . $item->id . '/edit') }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                        {{-- <a href="{{ route('guru.destroy', $item->id) }}" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a> --}}
                                        <a href="{{ url('admin/kelola/siswa/' . $item->id) }}" class="btn btn-outline-danger btn-sm" data-confirm-delete="true">Delete</a>
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
    <div id="add-siswa-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="add-siswa-modalLabel">Add Siswa</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('admin/kelola/siswa') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        {{-- <h6>Text in a modal</h6>
                        <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
                        <hr>
                        <h6>Overflowing text to show scroll behavior</h6>
                        <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                        <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p> --}}
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
    </div>



@endsection
