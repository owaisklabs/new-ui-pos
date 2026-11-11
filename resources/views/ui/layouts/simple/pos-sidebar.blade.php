<div class="sidebar-wrapper">
    <div>
        <div class="logo-wrapper">
            <a href="{{ route('dashboard') }}">
                <img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png') }}" alt="">
                <img class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo.png') }}" alt="">
            </a>
            <div class="back-btn"><i class="fa fa-angle-left"></i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"></i></div>
        </div>
        <div class="logo-icon-wrapper"><a href="{{ route('dashboard') }}"><img class="img-fluid" src="{{ asset('assets/images/logo/logo.png') }}" alt=""></a></div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Dashboard</h6>
                            <p>POS Management</p>
                        </div>
                    </li>

                    <!-- Main Dashboard -->
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i data-feather="home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Sales Management -->
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ request()->route()->getPrefix() == '/dashboard/sales' ? 'active' : '' }}" href="#">
                            <i data-feather="shopping-cart"></i>
                            <span>Sales</span>
                            <div class="according-menu"><i class="fa fa-angle-{{ request()->route()->getPrefix() == '/dashboard/sales' ? 'down' : 'right' }}"></i></div>
                        </a>
                        <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard/sales' ? 'block;' : 'none;' }}">
                            <li><a href="{{ route('sales.index') }}" class="{{ Route::currentRouteName() == 'sales.index' ? 'active' : '' }}">Sales List</a></li>
                            <li><a href="{{ route('sales.create') }}" class="{{ Route::currentRouteName() == 'sales.create' ? 'active' : '' }}">New Sale</a></li>
                            <li><a href="{{ route('return-sales.index') }}" class="{{ Route::currentRouteName() == 'return-sales.index' ? 'active' : '' }}">Sale Returns</a></li>
                        </ul>
                    </li>

                    <!-- Purchase Management -->
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ request()->route()->getPrefix() == '/dashboard/purchase' ? 'active' : '' }}" href="#">
                            <i data-feather="shopping-bag"></i>
                            <span>Purchase</span>
                            <div class="according-menu"><i class="fa fa-angle-{{ request()->route()->getPrefix() == '/dashboard/purchase' ? 'down' : 'right' }}"></i></div>
                        </a>
                        <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard/purchase' ? 'block;' : 'none;' }}">
                            <li><a href="{{ route('purchase.index') }}" class="{{ Route::currentRouteName() == 'purchase.index' ? 'active' : '' }}">Purchase List</a></li>
                            <li><a href="{{ route('purchase.create') }}" class="{{ Route::currentRouteName() == 'purchase.create' ? 'active' : '' }}">New Purchase</a></li>
                            <li><a href="{{ route('return-purchases.index') }}" class="{{ Route::currentRouteName() == 'return-purchases.index' ? 'active' : '' }}">Purchase Returns</a></li>
                        </ul>
                    </li>

                    <!-- Inventory & Items -->
                    <li class="sidebar-list">
                        @php
                            $inventoryOpen = request()->routeIs('book.*') || request()->routeIs('categories.*') || Route::currentRouteName() == 'inventory.index';
                        @endphp
                        <a class="sidebar-link sidebar-title {{ $inventoryOpen ? 'active' : '' }}" href="#">
                            <i data-feather="package"></i>
                            <span>Inventory</span>
                            <div class="according-menu"><i class="fa fa-angle-{{ $inventoryOpen ? 'down' : 'right' }}"></i></div>
                        </a>
                        <ul class="sidebar-submenu" style="display: {{ $inventoryOpen ? 'block;' : 'none;' }}">
                            <li><a href="{{ route('book.index') }}" class="{{ Route::currentRouteName() == 'book.index' ? 'active' : '' }}">Books/Items</a></li>
                            <li><a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">Categories</a></li>
                            <li><a href="{{ route('inventory.index') }}" class="{{ Route::currentRouteName() == 'inventory.index' ? 'active' : '' }}">Stock</a></li>
                        </ul>
                    </li>

                    <!-- Master Data -->
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Master Data</h6>
                            <p>Configuration</p>
                        </div>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ Route::currentRouteName() == 'customer.index' ? 'active' : '' }}" href="{{ route('customer.index') }}">
                            <i data-feather="users"></i>
                            <span>Customers</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ Route::currentRouteName() == 'supplier.index' ? 'active' : '' }}" href="{{ route('supplier.index') }}">
                            <i data-feather="truck"></i>
                            <span>Suppliers</span>
                        </a>
                    </li>

                   

                    <!-- Operations -->
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Operations</h6>
                            <p>Business Functions</p>
                        </div>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ Route::currentRouteName() == 'expenses.index' ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                            <i data-feather="dollar-sign"></i>
                            <span>Expenses</span>
                        </a>
                    </li>

                    <!-- Multi-Branch Features -->
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Multi-Branch</h6>
                            <p>Branch Management</p>
                        </div>
                    </li>

                    @if(Auth::check() && Auth::user()->isAdmin())
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ Route::currentRouteName() == 'branches.index' ? 'active' : '' }}" href="{{ route('branches.index') }}">
                            <i data-feather="map-pin"></i>
                            <span>Branches</span>
                        </a>
                    </li>
                    @endif

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ Route::currentRouteName() == 'reports.index' ? 'active' : '' }}" href="{{ route('reports.index') }}">
                            <i data-feather="bar-chart-2"></i>
                            <span>Reports</span>
                        </a>
                    </li>

                    <!-- Settings -->
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Admin</h6>
                            <p>Settings</p>
                        </div>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ Route::currentRouteName() == 'settings.index' ? 'active' : '' }}" href="{{ route('settings.index') }}">
                            <i data-feather="settings"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
