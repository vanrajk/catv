<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

    <!-- Brand -->
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <!-- <img src="{{ asset('assets/img/AdminLTELogo.png') }}" class="brand-image"> -->
            <span class="brand-text fw-light">Cable TV Billing</span>
        </a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar-wrapper">
        <nav class="mt-2">

            <!-- IMPORTANT: data-lte-toggle="treeview" -->
            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false">

                <li class="nav-item">
    <a href="{{ route('admin.dashboard') }}"
       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="nav-icon bi bi-speedometer2"></i>
        <p>Dashboard</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('customers.index') }}"
       class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
        <i class="nav-icon bi bi-people"></i>
        <p>ગ્રાહકો</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('transactions.index') }}"
       class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
        <i class="nav-icon bi bi-cash-stack"></i>
        <p>બિલો / ચૂકવણીઓ</p>
    </a>
</li>


                <!-- MASTER MENU -->
                <li class="nav-item {{ request()->is('admin/zones*','admin/areas*','admin/sites*') ? 'menu-open' : '' }}">
    <a href="#"
       class="nav-link {{ request()->is('admin/zones*','admin/areas*','admin/sites*') ? 'active' : '' }}">
        <i class="nav-icon bi bi-diagram-3"></i>
        <p>
            Master
            <i class="nav-arrow bi bi-chevron-right"></i>
        </p>
    </a>


                    <!-- SUB MENU -->
                    <ul class="nav nav-treeview">

    <li class="nav-item">
        <a href="{{ url('admin/zones') }}"
           class="nav-link {{ request()->is('admin/zones*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-circle"></i>
            <p>ઝોન</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ url('admin/areas') }}"
           class="nav-link {{ request()->is('admin/areas*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-circle"></i>
            <p>વિસ્તારો</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ url('admin/sites') }}"
           class="nav-link {{ request()->is('admin/sites*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-circle"></i>
            <p>આઈડીઑ</p>
        </a>
    </li>

</ul>

                </li>

            </ul>
        </nav>
    </div>

</aside>
