<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="d-flex flex-column" style="height: 100vh;">
        <!-- Logo and navigation -->
        <div class="p-2">
            <div class="brand-logo d-flex align-items-center justify-content-between">
                <div class="text-nowrap logo-img">
                    <!-- <img src="../assets/images/logos/logo-light.svg" alt="" /> -->
                    <h3 class="logo-text fw-bolder">Grandeur Realty</h3>
                </div>
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
                        <a class="sidebar-link" href="{{ route('inquiries') }}" aria-expanded="false">
                            <span>
                                <iconify-icon icon="solar:question-circle-bold-duotone" class="fs-6"></iconify-icon>
                            </span>
                            <span class="hide-menu">Inquiries</span>
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

        @php
            // Define the path where you want to check the storage (root of your server or Laravel project directory)
            $path = base_path(); // This gets the path of your Laravel project

            // Get total disk space in GB
            $storageTotal = round(disk_total_space($path) / 1024 / 1024 / 1024, 2); // Convert from bytes to GB

            // Get free/available disk space in GB
            $storageFree = round(disk_free_space($path) / 1024 / 1024 / 1024, 2); // Convert from bytes to GB

            // Calculate used storage
            $storageUsed = $storageTotal - $storageFree;
        @endphp

        <div class="text-center p-3 mt-auto bg-light" style="border-top: 1px solid #dee2e6;">
            <h5 class="mb-1">Storage Information:</h5>
            <h6 class="text-muted mb-0"><span class="text-danger">{{ $storageUsed }}</span> / <span class="text-dark">{{ $storageTotal }}</span> GB Used</h6>
            <h6 class="text-muted">Free: <span class="text-success">{{ $storageFree }}</span> GB</h6>
        </div>
    </div>
</aside>