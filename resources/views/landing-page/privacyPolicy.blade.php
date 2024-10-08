@extends('landing-page.layouts.app')

@section('title', 'Grandeur Realty - Privacy Policy')

@section('contents')
<div class="hero page-inner overlay" style="background-image: url('images/hero_bg_1.jpg')">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-9 text-center mt-5">
                <h1 class="heading" data-aos="fade-up">Privacy Policy</h1>

                <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                    <ol class="breadcrumb text-center justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page" style="cursor: default;">
                            Privacy Policy
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <h1 class="mb-4">Privacy Policy</h1>

        <p class="ms-3">At Grandeur Realty (Listings), we value your privacy and are committed to protecting your
            personal information. This Privacy Policy outlines how we collect, use, and protect your data when you use
            our website.</p>

        <h4 class="ms-3">1. Information We Collect</h4>
        <p class="ms-5">We may collect the following types of information when you use our website:</p>
        <ul class="ms-5">
            <li><strong>Personal Information:</strong> Name, email address, phone number, and other contact details you
                provide when you list your property or contact us.</li>
            <li><strong>Property Information:</strong> Details about the property you wish to list, including location,
                price, and description.</li>
            <li><strong>Usage Information:</strong> Information about how you use our website, such as pages viewed,
                time spent, and interaction with features.</li>
            <li><strong>Cookies:</strong> We use cookies to enhance your experience by remembering your preferences and
                improving our services.</li>
        </ul>

        <h4 class="ms-3">2. How We Use Your Information</h4>
        <p class="ms-5">We may use the information we collect for the following purposes:</p>
        <ul class="ms-5">
            <li>To process your property listings and provide services related to buying and selling real estate.</li>
            <li>To communicate with you about inquiries, updates, and promotions.</li>
            <li>To improve our website and services based on your feedback and usage patterns.</li>
            <li>To ensure security and prevent fraud.</li>
        </ul>

        <h4 class="ms-3">3. Data Sharing and Disclosure</h4>
        <p class="ms-5">We do not sell or rent your personal information to third parties. However, we may share your
            information in the following situations:</p>
        <ul class="ms-5">
            <li>With service providers who help us operate our website and provide services to you (e.g., hosting,
                payment processing).</li>
            <li>If required by law, regulation, or legal process to protect our rights, privacy, or safety.</li>
            <li>In the event of a business transfer, merger, or acquisition, your data may be transferred as part of
                that transaction.</li>
        </ul>

        <h4 class="ms-3">4. Data Security</h4>
        <p class="ms-5">We take appropriate security measures to protect your personal information from unauthorized
            access, alteration, disclosure, or destruction. However, no internet transmission or electronic storage is
            completely secure. While we strive to protect your data, we cannot guarantee absolute security.</p>

        <h4 class="ms-3">5. Your Data Rights</h4>
        <p class="ms-5">You have the right to access, update, or delete your personal information. If you wish to
            exercise these rights, please contact us at <a
                class="text-primary" href="mailto:grandeurrealty.ph@gmail.com">grandeurrealty.ph@gmail.com</a>. We will respond to your
            request within a reasonable timeframe.</p>

        <h4 class="ms-3">6. Third-Party Links</h4>
        <p class="ms-5">Our website may contain links to third-party websites. We are not responsible for the privacy
            practices or content of these external sites. We encourage you to review their privacy policies when
            visiting these sites.</p>

        <h4 class="ms-3">7. Cookies Policy</h4>
        <p class="ms-5">We use cookies to personalize your experience and analyze website traffic. You can choose to
            disable cookies in your browser settings; however, some features of our website may not function properly
            without cookies.</p>

        <h4 class="ms-3">8. Changes to This Privacy Policy</h4>
        <p class="ms-5">We reserve the right to update or modify this Privacy Policy at any time. If we make material
            changes, we will notify you by posting an updated version on our website. Your continued use of our website
            following such changes constitutes acceptance of the revised policy.</p>

        <h4 class="ms-3">9. Contact Us</h4>
        <p class="ms-5">If you have any questions or concerns about this Privacy Policy, please contact us at <a
                class="text-primary" href="mailto:grandeurrealty.ph@gmail.com">grandeurrealty.ph@gmail.com</a>.</p>
    </div>
</div>
@endsection