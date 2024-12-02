<div class="site-mobile-menu site-navbar-target">
    <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
            <span class="icon-close2 js-menu-toggle"></span>
        </div>
    </div>
    <div class="site-mobile-menu-body"></div>
</div>

<div class="site-navbar-wrap">
    <div class="site-navbar-top">
        <div class="container py-3">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="d-flex mr-auto">
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=grandeurrealty.ph@gmail.com"
                            target="_blank" class="d-flex align-items-center me-4">
                            <span class="icon-envelope me-2"></span>
                            <span class="d-none d-md-inline-block">grandeurrealty.ph@gmail.com</span>
                        </a>
                        <a role="button" style="cursor: pointer;"
                            class="d-flex align-items-center mr-auto copyPhoneNumber">
                            <span class="icon-phone me-2"></span>
                            <span class="d-none d-md-inline-block">+63 917 827 8812</span>
                        </a>
                    </div>
                </div>
                <div class="col-6 text-right d-flex justify-content-end">
                    <div class="mr-auto">
                        <a href="https://www.facebook.com/grandeurlistings.ph" target="_blank" class="p-2 pl-0"><span
                                class="icon-facebook"></span></a>
                        <a href="https://www.instagram.com/grandeurrealty.ph/" target="_blank" class="p-2 pl-0"><span
                                class="icon-instagram"></span></a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="site-navbar site-navbar-target js-sticky-header">
        <div class="container">
            <div class="row align-items-center p-2">
                <div class="col-3">
                    <img class="my-0 site-logo" src="{{ asset('images/grandeur-realty-transparent.png') }}"
                        alt="Grandeur Realty Logo">
                    <!-- <h1 class="my-0 site-logo"><a href="{{ route('home') }}">Grandeur Realty</a></h1> -->
                </div>

                @if(Session::has('success'))
                    <div class="alert alert-success floating-alert fw-bold" id="alert-success" role="alert">
                        {{ Session::get('success') }}
                    </div>
                @endif

                @if(Session::has('failed'))
                    <div class="alert alert-danger floating-alert fw-bold" id="alert-failed" role="alert">
                        {{ Session::get('failed') }}
                    </div>
                @endif

                <div class="col-9 d-flex justify-content-end">
                    <nav class="site-navigation text-right" role="navigation">
                        <div class="container">
                            <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#"
                                    class="site-menu-toggle js-menu-toggle"><span
                                        class="icon-menu"></span></a></div>

                            <ul class="site-menu main-menu js-clone-nav d-none d-lg-block">
                                <li><a href="{{ route('home') }}#home" class="nav-link">Home</a></li>
                                <li>
                                    <a href="{{ request()->is('/') ? route('home') . '#hot-properties' : route('allProperties') }}"
                                        class="nav-link">
                                        Properties
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ request()->is('/') ? route('home') . '#list-with-us' : route('listWithUsForm') }}"
                                        class="nav-link">
                                        List With Us
                                    </a>
                                </li>
                                <li><a href="{{ route('home') }}#about" class="nav-link">About</a></li>
                                <li><a href="{{ route(name: 'contactUsForm') }}" class="nav-link">Contact Us</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>