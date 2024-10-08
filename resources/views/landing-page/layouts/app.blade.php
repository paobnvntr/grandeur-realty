<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('fonts/icomoon/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/flaticon/font/flaticon.css') }}">

    <link rel="stylesheet" href="{{ asset('css/landing-page/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing-page/tiny-slider.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing-page/style.css') }}">
</head>

<body>
    @include('landing-page.layouts.navbar')

    @yield('contents')

    @include('landing-page.layouts.footer')

    <a href="#" id="scrollToTopBtn" class="scroll-to-top">
        <span class="fas fa-chevron-up"></span>
    </a>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/landing-page/tiny-slider.js') }}"></script>
    <script src="{{ asset('js/landing-page/aos.js') }}"></script>
    <script src="{{ asset('js/landing-page/jquery.sticky.js') }}"></script>
    <script src="{{ asset('js/landing-page/navbar.js') }}"></script>
    <script src="{{ asset('js/landing-page/counter.js') }}"></script>
    <script src="{{ asset('js/landing-page/custom.js') }}"></script>
    <script>
        var scrollToTopBtn = document.getElementById("scrollToTopBtn");

        window.onscroll = function () {
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                scrollToTopBtn.style.display = "block";
            } else {
                scrollToTopBtn.style.display = "none";
            }
        };

        scrollToTopBtn.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        const elements = document.getElementsByClassName('copyPhoneNumber');
        Array.from(elements).forEach(function (element) {
            element.addEventListener('click', function () {
                const phoneNumber = '09178278812';
                const tempInput = document.createElement('input');
                tempInput.style.position = 'absolute';
                tempInput.style.left = '-9999px';
                tempInput.value = phoneNumber;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                
                let alert = document.createElement('div');
                alert.classList.add('alert', 'alert-success', 'floating-alert', 'fw-bold');
                alert.id = 'alert-success';
                alert.role = 'alert';
                alert.innerHTML = 'Phone number copied to clipboard!';
                document.body.appendChild(alert);

                setTimeout(() => {
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = 0;
                    setTimeout(() => { alert.remove(); }, 500);
                }, 4000);
            });
        });

        document.addEventListener('DOMContentLoaded', (event) => {
            let successAlert = document.getElementById('alert-success');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.transition = "opacity 0.5s ease";
                    successAlert.style.opacity = 0;
                    setTimeout(() => { successAlert.remove(); }, 500);
                }, 4000);
            }

            let failedAlert = document.getElementById('alert-failed');
            if (failedAlert) {
                setTimeout(() => {
                    failedAlert.style.transition = "opacity 0.5s ease";
                    failedAlert.style.opacity = 0;
                    setTimeout(() => { failedAlert.remove(); }, 500);
                }, 4000);
            }
        });
    </script>
</body>

</html>