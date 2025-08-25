@php
    use Illuminate\Support\Facades\Auth;
@endphp

<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between w-100">

        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
            <img src="{{ asset('image/logo.png') }}" alt="Logo" style="height: 50px; width: auto; margin-right: 10px;">
            <span class="d-none d-lg-block" style="font-size: 22px; font-weight: bold;">
                MA Mangunsari
            </span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>

        <!-- Profile Dropdown di Navbar -->
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle me-2"
                            width="35" height="35">
                        <span class="d-none d-md-block dropdown-toggle ps-2">
                            {{ Auth::user()->name }}
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile shadow border-0 mt-2">

                        <!-- Header -->
                        <li class="dropdown-header text-center">
                            <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile"
                                class="rounded-circle mb-2 border shadow-sm" width="60" height="60">
                            <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                            <small class="text-muted">{{ Auth::user()->role ?? 'User' }}</small>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <!-- Menu Items -->
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <i class="bi bi-person me-2 text-primary"></i> My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <i class="bi bi-gear me-2 text-secondary"></i> Account Settings
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <!-- Logout -->
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </li><!-- End Profile Nav -->

            </ul>
        </nav>
    </div>
</header>
