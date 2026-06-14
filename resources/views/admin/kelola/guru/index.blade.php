@extends('layouts.main')
@section('main')

<!-- ═══════════════════════════════════════════════════
     PAGE TITLE
════════════════════════════════════════════════════ -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <button type="button"
                        class="btn btn-outline-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#add-guru-modal">
                    <i class="mdi mdi-plus me-1"></i> Add Guru
                </button>
            </div>
            <h4 class="page-title">Kelola Guru</h4>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════
     TABEL DAFTAR GURU
════════════════════════════════════════════════════ -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gurus as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->user->email }}</td>
                                <td>{{ $item->user->username }}</td>
                                <td class="text-center">

                                    {{-- Tombol Edit → trigger AJAX fetch data guru --}}
                                    <button type="button"
                                            class="btn btn-outline-primary btn-sm me-1"
                                            onclick="editGuru('{{ $item->id }}',
                                                             '{{ $item->user->name }}',
                                                             '{{ $item->user->email }}',
                                                             '{{ $item->user_id }}')">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </button>

                                    {{-- Tombol Delete → SweetAlert confirm --}}
                                    <a href="{{ url('admin/kelola/guru/' . $item->id) }}"
                                       class="btn btn-outline-danger btn-sm"
                                       data-confirm-delete="true">
                                        <i class="mdi mdi-delete"></i> Delete
                                    </a>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada guru terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div><!-- end row -->


<!-- ═══════════════════════════════════════════════════
     MODAL: ADD GURU
     Tidak diubah dari versi asli
════════════════════════════════════════════════════ -->
<div id="add-guru-modal" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="add-guru-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="add-guru-modalLabel">Add Guru</h4>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('admin/kelola/guru') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Pilih User</label>
                        <select class="form-select @error('user') is-invalid @enderror"
                                id="name" name="user">
                            <option selected disabled>Choose a user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} | {{ $user->email }} | {{ $user->username }}
                                </option>
                            @endforeach
                        </select>
                        @error('user')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════
     MODAL: EDIT GURU
     Baru — konsisten dengan style modal Add di atas
════════════════════════════════════════════════════ -->
<div id="edit-guru-modal" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="edit-guru-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="edit-guru-modalLabel">Edit Guru</h4>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Action & method diisi dinamis oleh JS saat tombol Edit diklik --}}
            <form id="edit-guru-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    {{-- Info guru yang sedang diedit --}}
                    <div class="alert alert-info d-flex align-items-center gap-2 mb-3">
                        <i class="mdi mdi-account-circle fs-4"></i>
                        <div>
                            Mengedit akun:
                            <strong id="edit-guru-name">-</strong>
                            <span class="text-muted" id="edit-guru-email"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ganti User <span class="text-danger">*</span></label>
                        <select class="form-select" name="user" id="edit-user-select" required>
                            <option selected disabled>Pilih user pengganti</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}">
                                    {{ $user->name }} | {{ $user->email }} | {{ $user->username }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save me-1"></i> Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════
     SCRIPT
     editGuru() dipanggil dari tombol Edit di tabel
     → isi modal dengan data guru yang dipilih
     → set form action ke route update
════════════════════════════════════════════════════ -->
<script>
    /**
     * Fungsi editGuru()
     *
     * Cara kerja:
     * 1. Terima parameter dari tombol Edit (id, name, email, userId)
     * 2. Tampilkan info guru di alert dalam modal
     * 3. Pre-select user yang sedang terhubung di dropdown
     * 4. Set action form ke URL update guru ini
     * 5. Buka modal
     *
     * Kenapa tidak pakai AJAX fetch?
     * Data sudah tersedia dari loop foreach di atas (dikirim lewat parameter onclick)
     * → lebih cepat, tidak perlu request tambahan ke server
     */
    function editGuru(guruId, namaGuru, emailGuru, currentUserId) {
        // 1. Isi info guru di dalam modal
        document.getElementById('edit-guru-name').textContent  = namaGuru;
        document.getElementById('edit-guru-email').textContent = ' (' + emailGuru + ')';

        // 2. Pre-select user yang sedang aktif di dropdown
        const select = document.getElementById('edit-user-select');
        for (let option of select.options) {
            if (option.value === currentUserId) {
                option.selected = true;
                break;
            }
        }

        // 3. Set action form ke URL update guru yang diklik
        document.getElementById('edit-guru-form').action =
            '/admin/kelola/guru/' + guruId;

        // 4. Buka modal
        new bootstrap.Modal(document.getElementById('edit-guru-modal')).show();
    }
</script>

@endsection
