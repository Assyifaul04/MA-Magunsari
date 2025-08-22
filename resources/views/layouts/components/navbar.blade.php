@php
    use Illuminate\Support\Facades\Auth;
@endphp

<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
            <img src="{{ asset('image/logo.png') }}" alt="Logo" style="height: 50px; width: auto; margin-right: 10px;">
            <span class="d-none d-lg-block" style="font-size: 22px; font-weight: bold;">
                MA Mangunsari
            </span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

</header><!-- End Header -->
