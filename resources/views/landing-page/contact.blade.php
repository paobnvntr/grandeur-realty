@extends('landing-page.layouts.app')

@section('title', 'Grandeur Realty - Contact Us')

@section('contents')
<div class="hero page-inner overlay" style="background-image: url('images/hero_bg_1.jpg')">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-9 text-center mt-5">
                <h1 class="heading" data-aos="fade-up">Contact Us</h1>

                <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                    <ol class="breadcrumb text-center justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page" style="cursor: default;">
                            Contact
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-info">
                    <div class="email mt-4">
                        <i class="icon-envelope"></i>
                        <h4 class="mb-2">Email:</h4>
                        <p><a href="https://mail.google.com/mail/?view=cm&fs=1&to=grandeurrealty.ph@gmail.com"
                                target="_blank">grandeurrealty.ph@gmail.com</a></p>
                    </div>

                    <div class="phone mt-4">
                        <i class="icon-phone"></i>
                        <h4 class="mb-2">Call:</h4>
                        <p class="copyPhoneNumber text-decoration-underline" style="cursor: pointer;">+63 917 827 8812
                        </p>
                    </div>

                    <div class="mt-4">
                        <i class="icon-facebook"></i>
                        <h4 class="mb-2">Facebook:</h4>
                        <p><a href="https://www.facebook.com/grandeurlistings.ph" target="_blank">Grandeur Realty -
                                Facebook</a></p>
                    </div>

                    <div class="mt-4">
                        <i class="icon-instagram"></i>
                        <h4 class="mb-2">Instagram:</h4>
                        <p><a href="https://www.instagram.com/grandeurrealty.ph/" target="_blank">Grandeur Realty -
                                Instagram</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
                <form action="{{ route('saveContactUs') }}" method="POST" enctype="multipart/form-data"
                    id="createContactUsForm">
                    @csrf
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="name" class="form-label">Your Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6 mb-3">
                            <label for="email" class="form-label">Your Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject"
                                name="subject">
                            @error('subject')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" cols="30" rows="7"
                                class="form-control @error('message') is-invalid @enderror"></textarea>
                            @error('message')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" id="createContactUsBtn">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const createContactUsBtn = document.getElementById('createContactUsBtn');

    createContactUsBtn.addEventListener("click", async () => {
        createContactUsBtn.disabled = true;
        let dots = 3;
        createContactUsBtn.textContent = 'Sending . . .';

        const interval = setInterval(() => {
            if (dots < 3) {
                dots++;
            } else {
                dots = 1;
            }
            createContactUsBtn.textContent = `Sending ${' . '.repeat(dots)}`;
        }, 500);

        const createContactUsForm = document.getElementById('createContactUsForm');
        const formData = new FormData(createContactUsForm);

        const errorElements = document.querySelectorAll('.invalid-feedback');
        errorElements.forEach(errorElement => {
            errorElement.remove();
        });

        const inputElements = document.querySelectorAll('.is-invalid');
        inputElements.forEach(inputElement => {
            inputElement.classList.remove('is-invalid');
        });

        try {
            const response = await fetch('{{ route('validateContactUsForm') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData,
            });

            const data = await response.json();

            if (data.message === 'Validation failed') {
                createContactUsBtn.disabled = false;
                clearInterval(interval);
                createContactUsBtn.textContent = 'Send Message';

                const errorElements = document.querySelectorAll('.invalid-feedback');
                errorElements.forEach(errorElement => {
                    errorElement.remove();
                });

                const inputElements = document.querySelectorAll('.is-invalid');
                inputElements.forEach(inputElement => {
                    inputElement.classList.remove('is-invalid');
                });

                for (const [key, value] of Object.entries(data.errors)) {
                    const input = document.querySelector(`[name="${key}"]`);
                    const error = document.createElement('div');
                    error.classList.add('invalid-feedback');
                    error.textContent = value;
                    input.classList.add('is-invalid');
                    input.parentNode.insertBefore(error, input.nextSibling);
                }
            } else if (data.message === 'Validation passed') {
                createContactUsForm.submit();
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