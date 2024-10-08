@extends('admin.layouts.app')

@section('contents')
<div class="container-fluid">
    <!-- Back Button -->
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mb-3">
        ← Go Home
    </a>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('success'))
                        <div class="alert alert-success" id="alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    @if(Session::has('failed'))
                        <div class="alert alert-danger" id="alert-failed" role="alert">
                            {{ Session::get('failed') }}
                        </div>
                    @endif

                    <h5 class="card-title text-warning fw-semibold mb-4">Update Profile</h5>

                    <form method="POST" action="{{ route('profile.updateProfile', auth()->user()->id) }}"
                        enctype="multipart/form-data" id="editUserForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', auth()->user()->name) }}" disabled>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username', auth()->user()->username) }}" disabled>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', auth()->user()->email) }}" disabled>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" disabled>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror" disabled>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-warning" id="editButton">Edit</button>
                            <button type="button" class="btn btn-danger me-2 d-none" id="cancelButton">Cancel</button>
                            <button type="button" class="btn btn-warning d-none" id="saveButton">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

    const editButton = document.getElementById('editButton');
    const saveButton = document.getElementById('saveButton');
    const cancelButton = document.getElementById('cancelButton');
    const inputs = document.querySelectorAll('input');
    const editUserForm = document.getElementById('editUserForm');

    const originalValues = {};
    inputs.forEach(input => {
        originalValues[input.id] = input.value;
    });

    editButton.addEventListener('click', function () {
        inputs.forEach(input => input.removeAttribute('disabled'));
        saveButton.classList.remove('d-none');
        cancelButton.classList.remove('d-none');
        editButton.classList.add('d-none');
    });

    cancelButton.addEventListener('click', function () {
        inputs.forEach(input => {
            input.setAttribute('disabled', 'disabled');
            input.value = originalValues[input.id];
        });

        const errorElements = document.querySelectorAll('.invalid-feedback');
        errorElements.forEach(errorElement => {
            errorElement.remove();
        });

        const inputElements = document.querySelectorAll('.is-invalid');
        inputElements.forEach(inputElement => {
            inputElement.classList.remove('is-invalid');
        });

        saveButton.classList.add('d-none');
        cancelButton.classList.add('d-none');
        editButton.classList.remove('d-none');
    });

    saveButton.addEventListener("click", async () => {
        saveButton.disabled = true;
        saveButton.textContent = 'Updating . . .';

        const formData = new FormData(editUserForm);

        const errorElements = document.querySelectorAll('.invalid-feedback');
        errorElements.forEach(errorElement => {
            errorElement.remove();
        });

        const inputElements = document.querySelectorAll('.is-invalid');
        inputElements.forEach(inputElement => {
            inputElement.classList.remove('is-invalid');
        });

        try {
            const response = await fetch('{{ route('profile.validateProfileForm', auth()->user()->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData,
            });

            const data = await response.json();

            if (data.message === 'Validation failed') {
                saveButton.disabled = false;
                saveButton.textContent = 'Update';

                for (const [key, value] of Object.entries(data.errors)) {
                    const input = document.querySelector(`[name="${key}"]`);
                    const error = document.createElement('div');
                    error.classList.add('invalid-feedback');
                    error.textContent = value;
                    input.classList.add('is-invalid');
                    input.parentNode.insertBefore(error, input.nextSibling);
                }
            } else if (data.message === 'Validation passed') {
                editUserForm.submit();
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