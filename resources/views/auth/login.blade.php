@extends('auth.layouts.app')

@section('title', 'Grandeur Realty - Login')

@section('contents')
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
        class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="row justify-content-center w-100">
                <div class="col-md-8 col-lg-6 col-xxl-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="text-nowrap logo-img text-center d-block py-3 w-100">
                                <!-- <img src="../assets/images/logos/logo-light.svg" alt=""> -->
                                <h1>Log In</h1>
                            </div>
                            <p class="text-center">Welcome to Grandeur Realty!</p>
                            <form action="{{ route('loginAction') }}" method="POST" id="loginForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                        id="username" name="username" value="{{ old('username') }}"
                                        aria-describedby="username">
                                    @error('username')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" value="{{ old('password') }}">
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="d-flex align-items-center justify-content-end mb-4">
                                    <span class="text-danger fw-bold forgot-password"
                                        onclick="submitForgotPasswordForm()">Forgot Password?</span>
                                </div>

                                <button type="submit" class="btn btn-dark w-100 py-8 fs-4 mb-4">Log In</button>

                                <hr>

                                @if(Session::has('success'))
                                    <div class="alert alert-success" id="alert-success" role="alert">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif

                                @if(Session::has('error'))
                                    <div class="alert alert-danger" id="alert-danger" role="alert">
                                        {{ Session::get('error') }}
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    function submitForgotPasswordForm() {
        document.getElementById("loginForm").action = "{{ route('forgotPassword') }}";
        document.getElementById("loginForm").submit();
    }

    document.addEventListener('DOMContentLoaded', (event) => {
        let successAlert = document.getElementById('alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = "opacity 0.5s ease";
                successAlert.style.opacity = 0;
                setTimeout(() => { successAlert.remove(); }, 500);
            }, 4000);
        }
    });
</script>
@endsection