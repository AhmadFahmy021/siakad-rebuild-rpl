@extends('layouts.main')
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{-- <a href="{{ route('guru.create') }}" class="btn btn-outline-primary btn-sm">Add Guru</a> --}}
                </div>
                <h4 class="page-title">Edit Kelas</h4>
            </div>
        </div>
    </div>
    <div class="row col-12 ">
        <div class="card">
            <h4 class="card-header">Edit Kelas</h4>
            <div class="card-body">
                <form action="{{ url('tu/kelas/' . $kelas->id . '/kelola') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label d-block mb-3">Pilih Siswa (<span id="selected-count" class="fw-bold text-primary">0</span> terpilih)</label>
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 row-cols-lg-10 g-3">
                            @foreach ($siswa as $item)
                                <div class="col">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="siswa[]"
                                            value="{{ $item->id }}"
                                            id="siswa{{ $item->id }}"
                                            @checked(in_array($item->id, $siswaKelas))
                                        >

                                        <label
                                            class="form-check-label text-truncate"
                                            for="siswa{{ $item->id }}"
                                            title="{{ $item->user->name }}"
                                        >
                                            {{ $item->user->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="siswa[]"]');
            const countSpan = document.getElementById('selected-count');

            function updateCount() {
                const checkedCount = document.querySelectorAll('input[name="siswa[]"]:checked').length;
                countSpan.textContent = checkedCount;
            }

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateCount);
            });

            updateCount();
        });
    </script>
@endsection
