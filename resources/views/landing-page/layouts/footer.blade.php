<div class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="widget">
                    <img src="{{ asset('images/grandeur-realty-footer.png') }}" alt="Grandeur Realty Logo"
                        style="width: 70%;" class="pb-2 ms-2">
                    <address>Your go-to platform for exploring top real
                        estate opportunities across the Philippines. Discover insights into the real estate market—all
                        designed to help you make informed and
                        confident decisions.</address>
                    <p>Phone: <a class="copyPhoneNumber" role="button" style="cursor: pointer;">
                            <span class="d-md-inline-block">+63 917 827 8812</span>
                        </a></p>
                    <p>Email: <a href="https://mail.google.com/mail/?view=cm&fs=1&to=grandeurrealty.ph@gmail.com"
                            target="_blank">grandeurrealty.ph@gmail.com</a></p>

                    <ul class="list-unstyled social">
                        <li>
                            <a href="https://www.facebook.com/grandeurlistings.ph" target="_blank"><span
                                    class="icon-facebook"></span></a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/grandeurrealty.ph/" target="_blank"><span
                                    class="icon-instagram"></span></a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-1 d-none d-lg-block"></div>

            <div class="col-lg-6 col-md-6">
                <div class="widget">
                    <h3>Quick Links</h3>
                    <ul class="list-unstyled links">
                        <li><a href="{{ route('home') }}#home">Home</a></li>
                        <li><a href="{{ route('allProperties') }}">Properties</a></li>
                        <li><a href="{{ route('listWithUsForm') }}">List With Us</a></li>
                        <li><a href="{{ route('home') }}#about">About</a></li>
                        <li><a href="{{ route('contactUsForm') }}">Contact Us</a></li>
                        <li><a href="{{ route(name: 'privacyPolicy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>