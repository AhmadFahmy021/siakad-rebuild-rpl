@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{-- <a href="{{ route('guru.create') }}" class="btn btn-outline-primary btn-sm">Add Guru</a> --}}
                </div>
                <h4 class="page-title">Edit Pembayaran</h4>
            </div>
        </div>
    </div>
    <div class="row col-12 ">
        <div class="card">
            <h4 class="card-header">Edit Pembayaran</h4>
            <div class="card-body">
                <form action="{{ url('admin/pembayaran/' . $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')


                        {{-- KELAS --}}
                        <div class="form-group mb-3">
                            <label for="kelas" class="form-label">Kelas</label>

                            <select
                                class="form-select @error('kelas') is-invalid @enderror"
                                id="kelas"
                                name="kelas"
                                onchange="changeKelas(this.value); changeTagihan(this.value);">

                                <option value="">Choose a kelas</option>

                                @foreach ($kelas as $k)

                                    <option value="{{ $k->id }}"
                                        {{ old('kelas', $pembayaran->kelas_id) == $k->id ? 'selected' : '' }}>

                                        {{ $k->name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('kelas')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- SISWA --}}
                        <div class="form-group mb-3">
                            <label for="siswa" class="form-label">Siswa</label>

                            <select
                                class="form-select @error('siswa') is-invalid @enderror"
                                id="siswa"
                                name="siswa">

                                <option
                                    value="{{ $pembayaran->siswa->id }}"
                                    selected>

                                    {{ $pembayaran->siswa->user->name }}
                                    |
                                    {{ $pembayaran->siswa->user->email }}

                                </option>

                            </select>

                            @error('siswa')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- TANGGAL --}}
                        <div class="form-group mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>

                            <input
                                class="form-control @error('tanggal') is-invalid @enderror"
                                id="tanggal"
                                name="tanggal"
                                type="date"
                                value="{{ old('tanggal', $pembayaran->tanggal?->format('Y-m-d')) }}">

                            @error('tanggal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- NOMINAL --}}
                        <div class="form-group mb-3">
                            <label for="nominal" class="form-label">Nominal</label>

                            <input
                                class="form-control @error('nominal') is-invalid @enderror"
                                id="nominal"
                                name="nominal"
                                type="number"
                                value="{{ old('nominal', $pembayaran->nominal) }}"
                                placeholder="Masukkan nominal pembayaran">

                            @error('nominal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- SEMESTER --}}
                        <div class="form-group mb-3">
                            <label for="semester" class="form-label">Semester</label>

                            <input
                                class="form-control @error('semester') is-invalid @enderror"
                                id="semester"
                                name="semester"
                                type="text"
                                value="{{ old('semester', $pembayaran->semester) }}"
                                placeholder="Masukkan semester pembayaran">

                            @error('semester')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- TAGIHAN --}}
                        <div class="form-group mb-3">
                            <label for="tagihan" class="form-label">Tagihan</label>

                            <select
                                class="form-select @error('tagihan') is-invalid @enderror"
                                id="tagihan"
                                name="tagihan">

                                <option
                                    value="{{ $pembayaran->tagihan->id }}"
                                    selected>

                                    {{ $pembayaran->tagihan->name }}
                                    |
                                    {{ $pembayaran->tagihan->category }}

                                </option>

                            </select>

                            @error('tagihan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- BUKTI PEMBAYARAN --}}
                        <div class="form-group mb-3">

                            <label for="bukti_pembayaran" class="form-label">
                                Bukti Pembayaran
                            </label>

                            @if ($pembayaran->bukti_pembayaran)

                                <div class="mb-2">
                                    <img
                                        src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}"
                                        class="img-thumbnail"
                                        style="max-width: 150px;">
                                </div>

                            @endif

                            <input
                                class="form-control @error('bukti_pembayaran') is-invalid @enderror"
                                id="bukti_pembayaran"
                                name="bukti_pembayaran"
                                type="file">

                            @error('bukti_pembayaran')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- STATUS --}}
                        <div class="form-group mb-3">

                            <label for="statuspembayaran" class="form-label">
                                Status
                            </label>

                            <select
                                class="form-select @error('status') is-invalid @enderror"
                                id="statuspembayaran"
                                name="status">

                                <option disabled>
                                    Choose a status
                                </option>

                                <option value="approved"
                                    {{ old('status', $pembayaran->status) == 'approved' ? 'selected' : '' }}>
                                    Approve | Lunas
                                </option>

                                <option value="pending"
                                    {{ old('status', $pembayaran->status) == 'pending' ? 'selected' : '' }}>
                                    Pending | Perlu Diverifikasi
                                </option>

                                <option value="rejected"
                                    {{ old('status', $pembayaran->status) == 'rejected' ? 'selected' : '' }}>
                                    Reject | Belum Lunas
                                </option>

                            </select>

                            @error('status')
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
@section('js')
<script>
    function changeKelas(kelasId) {
        // alert(kelasId);
        if (kelasId == ''){
            $('#siswa').empty();
            $('#siswa').html(
                '<option selected disabled>Pilih siswa</option>'
            );

            return;
        }
        $('#siswa').empty();
        $('#siswa').html(
            '<option selected disabled>Loading...</option>'
        );

        $.ajax({
            url: '/admin/ajax/pembayaran/siswa/' + kelasId,
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
        if (tagihanId == ''){
            $('#tagihan').empty();
            $('#tagihan').html(
                '<option selected disabled>Pilih tagihan</option>'
            );

            return;
        }
        $('#tagihan').empty();
        $('#tagihan').html(
            '<option selected disabled>Loading...</option>'
        );

        $.ajax({
            url: '/admin/ajax/pembayaran/tagihan/' + tagihanId,
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
</script>
@endsection

