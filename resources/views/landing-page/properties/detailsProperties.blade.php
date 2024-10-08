@extends('landing-page.layouts.app')

@section('title', 'Grandeur Realty - Details')

@section('contents')
<div class="hero page-inner overlay" style="background-image: url('../images/hero_bg_1.jpg')">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-9 text-center mt-5">
                <h1 class="heading" data-aos="fade-up">For {{ ucfirst($property->property_status) }}:
                    {{ $property->size }} ft² {{ ucfirst($property->property_type) }} in {{ $property->city }}
                </h1>

                <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                    <ol class="breadcrumb text-center justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('allProperties') }}">Properties</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('hotProperties', ['city' => $property->city]) }}">{{ $property->city }}</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <!-- Left column for images and details -->
        <div class="col-lg-8 mb-4">
            <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
                <!-- Main Carousel Images -->
                <div class="carousel-inner">
                    @foreach($property->image as $key => $image)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <img src="{{ asset('uploads/property/' . $property->folder_name . '/' . $image) }}"
                                class="d-block w-100" alt="Property Image {{ $key + 1 }}">
                        </div>
                    @endforeach
                </div>

                <!-- Carousel Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <!-- Thumbnail Navigation -->
            <div class="mt-3 ms-3 me-3 d-flex justify-content-between">
                @foreach($property->image as $key => $image)
                    <div class="thumbnail">
                        <img src="{{ asset('uploads/property/' . $property->folder_name . '/' . $image) }}"
                            class="img-fluid thumbnail-img" data-bs-target="#propertyCarousel" data-bs-slide-to="{{ $key }}"
                            alt="Thumbnail {{ $key + 1 }}">
                    </div>
                @endforeach
            </div>

            <!-- Property Description -->
            <h2 class="mt-4 h4 mb-4 fw-bold">For {{ ucfirst($property->property_status) }}:
                {{ $property->size }} ft² {{ ucfirst($property->property_type) }} in {{ $property->city }}
            </h2>
            <div class="row">
                <div class="col-8">
                    <p class="mb-2 text-muted">
                        <span class="icon-building me-2"></span>
                        {{ ucfirst($property->property_type) }}
                    </p>
                    <p class="text-muted">
                        <span class="icon-room text-danger me-2"></span>
                        {{ $property->city }}
                    </p>
                </div>
                <div class="col-4">
                    <p class="mb-2 text-muted">
                        <span class="icon-arrows-alt text-warning me-2"></span>
                        {{ $property->size }} ft²
                    </p>
                    <p class="text-muted">
                        <span class="icon-money text-success me-2"></span>
                        ₱{{ number_format($property->price) }}
                    </p>
                </div>
            </div>

            <hr>

            @if ($property->description)
                <p>{{ $property->description }}</p>
                <hr>
            @endif

            @if ($property->bedrooms > 0 || $property->bathrooms > 0 || $property->garage > 0)
                <h6 class="fw-bold">What this place offers</h6>
                <ul class="list-unstyled d-flex flex-wrap mb-4 justify-content-center">
                    @if ($property->bedrooms > 0)
                        <li class="me-3">
                            <span class="icon-check text-success me-2"></span>
                            {{ $property->bedrooms }} Bedrooms
                        </li>
                    @endif

                    @if ($property->bathrooms > 0)
                        <li class="me-3">
                            <span class="icon-check text-success me-2"></span>
                            {{ $property->bathrooms }} Bathrooms
                        </li>
                    @endif

                    @if ($property->garage > 0)
                        <li class="me-3">
                            <span class="icon-check text-success me-2"></span>
                            {{ $property->garage }} Garage
                        </li>
                    @endif
                </ul>
            @endif
        </div>

        <!-- Right column for inquiry form -->
        <div class="col-lg-4 mb-4">
            <div class="card p-4">
                <h4 class="fw-bold text-center mb-3">Inquiry Form</h4>
                <form action="#" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email_address" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email_address" name="email_address" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <select class="form-select" id="subject" name="subject" required>
                            <option value="" selected>Select an inquiry subject</option>
                            <option value="property_inquiry">Inquiry about Property</option>
                            <option value="request_viewing">Request for Viewing</option>
                            <option value="request_information">Request for Information</option>
                            <option value="negotiation">Negotiation Inquiry</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="inquiry_message" class="form-label">Message</label>
                        <textarea class="form-control" id="inquiry_message" name="inquiry_message" rows="4"
                            required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection