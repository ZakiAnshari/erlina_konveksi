<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/" class="d-flex align-items-center text-decoration-none">
            <span class="app-brand-logo">
                <!-- Simple Circle Logo with "H" -->
                <svg width="50" height="50" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Bulatan -->
                    <circle cx="50" cy="50" r="48" fill="#696cff" />
                    <!-- Huruf H -->
                    <text x="50%" y="55%" text-anchor="middle" fill="#ffffff" font-size="48"
                        font-family="Arial, sans-serif" font-weight="bold" dominant-baseline="middle">
                        E
                    </text>
                </svg>
            </span>
            <span class="app-brand-text fw-bold ms-4 fs-4 text-dark">ERLINA</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <!-- Digital Clock -->
    <div id="digital-clock" class="text-center my-2"></div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="/dashboard" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        {{-- PENDAPATAN --}}
        <li class="menu-item {{ Request::is('pendapatan*') ? 'active' : '' }}">
            <a href="/pendapatan" class="menu-link">
                <i class="menu-icon tf-icons bx bx-dollar-circle"></i>

                <div data-i18n="Analytics">Pendapatan</div>
            </a>
        </li>
        {{-- PENGELUARAN --}}
        <li class="menu-item {{ Request::is('pengeluaran*') ? 'active' : '' }}">
            <a href="/pengeluaran" class="menu-link">
                <i class="menu-icon tf-icons bx bx-log-out"></i>
                <div data-i18n="Analytics">Pengeluaran</div>
            </a>
        </li>
        {{-- HUTANG/PIUTANG --}}
        <li class="menu-item {{ Request::is('hutang*') ? 'active' : '' }}">
            <a href="/hutang" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                <div data-i18n="Analytics">Hutang</div>
            </a>
        </li>


        {{-- KARYAWAN --}}
        <li class="menu-item {{ Request::is('karyawan*') ? 'active' : '' }}">
            <a href="/karyawan" class="menu-link">
                <i class="menu-icon tf-icons bx bx-id-card"></i>
                <div data-i18n="Analytics">Karyawan</div>
            </a>
        </li>

        {{-- LAPORAN --}}



        <li class="menu-item {{ Request::is('laporan*') ? 'open active' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Layouts">Laporan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('laporan/pendapatan') ? 'active' : '' }}">
                    <a href="/laporan/pendapatan" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-trending-up"></i>
                        <div data-i18n="Analytics">Pendapatan</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('laporan/pengeluaran') ? 'active' : '' }}">
                    <a href="/laporan/pengeluaran" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-trending-down"></i>
                        <div data-i18n="Analytics">Pengeluaran</div>
                    </a>
                </li>
            </ul>
        </li>



        <li class="menu-header small text-uppercase"><span class="menu-header-text">Hak Akses</span></li>
        {{-- USER --}}
        <li class="menu-item {{ Request::is('user*') ? 'active' : '' }}">
            <a href="/user" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                <div data-i18n="Analytics">User</div>
            </a>
        </li>
    </ul>
</aside>
