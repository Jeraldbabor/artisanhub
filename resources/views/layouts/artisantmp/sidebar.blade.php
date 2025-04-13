<style>
    /* Additional Custom Styles */
    .user-panel {
        transition: all 0.3s ease;
        margin: 0 10px;
    }
    
    .user-panel:hover {
        background: linear-gradient(135deg, #3a65d1 0%, #1a3da8 100%) !important;
    }
    
    .user-panel .image {
        transition: all 0.3s ease;
    }
    
    .user-panel .image:hover {
        transform: scale(1.05);
    }
    
    .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
        background-color: rgba(255,255,255,0.1);
        border-left: 3px solid #fff;
    }
    
    .nav-item > .nav-link {
        margin-bottom: 2px;
    }
</style>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Enhanced Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center p-2 rounded" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
            <div class="image">
                <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('assetsadmin/dist/img/user2-160x160.jpg') }}" 
                     class="img-circle elevation-2" 
                     alt="User Image"
                     style="width: 40px; height: 40px; object-fit: cover; border: 2px solid rgba(255,255,255,0.3);">
            </div>
            <div class="info ml-2">
                <a href="#" class="d-block text-white font-weight-light" style="font-size: 0.9rem;">Welcome,</a>
                <a href="#" class="d-block text-white font-weight-bold" style="font-size: 1rem;">{{ explode(' ', Auth::user()->name)[0] }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard Link -->
                <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'menu-open' : '' }}">
                    <a href="{{ route('artisan.dashboard') }}" class="nav-link {{ request()->routeIs('artisan.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Settings Link -->
                <li class="nav-item {{ request()->routeIs('artisan.productlist') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Product
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- Product List Link -->
                        <li class="nav-item">
                            <a href="{{ route('artisan.productlist') }}" class="nav-link {{ request()->routeIs('artisan.productlist') ? 'active' : '' }}">
                                <i class="fas far fa-file-alt"></i>
                                <p>Product List</p>
                            </a>
                            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                                <i class="fas far fa-file-alt"></i>
                                <p>categories List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon 	fas fa-envelope-open-text"></i>
                        <p>Transactions</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-address-card"></i>
                        <p>Customers</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('artisan.orders.index') }}" class="nav-link">
                        <i class="nav-icon far fa-clipboard"></i>
                        <p>Orders</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
