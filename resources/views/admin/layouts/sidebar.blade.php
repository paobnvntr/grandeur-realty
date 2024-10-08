<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="d-flex flex-column" style="height: 100vh;">
        <!-- Logo and navigation -->
        <div class="p-2">
            <div class="brand-logo d-flex align-items-center justify-content-between">
                <a href="./index.html" class="text-nowrap logo-img">
                    <!-- <img src="../assets/images/logos/logo-light.svg" alt="" /> -->
                    <h3 class="logo-text fw-bolder">Grandeur Realty</h3>
                </a>
                <div class="ms-2 close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                    <i class="ti ti-x fs-8"></i>
                </div>
            </div>
            <!-- Sidebar navigation-->
            <nav class="sidebar-nav" data-simplebar="">
                <ul id="sidebarnav">
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                        <span class="hide-menu">Core</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                            <span>
                                <iconify-icon icon="solar:chart-bold-duotone" class="fs-6"></iconify-icon>
                            </span>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('listWithUs') }}" aria-expanded="false">
                            <span>
                                <iconify-icon icon="solar:document-add-bold-duotone" class="fs-6"></iconify-icon>
                            </span>
                            <span class="hide-menu">List with Us</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('properties.List') }}" aria-expanded="false">
                            <span>
                                <iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon>
                            </span>
                            <span class="hide-menu">Properties</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('contactUs') }}" aria-expanded="false">
                            <span>
                                <iconify-icon icon="solar:call-chat-bold-duotone" class="fs-6"></iconify-icon>
                            </span>
                            <span class="hide-menu">Contact Us</span>
                        </a>
                    </li>
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                        <span class="hide-menu">Management</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('users') }}" aria-expanded="false">
                            <span>
                                <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-6"></iconify-icon>
                            </span>
                            <span class="hide-menu">Users</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('logs') }}" aria-expanded="false">
                            <span>
                                <iconify-icon icon="solar:file-text-bold-duotone" class="fs-6"></iconify-icon>
                            </span>
                            <span class="hide-menu">Logs</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- User name at the bottom -->
        <!-- <div class="text-center p-3 mt-auto bg-light" style="border-top: 1px solid #dee2e6;">
            <h5 class="mb-0">Logged In As:</h5>
            <h6 class="text-muted">{{ auth()->user()->name }}</h6>
        </div> -->
    </div>
</aside>