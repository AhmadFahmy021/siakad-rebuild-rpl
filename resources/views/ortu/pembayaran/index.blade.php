@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                </div>
                <h4 class="page-title">Pembayaran</h4>
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
                                <th>Name</th>
                                <th>Deskripsi</th>
                                <th>Category</th>
                                <th>Kelas</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($tagihan as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td style="max-width: 350px; white-space: normal;">{{ $item->deskripsi }}</td>
                                    {{-- <td>{!! wordwrap($item->deskripsi, 50, '<br>', true) !!}</td> --}}
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->kelas ? $item->kelas->name : 'Umum' }}</td>
                                    <td>
                                        @if (optional($pembayaran->get($item->id))->status === 'approved')
                                            <span class="badge bg-success badge-lg">Lunas</span>
                                        @elseif (optional($pembayaran->get($item->id))->status === 'pending')
                                            <span class="badge bg-primary badge-lg">Pending | Menunggu Konfirmasi Admin</span>
                                        @else
                                            <span class="badge bg-warning badge-lg">Belum Lunas</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('ortu/pembayaran/' . $item->id . '/bayar') }}" class="btn btn-outline-primary btn-sm">Bayar</a>
                                        {{-- <a href="{{ route('guru.destroy', $item->id) }}" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a> --}}
                                        {{-- <a href="{{ url('ortu/pembayaran/' . $item->id) }}" class="btn btn-outline-danger btn-sm @if (Auth::user()->id === $item->id)
                                            disabled
                                        @endif" data-confirm-delete="true">Delete</a> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
@endsection
