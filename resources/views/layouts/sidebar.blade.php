<div class="app-menu">

    <!-- Brand Logo -->
    <div class="logo-box">
        <!-- Brand Logo Light -->
        <a href="index.html" class="logo-light">
            {{-- <img src="{{ asset('assets') }}/images/logo-dark-3.png" width="50%" alt="logo" class="logo-lg"> --}}
            <img src="{{ asset('assets') }}/images/logo-dark-3.png" style="width: 60px; height: auto;" alt="dark logo" class="logo-lg">
            <img src="{{ asset('assets') }}/images/logo-dark-3.png" style="width: 50px; height: auto;" alt="small logo" class="logo-sm">
            {{-- <img src="{{ asset('assets') }}/images/logo-dark-3.png" alt="small logo" class="logo-sm"> --}}
        </a>

        <!-- Brand Logo Dark -->
        <a href="index.html" class="logo-dark">
            <img src="{{ asset('assets') }}/images/logo-dark-3.png" style="width: 60px; height: auto;" alt="dark logo" class="logo-lg">
            <img src="{{ asset('assets') }}/images/logo-dark-3.png" style="width: 50px; height: auto;" alt="small logo" class="logo-sm">
        </a>
    </div>

    <!-- menu-left -->
    <div class="scrollbar">

        <!-- User box -->
        <div class="user-box text-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center avatar-md mx-auto" style="background-color: #eef2f7;"><i class="mdi mdi-account" style="font-size: 32px; color: #8a9ab0; line-height: 1;"></i></div>
            <div class="dropdown">
                <a href="javascript: void(0);" class="dropdown-toggle h5 mb-1 d-block" data-bs-toggle="dropdown">Geneva Kennedy</a>
                <div class="dropdown-menu user-pro-dropdown">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings me-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <!-- item-->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item notify-item">
                            <i class="fe-log-out me-1"></i>
                            <span>Logout</span>
                        </button>
                    </form>

                </div>
            </div>
            <p class="text-muted mb-0">Admin Head</p>
        </div>
        {{-- @php
        use App\Models\Admin;
        @endphp --}}
        <!--- Menu -->
        <ul class="menu">

            {{-- ADMIN --}}
            @if(Request::is('admin/*'))


                {{-- <li class="menu-title">Admin</li> --}}

                <li class="menu-item {{ Request::is('admin/dashboard') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('admin/dashboard') }}" class="menu-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                        {{-- <span class="menu-icon"><i data-feather="calendar"></i></span> --}}
                        <span class="menu-icon"><i data-feather="airplay"></i></span>
                        <span class="menu-text"> Dashboard </span>
                    </a>
                </li>

                <li class="menu-title">Management Sekolah</li>

                <li class="menu-item {{ Request::is('admin/kelola/guru/*') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('admin/kelola/guru') }}" class="menu-link {{ Request::is('admin/kelola/guru') || Request::is('admin/kelola/guru/*') ? 'active' : '' }}">
                        {{-- <span class="menu-icon"><i data-feather="calendar"></i></span> --}}
                        <span class="menu-icon"><i data-feather="users"></i></span>
                        <span class="menu-text"> Kelola Guru </span>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('admin/kelola/tu/*') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('admin/kelola/tu') }}" class="menu-link {{ Request::is('admin/kelola/tu') || Request::is('admin/kelola/tu/*') ? 'active' : '' }}">
                        {{-- <span class="menu-icon"><i data-feather="calendar"></i></span> --}}
                        <span class="menu-icon"><i data-feather="users"></i></span>
                        <span class="menu-text"> Kelola TU </span>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('admin/kelola/siswa/*') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('admin/kelola/siswa') }}" class="menu-link {{ Request::is('admin/kelola/siswa') || Request::is('admin/kelola/siswa/*') ? 'active' : '' }}">
                        {{-- <span class="menu-icon"><i data-feather="calendar"></i></span> --}}
                        <span class="menu-icon"><i data-feather="users"></i></span>
                        <span class="menu-text"> Kelola Siswa </span>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('admin/kelola/user/*') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('admin/kelola/user') }}" class="menu-link {{ Request::is('admin/kelola/user') || Request::is('admin/kelola/user/*') ? 'active' : '' }}">
                        {{-- <span class="menu-icon"><i data-feather="calendar"></i></span> --}}
                        <span class="menu-icon"><i data-feather="users"></i></span>
                        <span class="menu-text"> Kelola User </span>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('admin/kelola/account/admin/*') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('admin/kelola/account/admin') }}" class="menu-link {{ Request::is('admin/kelola/account/admin') || Request::is('admin/kelola/account/admin/*') ? 'active' : '' }}">
                        {{-- <span class="menu-icon"><i data-feather="calendar"></i></span> --}}
                        <span class="menu-icon"><i data-feather="users"></i></span>
                        <span class="menu-text"> Kelola Admin </span>
                    </a>
                </li>

                <li class="menu-title">Bank / Pembayaran</li>

                <li class="menu-item {{ Request::is('admin/tagihan/*') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('admin/tagihan') }}" class="menu-link {{ Request::is('admin/tagihan') || Request::is('admin/tagihan/*') ? 'active' : '' }}">
                        {{-- <span class="menu-icon"><i data-feather="calendar"></i></span> --}}
                        <span class="menu-icon"><i data-feather="dollar-sign"></i></span>
                        <span class="menu-text"> Tagihan </span>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('admin/bank/*') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('admin/bank') }}" class="menu-link {{ Request::is('admin/bank') || Request::is('admin/bank/*') ? 'active' : '' }}">
                        {{-- <span class="menu-icon"><i data-feather="calendar"></i></span> --}}
                        <span class="menu-icon"><i data-feather="dollar-sign"></i></span>
                        <span class="menu-text"> Bank </span>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('admin/pembayaran/*') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('admin/pembayaran') }}" class="menu-link {{ Request::is('admin/pembayaran') || Request::is('admin/pembayaran/*') ? 'active' : '' }}">
                        {{-- <span class="menu-icon"><i data-feather="calendar"></i></span> --}}
                        <span class="menu-icon"><i data-feather="dollar-sign"></i></span>
                        <span class="menu-text"> Pembayaran </span>
                    </a>
                </li>

            @endif


            {{-- GURU --}}
            @if(Request::is('guru/*'))
                @php
                    $isWaliKelas = false;
                    $guruModel = \App\Models\Guru::where('user_id', Auth::id())->first();
                    if ($guruModel) {
                        $isWaliKelas = \App\Models\Kelas::where('guru_id', $guruModel->id)->exists();
                    }
                @endphp

                <li class="menu-item {{ Request::is('guru/dashboard') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('guru/dashboard') }}" class="menu-link">
                        <span class="menu-icon">
                            <i data-feather="airplay"></i>
                        </span>
                        <span class="menu-text">Dashboard Guru</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('guru/assignment*') ? 'menuitem-active' : '' }}">
                    <a href="{{ route('assignment.index') }}" class="menu-link {{ Request::is('guru/assignment*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i data-feather="check-square"></i>
                        </span>
                        <span class="menu-text">Tugas / Assignment</span>
                    </a>
                </li>

                @if($isWaliKelas)
                    <li class="menu-item {{ Request::is('guru/walas*') ? 'menuitem-active' : '' }}">
                        <a href="{{ url('guru/walas') }}" class="menu-link {{ Request::is('guru/walas*') ? 'active' : '' }}">
                            <span class="menu-icon">
                                <i data-feather="users"></i>
                            </span>
                            <span class="menu-text">Wali Kelas</span>
                        </a>
                    </li>
                @endif

            @endif


            {{-- SISWA --}}
            @if(Request::is('siswa/*'))

                <li class="menu-item {{ Request::is('siswa/dashboard') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('siswa/dashboard') }}" class="menu-link {{ Request::is('siswa/dashboard') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i data-feather="grid"></i>
                        </span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('siswa/tugas') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('siswa/tugas') }}" class="menu-link {{ Request::is('siswa/tugas') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i data-feather="check-square"></i>
                        </span>
                        <span class="menu-text">Tugas</span>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('siswa/konsultasi') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('siswa/konsultasi') }}" class="menu-link {{ Request::is('siswa/konsultasi') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i data-feather="mail"></i>
                        </span>
                        <span class="menu-text">Konsultasi</span>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('siswa/nilai') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('siswa/nilai') }}" class="menu-link {{ Request::is('siswa/nilai') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i data-feather="star"></i>
                        </span>
                        <span class="menu-text">Nilai</span>
                    </a>
                </li>

            @endif


            {{-- TU --}}
            @if(Request::is('tu/*'))

                <li class="menu-item {{ Request::is('tu/dashboard') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('tu/dashboard') }}" class="menu-link {{ Request::is('tu/dashboard') ? 'active' : '' }}">
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('tu/pembayaran/*') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('tu/pembayaran') }}" class="menu-link {{ Request::is('tu/pembayaran') ? 'active' : '' }}">
                        <span class="menu-text">Pembayaran</span>
                    </a>
                </li>
                {{-- <li class="menu-item">
                    <a href="#menuDashboards" data-bs-toggle="collapse" class="menu-link">
                        <span class="menu-text"> Kelas </span>
                    </a>
                    <div class="collapse" id="menuDashboards">
                        <ul class="sub-menu">
                            <li class="menu-item">
                                <a href="index.html" class="menu-link">
                                    <span class="menu-text">Kelola Kelas</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="dashboard-2.html" class="menu-link">
                                    <span class="menu-text"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> --}}
                <li class="menu-item {{ Request::is('tu/kelas/*') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('tu/kelas') }}" class="menu-link {{ Request::is('tu/kelas') ? 'active' : '' }}">
                        <span class="menu-text">Kelas</span>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('tu/matapelajaran/*') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('tu/matapelajaran') }}" class="menu-link {{ Request::is('tu/matapelajaran') ? 'active' : '' }}">
                        <span class="menu-text">Mata Pelajaran</span>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('tu/jadwal/*') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('tu/jadwal') }}" class="menu-link {{ Request::is('tu/jadwal') ? 'active' : '' }}">
                        <span class="menu-text">Jadwal</span>
                    </a>
                </li>

            @endif
            @if(Request::is('ortu/*'))

                <li class="menu-item {{ Request::is('ortu/dashboard') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('ortu/dashboard') }}" class="menu-link {{ Request::is('ortu/dashboard') ? 'active' : '' }}">
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('ortu/pembayaran/*') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('ortu/pembayaran') }}" class="menu-link {{ Request::is('ortu/pembayaran') ? 'active' : '' }}">
                        <span class="menu-text">Pembayaran</span>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('ortu/jadwal/*') ? 'menuitem-active' : '' }}">
                    <a href="{{ url('ortu/jadwal') }}" class="menu-link {{ Request::is('ortu/jadwal') ? 'active' : '' }}">
                        <span class="menu-text">Jadwal</span>
                    </a>
                </li>

            @endif

        </ul>
        <!--- End Menu -->
        <div class="clearfix"></div>
    </div>
</div>
