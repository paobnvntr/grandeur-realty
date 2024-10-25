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
                <form action="{{ route('saveInquiry', $property->id) }}" method="POST" id="createInquiryForm">
                    @csrf
                    <input type="hidden" name="property_name"
                        value="For {{ ucfirst($property->property_status) }}: {{ $property->size }} ft² {{ ucfirst($property->property_type) }} in {{ $property->city }}">

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="cellphone_number" class="form-label">Cellphone Number <span
                                class="text-danger">* </span><span class="text-muted">(09---------)</span></label>
                        <input type="text" class="form-control @error('cellphone_number') is-invalid @enderror"
                            id="cellphone_number" name="cellphone_number">
                        @error('cellphone_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                        <select class="form-select @error('subject') is-invalid @enderror" id="subject" name="subject">
                            <option value="" selected>Select an inquiry subject</option>
                            <option value="Inquiry about Property">Inquiry about Property</option>
                            <option value="Request for Viewing">Request for Viewing</option>
                            <option value="Request for Information">Request for Information</option>
                            <option value="Negotiation Inquiry">Negotiation Inquiry</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('subject')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="message"
                            name="message" rows="4"></textarea>
                        @error('message')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="termsCheckbox" name="termsCheckbox">
                        <label class="form-check-label" for="termsCheckbox">I have read and agree to the <span
                                data-bs-toggle="modal" data-bs-target="#termsModal" id="termsAndConditionSpan"
                                class="text-decoration-underline">Terms and
                                Conditions</span> of Grandeur Realty.</label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" id="createInquiryBtn">Submit Inquiry</button>
                    </div>
                </form>
            </div>

            @include('landing-page.termsAndConditions')
        </div>
    </div>
</div>

<script>
    document.getElementById('cellphone_number').addEventListener('input', function (e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });

    const createInquiryBtn = document.getElementById('createInquiryBtn');

    createInquiryBtn.addEventListener("click", async () => {
        createInquiryBtn.disabled = true;
        let dots = 3;
        createInquiryBtn.textContent = 'Sending . . .';

        const interval = setInterval(() => {
            if (dots < 3) {
                dots++;
            } else {
                dots = 1;
            }
            createInquiryBtn.textContent = `Sending ${' . '.repeat(dots)}`;
        }, 500);

        const createInquiryForm = document.getElementById('createInquiryForm');
        const formData = new FormData(createInquiryForm);

        // Remove previous error messages and input highlights
        const errorElements = document.querySelectorAll('.invalid-feedback');
        errorElements.forEach(errorElement => {
            errorElement.remove();
        });

        const inputElements = document.querySelectorAll('.is-invalid');
        inputElements.forEach(inputElement => {
            inputElement.classList.remove('is-invalid');
        });

        try {
            const response = await fetch('{{ route('validateSendInquiryForm') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData,
            });

            const data = await response.json();

            if (data.message === 'Validation failed') {
                createInquiryBtn.disabled = false;
                clearInterval(interval);
                createInquiryBtn.textContent = 'Send Inquiry';

                // Show validation errors
                for (const [key, value] of Object.entries(data.errors)) {
                    const input = document.querySelector(`[name="${key}"]`);
                    const error = document.createElement('div');
                    error.classList.add('invalid-feedback');
                    error.textContent = value;
                    input.classList.add('is-invalid');
                    input.parentNode.insertBefore(error, input.nextSibling);
                }
            } else if (data.message === 'Validation passed') {
                createInquiryForm.submit();
                console.log('Validation passed');
            } else {
                console.log('Other errors');
            }

        } catch (error) {
            console.error('An error occurred:', error);
        }
    });

</script>

@endsection