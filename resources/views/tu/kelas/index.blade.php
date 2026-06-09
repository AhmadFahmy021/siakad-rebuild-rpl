@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{-- <a href="{{ route('guru.create') }}" class="btn btn-outline-primary btn-sm">Add Guru</a> --}}
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#add-kelas-modal">Add Kelas</button>
                </div>
                <h4 class="page-title">Kelas</h4>
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
                                <th>Guru Wali</th>
                                <th>Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($kelas as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->guru->user->name }}</td>
                                    <td>
                                        <a href="{{ url('tu/kelas/' . $item->id . '/edit') }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                        <a href="{{ url('tu/kelas/' . $item->id . '/kelola') }}" class="btn btn-outline-success btn-sm">Kelola</a>
                                        <a href="{{ url('tu/kelas/' . $item->id) }}" class="btn btn-outline-danger btn-sm" data-confirm-delete2="true" data-name="{{ $item->name }}">Delete</a>
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
    <div id="add-kelas-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="add-pembayaran-modalLabel">Add Kelas</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('tu/kelas') }}" method="POST" >
                    @csrf
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <div class="form-group mb-3">
                            <label for="kelas" class="form-label">Nama kelas</label>
                            <input class="form-control @error('kelas')
                                is-invalid
                            @enderror" id="kelas" name="kelas" type="text" placeholder="Enter kelas name" value="{{ old('kelas') }}">
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
                                    <option value="{{ $item->id }}" {{ old('guru') == $item->id ? 'selected' : '' }}>
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
