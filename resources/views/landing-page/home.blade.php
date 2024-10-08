@extends('landing-page.layouts.app')

@section('title', 'Grandeur Realty - Home')

@section('contents')
<div class="hero" id="home">
    <div class="hero-slide">
        <div class="img overlay" style="background-image: url('images/hero_bg_3.jpg')"></div>
        <div class="img overlay" style="background-image: url('images/hero_bg_2.jpg')"></div>
        <div class="img overlay" style="background-image: url('images/hero_bg_1.jpg')"></div>
    </div>

    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-9 text-center">
                <div class="sub-heading text-uppercase mb-4" data-aos="fade-up" style="font-size: 3rem;">Welcome To Grandeur Realty!</div>
                <h1 class="heading" data-aos="fade-up">
                    Find Your Dream Home
                </h1>
                <a class="btn btn-primary py-3 px-4" data-aos="fade-up" href="#hot-properties">GET STARTED</a>
            </div>
        </div>
    </div>
</div>

<div class="section" id="hot-properties">
    <div class="container">
        <div class="row mb-5 align-items-center">
            <div class="col-lg-6">
                <h2 class="font-weight-bold text-primary heading">
                    Hot Properties
                </h2>
            </div>

            <div class="col-lg-6 text-lg-end">
                <p>
                    <a href="{{ route('allProperties') }}" class="btn btn-primary text-white py-3 px-4">View All
                        Properties</a>
                </p>
            </div>
        </div>

        <div class="container mt-5">
            <div class="row button-row">
                @foreach($cities as $city)
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('hotProperties', ['city' => $city->city]) }}"
                            class="button-card">{{ $city->city }}</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<section class="features-1">
    <div class="container">
        <div class="row">
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="box-feature">
                    <span class="flaticon-house"></span>
                    <h3 class="mb-3">Our Properties</h3>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                        Voluptates, accusamus.
                    </p>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                <div class="box-feature">
                    <span class="flaticon-building"></span>
                    <h3 class="mb-3">Property for Sale</h3>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                        Voluptates, accusamus.
                    </p>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="500">
                <div class="box-feature">
                    <span class="flaticon-house-2"></span>
                    <h3 class="mb-3">House for Rent</h3>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                        Voluptates, accusamus.
                    </p>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="600">
                <div class="box-feature">
                    <span class="flaticon-house-1"></span>
                    <h3 class="mb-3">House for Sale</h3>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                        Voluptates, accusamus.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="section" id="list-with-us">
    <div class="row justify-content-center footer-cta" data-aos="fade-up">
        <div class="col-lg-7 mx-auto text-center">
            <h2 class="mb-4">Do you want your property to be listed here?</h2>
            <p>
                <a href="{{ route('listWithUsForm') }}" class="btn btn-primary text-white py-3 px-4">Submit
                    Now!</a>
            </p>
        </div>
    </div>
</div>


<div class="section section-4 bg-light" id="about">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-5">
                <h1 class="font-weight-bold heading text-primary mb-4">
                    About Grandeur Realty
                </h1>
                <p class="text-black-50">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus convallis lectus et tristique feugiat. Nam imperdiet risus et eros ultrices,
                    ut bibendum nibh iaculis.
                </p>
            </div>
        </div>

        <div class="row justify-content-between mb-5 mx-auto" style="max-width: 1200px;">
            <div class="col-lg-7 mb-5 mb-lg-0 order-lg-2">
                <div class="img-about dots text-center">
                    <img src="{{ asset('images/grandeur-realty.jpg') }}" alt="Image" class="img-fluid img-thumbnail" />
                </div>
            </div>
            <div class="col-lg-4 d-flex flex-column justify-content-center">
                <div class="d-flex feature-h mb-4">
                    <span class="wrap-icon me-3">
                        <span class="icon-bullseye"></span>
                    </span>
                    <div class="feature-text">
                        <h3 class="heading">Our Mission</h3>
                        <p class="text-black-50">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ligula nibh,
                            lacinia et diam eget, tempor facilisis orci. Ut ac libero at ante lacinia pulvinar.
                        </p>
                    </div>
                </div>

                <div class="d-flex feature-h">
                    <span class="wrap-icon me-3">
                        <span class="icon-eye"></span>
                    </span>
                    <div class="feature-text">
                        <h3 class="heading">Our Vision</h3>
                        <p class="text-black-50">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ligula nibh,
                            lacinia et diam eget, tempor facilisis orci. Ut ac libero at ante lacinia pulvinar.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row section-counter mt-5 justify-content-center">
            <div class="col-6 col-sm-6 col-md-6 col-lg-3 text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="counter-wrap mb-5 mb-lg-0">
                    <span class="number"><span class="countup text-primary">3298</span></span>
                    <span class="caption text-black-50"># of Listed Properties</span>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-3 text-center" data-aos="fade-up" data-aos-delay="400">
                <div class="counter-wrap mb-5 mb-lg-0">
                    <span class="number"><span class="countup text-primary">10231</span></span>
                    <span class="caption text-black-50"># of Sold Properties</span>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-3 text-center" data-aos="fade-up" data-aos-delay="500">
                <div class="counter-wrap mb-5 mb-lg-0">
                    <span class="number"><span class="countup text-primary">9316</span></span>
                    <span class="caption text-black-50"># of Satisfied Clients</span>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-3 text-center" data-aos="fade-up" data-aos-delay="600">
                <div class="counter-wrap mb-5 mb-lg-0">
                    <span class="number"><span class="countup text-primary">7191</span></span>
                    <span class="caption text-black-50"># of Successful Transactions</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection