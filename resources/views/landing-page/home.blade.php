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
                <div class="sub-heading text-uppercase mb-4" data-aos="fade-up">Welcome To
                    Grandeur Realty!</div>
                <h1 class="heading" data-aos="fade-up">
                    Your Needs, Our Priority: Buy, Sell, Lease with Ease
                </h1>
            </div>
        </div>
    </div>
</div>

<div class="section" id="hot-properties">
    <div class="container">
        <div class="row mb-5 align-items-center">
            <div class="col-lg-6">
                <h2 class="fw-bolder text-primary heading">
                    Hot Properties
                </h2>
            </div>

            <div class="col-lg-6 text-lg-end">
                <p>
                    <a href="{{ route('allProperties') }}" class="btn btn-primary py-3 px-4">View All
                        Properties</a>
                </p>
            </div>
        </div>

        <div class="container mt-5">
            <div class="row button-row">
                @foreach($cities as $city)
                    <div class="col-6 mb-3">
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
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
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
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-5">
                <h2 class="fw-bolder heading text-primary mb-4">
                    About Grandeur Realty
                </h2>
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
                    <img src="{{ asset('images/grandeur-realty-about.jpg') }}" alt="Image" class="img-fluid img-thumbnail" />
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
    </div>
</div>
@endsection