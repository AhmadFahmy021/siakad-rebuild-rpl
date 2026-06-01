<div class="navbar-custom">
    <div class="topbar">
        <div class="topbar-menu d-flex align-items-center gap-1">

            <!-- Sidebar Menu Toggle Button -->
            <button class="button-toggle-menu">
                <i class="mdi mdi-menu"></i>
            </button>


        </div>

        <ul class="topbar-menu d-flex align-items-center">


            <!-- Fullscreen Button -->
            <li class="d-none d-md-inline-block">
                <a class="nav-link waves-effect waves-light" href="" data-toggle="fullscreen">
                    <i class="fe-maximize font-22"></i>
                </a>
            </li>





            <!-- User Dropdown -->
            <li class="dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="{{ asset('assets') }}/images/users/user-1.jpg" alt="user-image" class="rounded-circle">
                    <span class="ms-1 d-none d-md-inline-block">
                        {{ Auth::user()->name }} <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <!-- item-->
                    <span class="dropdown-item notify-item">
                        <i class="fe-user"></i>
                        <span>{{ Auth::user()->username }}</span>
                    </span>

                    <span class="dropdown-item notify-item">
                        <i class="fe-mail"></i>
                        <span>{{ Auth::user()->email }}</span>
                    </span>
                    <div class="dropdown-divider"></div>
                    
                    <div class="dropdown-title">Role</div>

                        @if (App\Models\Admin::where('user_id', Auth::id())->exists())
                            <a href="{{ url('admin/dashboard') }}"
                            class="dropdown-item notify-item {{ Request::is('admin/*') ? 'active' : '' }}">
                                <span>Admin</span>
                            </a>
                        @endif

                        @if (App\Models\Guru::where('user_id', Auth::id())->exists())
                            <a href="{{ url('guru/dashboard') }}"
                            class="dropdown-item notify-item {{ Request::is('guru/*') ? 'active' : '' }}">
                                <span>Guru</span>
                            </a>
                        @endif

                        @if (App\Models\TataUsaha::where('user_id', Auth::id())->exists())
                            <a href="{{ url('tu/dashboard') }}"
                            class="dropdown-item notify-item {{ Request::is('tu/*') ? 'active' : '' }}">
                                <span>Tata Usaha</span>
                            </a>
                        @endif

                        @if (App\Models\Siswa::where('user_id', Auth::id())->exists())
                            <a href="{{ url('siswa/dashboard') }}"
                            class="dropdown-item notify-item {{ Request::is('siswa/*') ? 'active' : '' }}">
                                <span>Siswa</span>
                            </a>
                        @endif
                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item notify-item">
                            <i class="fe-log-out"></i>
                            <span>Logout</span>
                        </button>
                    </form>

                </div>
            </li>

            <!-- Right Bar offcanvas button (Theme Customization Panel) -->
        </ul>
    </div>
</div>
