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
                <div class="sub-heading text-uppercase mb-4" data-aos="fade-up">Welcome To <br class="d-sm-none">
                    Grandeur Realty!</div>
                <h1 class="heading" data-aos="fade-up" data-aos-delay="100">
                    Your Needs, Our Priority: Buy, Sell, Lease with Ease
                </h1>
            </div>
        </div>
    </div>
</div>

<div class="section" id="hot-properties">
    <div class="container">
        <div class="row mb-5 align-items-center" data-aos="fade-up">
            <div class="col-7">
                <h2 class="fw-bolder text-primary heading">
                    Hot Properties
                </h2>
            </div>

            <div class="col-5 text-end">
                <p>
                    <a href="{{ route('allProperties') }}"
                        class="btn btn-primary py-3 px-4 d-none d-sm-inline-block">View All Properties</a>
                    <a href="{{ route('allProperties') }}" class="btn btn-primary btn-sm d-inline-block d-sm-none">View
                        All</a>
                </p>
            </div>

        </div>

        <div class="container mt-5">
            <div class="row button-row">
                @foreach($cities as $city)
                    <div class="col-6 mb-3" data-aos="fade-up" data-aos-delay="200">
                        <a href="{{ route('hotProperties', ['city' => $city->city]) }}" class="city-card"
                            style="background-image: url('{{ $city->image_url }}');">
                            <div class="city-overlay">
                                <span class="city-name">{{ $city->city }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<section class="features-1">
    <div class="container">
        <div class="row">
            @foreach ($features as $feature)
                <div class="col-6 col-lg-3 mb-2 mt-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="box-feature">
                        <span class="{{ $feature['icon'] }}"></span>
                        <h3 class="mb-3">{{ $feature['title'] }}</h3>
                        <p>
                            {{ $feature['description'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<div class="section" id="list-with-us">
    <div class="row justify-content-center footer-cta" data-aos="fade-up">
        <div class="col-lg-7 mx-auto text-center">
            <h2 class="mb-4">Do you want your property to be listed here?</h2>
            <p>
                <a href="{{ route('listWithUsForm') }}" class="btn btn-primary py-3 px-4">Submit
                    Now!</a>
            </p>
        </div>
    </div>
</div>


<div class="section section-4 bg-light" id="about">
    <div class="container">
        <div class="row justify-content-center text-center mb-5" data-aos="fade-up">
            <div class="col-lg-7">
                <h2 class="fw-bolder heading text-primary mb-4">
                    About Grandeur Realty
                </h2>
                <p class="text-black-50">
                    Grandeur Realty is dedicated to transforming the real estate experience by guiding clients through
                    every step of the property journey. From purchasing a family home to investing in real estate, we
                    are committed to making your goals a reality.
                </p>
            </div>
        </div>

        <div class="row align-items-center justify-content-between mb-5 mx-auto" style="max-width: 1200px;">
            <div class="col-lg-7 mb-5 mb-lg-0 order-lg-2" data-aos="fade-up" data-aos-delay="200">
                <div class="img-about dots text-center">
                    <img src="{{ asset('images/grandeur-realty-about.jpg') }}" alt="About Grandeur Realty"
                        class="img-fluid img-thumbnail" />
                </div>
            </div>
            <div class="col-lg-4 d-flex flex-column justify-content-center">
                <div class="d-flex feature-h mb-4" data-aos="fade-up" data-aos-delay="100">
                    <span class="wrap-icon me-3">
                        <span class="icon-bullseye"></span>
                    </span>
                    <div class="feature-text">
                        <h3 class="heading">Our Mission</h3>
                        <p class="text-black-50">
                            To provide an exceptional, personalized real estate experience that goes beyond
                            transactions. We are passionate about helping clients build lasting wealth and find places
                            they can call home, delivering quality service and professional expertise.
                        </p>
                    </div>
                </div>

                <div class="d-flex feature-h mb-0" data-aos="fade-up" data-aos-delay="300">
                    <span class="wrap-icon me-3">
                        <span class="icon-eye"></span>
                    </span>
                    <div class="feature-text">
                        <h3 class="heading">Our Vision</h3>
                        <p class="text-black-50">
                            To become a trusted leader in the real estate industry, recognized for our integrity,
                            dedication, and commitment to excellence. We envision a world where property ownership is
                            accessible and rewarding for everyone, creating lasting value for generations to come.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection