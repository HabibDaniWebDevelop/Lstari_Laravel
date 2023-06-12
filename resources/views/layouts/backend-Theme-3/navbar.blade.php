<?php
$iduser = Session::get('iduser');

//!  ------------------------ Cek gambar ------------------------ !!
// Initialize an URL to the variable
// $url = 'http://192.168.1.100:8585/karyawan/' . $iduser . '.jpg';

// Use get_headers() function
// $headers = @get_headers($url);

// Use condition to check the existence of URL
// if ($headers && strpos($headers[0], '404')) {
//     $provil = 'assets/images/user.jpg';
// } else {
//     $provil = 'http://192.168.1.100/karyawan/' . $iduser . '.jpg';
// }

?>


<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar"
    style="height: 3rem;">
    {{-- style="background-color: #913030;" --}}
    <div class="container-fluid">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none d-none">
            <a class="nav-item nav-link px-0 me-xl-4 text-white" href="javascript:void(0)">
                <i class="bx bx-menu bx-md align-middle"></i>
            </a>
        </div>

        <div class="navbar-brand app-brand demo d-xl-flex py-0 me-4 d-none d-lg-flex">
            <a href="/" class="app-brand-link gap-2">
                <img src="{!! asset('assets/images/favicon.png') !!}" class="w-px-40 h-auto rounded-circle"
                    style="opacity: .9">
                <span class="app-brand-text demo menu-text fw-bolder text-white ">LMS</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
        </div>
        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">



            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <li class="nav-item d-none d-md-block me-3 me-xl-1">
                    <div class="mx-4" style="color: white;"> {{ date('d M Y'), }} :
                        <!-- date('d F Y') -->
                        <b> <span id="jam" style="font-size:24"></span>
                        </b>
                    </div>
                </li>
                <!-- Notification -->
                {{-- <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                    <a class="nav-link dropdown-toggle hide-arrow text-light" href="javascript:void(0);"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" onclick="Notification_list()">
                        <i class="bx bx-bell bx-tada-hover bx-sm"></i>
                        <span class="badge bg-warning rounded-pill badge-notifications d-none" id='ntcountmaster'>
                            <div id="ntcount">0</div>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end py-0">
                        <div id="Notif">
                        </div>
                    </ul>
                </li> --}}
                <!--/ Notification -->

                <!-- Messaging -->

                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                    <a class="nav-link dropdown-toggle hide-arrow text-light" href="javascript:void(0);"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" onclick="Messaging_list()">
                        <i class="bx bx-envelope bx-tada-hover bx-sm"></i>
                        <span class="badge bg-warning rounded-pill badge-notifications d-none" id='mscountmaster'>
                            <div id="mscount">0</div>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end py-0">
                        <div id="pesan">
                        </div>
                    </ul>
                </li>
                <!--/ Messaging -->

                <!-- Setting -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="{{ Session::get('hostfoto') }}/karyawan2/{{ $iduser }}.jpg" class="rounded-circle"
                                style="width: 100%; object-fit: cover; object-position: top;"
                                onerror="this.onerror=null; this.src='{!! asset('assets/images/user.jpg') !!}'">
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard.index') }}">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar" style="width: 3.5rem; height: 3.5rem;">
                                            <img src="{{ Session::get('hostfoto') }}/karyawan2/{{ $iduser }}.jpg"
                                                class="rounded-circle"
                                                style="width: 100%; object-fit: cover; object-position: top;"
                                                onerror="this.onerror=null; this.src='{!! asset('assets/images/user.jpg') !!}'">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                        <small class="text-muted">{{ Session::get('LevelUser') }} -
                                            {{ Session::get('iduser') }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        {{-- <li>
                            <a class="dropdown-item" href="{{ route('setting.todolist') }}">
                        <i class="fas fa-calendar-check me-2"></i>
                        <span class="align-middle">To Do List</span>
                        </a>
                </li> --}}
                <li>
                    <a class="dropdown-item" href="{{ route('setting.gantipswd') }}">
                        <i class="fas fa-user-lock me-2"></i>
                        <span class="align-middle">Ganti Password</span>
                    </a>
                </li>
                @if (Auth::user()->level == '1')
                <li>
                    <a class="dropdown-item" href="{{ route('setting') }}">
                        <i class="bx bx-cog me-2"></i>
                        <span class="align-middle">Settings</span>
                    </a>
                </li>
                @endif
                <li>
                    <a class="dropdown-item" href="{{ route('setting.About') }}">
                        <i class="bx bx-help-circle me-2"></i>
                        <span class="align-middle">About</span>
                    </a>
                </li>
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('dashboard.logout') }}">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                    </a>
                </li>
            </ul>
            </li>
            <!--/ Setting -->


            </ul>
        </div>
    </div>
</nav>

<!-- / Navbar -->