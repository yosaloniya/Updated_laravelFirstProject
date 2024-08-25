@php
    $userRole = Auth::user()->role;
    $userAccess = json_decode(Auth::user()->access, true);
@endphp
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    @if ($userRole == 1)
        <li class="nav-item active">
            <a class="nav-link" href="{{ url('/') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>
    <!-- Nav Item - Charts -->
    @if ($userRole == 1)
        <li class="nav-item">
            <a class="nav-link" href="{{ url('users') }}">
                <i class="fa fa-user" aria-hidden="true"></i>
                <span>Users</span></a>
        </li>
    @endif
    @if ($userRole == 1 || $userRole == 0 || $userRole == -1)
        @if (is_array($userAccess) && in_array('brand', $userAccess))
            <li class="nav-item">
                <a class="nav-link" href="{{ url('brand') }}">
                    <i class="fa fa-flag" aria-hidden="true"></i>
                    <span>Brand</span></a>
            </li>
        @endif
    @endif
    @if ($userRole == 1 || $userRole == 0 || $userRole == -1)
        @if (is_array($userAccess) && in_array('category', $userAccess))
            <li class="nav-item">
                <a class="nav-link" href="{{ url('category') }}">
                    <i class="fa fa-th" aria-hidden="true"></i>
                    <span>Category</span></a>
            </li>
        @endif
    @endif
    @if ($userRole == 1 || $userRole == 0 || $userRole == -1)
        @if (is_array($userAccess) && in_array('products', $userAccess))
            <li class="nav-item">
                <a class="nav-link" href="{{ url('products') }}">
                    <i class="fa fa-product-hunt" aria-hidden="true"></i>
                    <span>Products</span></a>
            </li>
        @endif
    @endif
    @if ($userRole == 1 || $userRole == 0 || $userRole == -1)
        @if (is_array($userAccess) && in_array('sales', $userAccess))
            <li class="nav-item">
                <a class="nav-link" href="{{ url('orders') }}">
                    <i class="fa fa-bar-chart" aria-hidden="true"></i>
                    <span>Sales</span></a>
            </li>
        @endif
    @endif
    @if ($userRole == 1 || $userRole == 0 || $userRole == -1)
        @if (is_array($userAccess) && in_array('customers', $userAccess))
            <li class="nav-item">
                <a class="nav-link" href="{{ url('customers') }}">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>Customers</span></a>
            </li>
        @endif
    @endif
    @if ($userRole == 1 || $userRole == 0 || $userRole == -1)
        @if (is_array($userAccess) && in_array('sup_p', $userAccess))
            <li class="nav-item">
                <a class="nav-link" href="{{ url('supplierproducts') }}">
                    <i class="fa fa-history" aria-hidden="true"></i>
                    <span>Supplier Products</span></a>
            </li>
        @endif
    @endif
    @if ($userRole == 1 || $userRole == 0 || $userRole == -1)
        @if (is_array($userAccess) && in_array('suppliers', $userAccess))
            <li class="nav-item">
                <a class="nav-link" href="{{ url('suppliers') }}">
                    <i class="fa fa-industry" aria-hidden="true"></i>
                    <span>Suppliers</span></a>
            </li>
        @endif
    @endif
    @if ($userRole == 1 || $userRole == 0 || $userRole == -1)
        @if (is_array($userAccess) && in_array('returns', $userAccess))
            <li class="nav-item">
                <a class="nav-link" href="{{ url('returns') }}">
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                    <span>Returs</span></a>
            </li>
        @endif
    @endif
    @if ($userRole == 1 || $userRole == 0 || $userRole == -1)
        @if (is_array($userAccess) && in_array('history', $userAccess))
            <li class="nav-item">
                <a class="nav-link" href="{{ url('history') }}">
                    <i class="fa fa-history" aria-hidden="true"></i>
                    <span>History</span></a>
            </li>
        @endif
    @endif

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>
