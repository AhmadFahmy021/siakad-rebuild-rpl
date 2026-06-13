@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{-- <a href="{{ route('guru.create') }}" class="btn btn-outline-primary btn-sm">Add Guru</a> --}}
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#add-pembayaran-modal">Add Pembayaran</button>
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
                                <th>Nominal</th>
                                <th>Bukti Pembayaran</th>
                                <th>Tanggal</th>
                                <th>Kelas</th>
                                <th>Semester</th>
                                <th>Tagihan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($pembayarans as $item)
                                <tr>
                                    <td>{{ $item->siswa->user->name }}</td>
                                    <td>{{ $item->nominal }}</td>
                                    <td>
                                        @if ($item->bukti_pembayaran)
                                            <img src="{{ asset('storage/' . $item->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="img-thumbnail" style="max-width: 100px; cursor: pointer;" onclick="Swal.fire({ imageUrl: '{{ asset('storage/' . $item->bukti_pembayaran) }}', imageAlt: 'Bukti Pembayaran', showConfirmButton: false, showCloseButton: true, width: 'auto' })">
                                        @else
                                            <span class="badge bg-danger">Tidak Ada Bukti</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->tanggal->format('d-m-Y') }}</td>
                                    <td>{{ $item->kelas->name }}</td>
                                    <td>{{ $item->semester }}</td>
                                    <td>{{ $item->tagihan->name }}</td>
                                    <td>
                                        @if ($item->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($item->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->bank_id == null)

                                            <a href="{{ url('tu/pembayaran/' . $item->id . '/edit') }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                            {{-- <a href="{{ route('guru.destroy', $item->id) }}" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a> --}}
                                            <a href="{{ url('tu/pembayaran/' . $item->id) }}" class="btn btn-outline-danger btn-sm @if (Auth::user()->id === $item->id)
                                                disabled
                                            @endif" data-confirm-delete="true">Delete</a>
                                        @else
                                            @if ($item->status == 'approved')
                                                <button type="button" class="btn btn-success btn-sm" onclick="Swal.fire('Info', 'Pembayaran ini sudah di-approve.', 'info')">Approved</button>
                                            @else
                                                <form action="{{ route('tu.pembayaran.approve', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-success btn-sm">Approve</button>
                                                </form>
                                                <form action="{{ route('tu.pembayaran.reject', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">Reject</button>
                                                </form>
                                            @endif
                                        @endif
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
    <div id="add-pembayaran-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="add-pembayaran-modalLabel">Add Pembayaran</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('tu/pembayaran') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <div class="form-group mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <select class="form-select @error('kelas')
                                is-invalid
                            @enderror" id="kelas" name="kelas" onchange="changeKelas(this.value); changeTagihan(this.value);">
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
                            <label for="siswa" class="form-label">Siswa</label>
                            <select class="form-select @error('siswa')
                                is-invalid
                            @enderror" id="siswa" name="siswa">
                                <option selected disabled>Choose a siswa</option>
                            </select>
                            @error('siswa')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="tagihan" class="form-label">Tagihan</label>
                            <select class="form-select @error('tagihan')
                                is-invalid
                            @enderror" id="tagihan" name="tagihan" onchange="changeNominal(this.value)">
                                <option selected disabled>Choose a tagihan</option>
                            </select>
                            @error('tagihan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input class="form-control @error('tanggal')
                                is-invalid
                            @enderror" id="tanggal" name="tanggal" type="date">
                            @error('tanggal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="nominal" class="form-label">Nominal</label>
                            <p class="text-black font-15 mb-4" id="nominalDisplay" hidden="true"><span id="nominalValue"></span></p>
                            <input class="form-control @error('nominal')
                                is-invalid
                            @enderror" id="nominal" name="nominal" type="number" placeholder="Masukkan nominal pembayaran">
                            @error('nominal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <input class="form-control @error('semester')
                                is-invalid
                            @enderror" id="semester" name="semester" type="text" placeholder="Masukkan semester pembayaran">
                            @error('semester')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
                            <input class="form-control @error('bukti_pembayaran')
                                is-invalid
                            @enderror" id="bukti_pembayaran" name="bukti_pembayaran" type="file" placeholder="Masukkan bukti pembayaran">
                            @error('bukti_pembayaran')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="statuspembayaran" class="form-label">Status</label>
                            <select class="form-select @error('status')
                                is-invalid
                            @enderror" id="statuspembayaran" name="status">
                                <option selected>Choose a status</option>
                                <option value="approved">Approve | Lunas</option>
                                <option value="pending">Pending | Perlu Diverifikasi</option>
                                <option value="rejected">Reject | Belum Lunas</option>
                            </select>
                            @error('status')
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
@section('js')
<script>
    function changeKelas(kelasId) {
        $('#siswa').html(
            '<option selected disabled>Loading...</option>'
        );

        $.ajax({
            url: '/ajax/pembayaran/siswa/' + kelasId,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                $('#siswa').html(
                    '<option selected disabled>Pilih siswa</option>'
                );
                $.each(response, function (k, v) {

                    $('#siswa').append(`
                        <option value="${v.siswa.id}">
                            ${v.siswa.user.name} |
                            ${v.siswa.user.email}
                        </option>
                    `);

                });

            },
            error: function () {

                alert('Gagal mengambil data siswa');

            }

        });
    }
    function changeTagihan(tagihanId) {
        $('#tagihan').html(
            '<option selected disabled>Loading...</option>'
        );

        $.ajax({
            url: '/ajax/pembayaran/tagihan/' + tagihanId,
            type: 'GET',
            dataType: 'json',
            success: function (response) {

                $('#tagihan').html(
                    '<option selected disabled>Pilih tagihan</option>'
                );
                $.each(response, function (k, v) {

                    $('#tagihan').append(`
                        <option value="${v.id}">
                            ${v.name} | ${v.category}
                        </option>
                    `);

                });

            },
            error: function () {

                alert('Gagal mengambil data tagihan');

            }

        });
    }

    function changeNominal(tagihanId) {

        $("#nominal").val("");
        $.ajax({
            url: '/ajax/pembayaran/tagihan/' + tagihanId,
            type: 'GET',
            dataType: 'json',
            success: function (response) {

                // $('#tagihan').html(
                //     '<option selected disabled>Pilih tagihan</option>'
                // );
                $("#nominal").val(response[0].nominal);
                $("#nominal").attr('readonly', true);
                $("#nominal").attr('hidden', true);

                $("#nominalDisplay").attr('hidden', false);
                // $("#nominalValue").text(response[0].nominal);
                $("#nominalValue").text(
                    new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(response[0].nominal)
                );

            },
            error: function () {

                alert('Gagal mengambil data tagihan');

            }

        });
    }
</script>
@endsection
