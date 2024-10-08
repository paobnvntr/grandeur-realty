@extends('admin.layouts.app')

@section('contents')
<div class="container-fluid">

    <a href="{{ route('users') }}" class="btn btn-outline-secondary mb-3">
        ← Go Back
    </a>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title fw-semibold mb-4">Add User Form</h4>
            <form action="{{ route('users.saveUser') }}" method="POST" enctype="multipart/form-data"
                id="createUserForm">
                @csrf
                <!-- Name and Username Fields -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="userName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-user @error('name') is-invalid @enderror"
                            id="userName" name="name">
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="userUsername" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                            id="userUsername" name="username">
                        @error('username')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="userEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="userEmail"
                        name="email" aria-describedby="emailHelp">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password and Confirm Password Fields -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="userPassword" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="userPassword" name="password">
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="userConfirmPassword" class="form-label">Confirm Password <span
                                class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="userConfirmPassword" name="password_confirmation">
                        @error('password_confirmation')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-end">
                    <button type="button" class="btn btn-dark" id="createUserBtn">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    const createUserBtn = document.getElementById('createUserBtn');

    createUserBtn.addEventListener("click", async () => {
        createUserBtn.disabled = true;
        createUserBtn.textContent = 'Adding . . .';

        const createUserForm = document.getElementById('createUserForm');
        const formData = new FormData(createUserForm);

        const errorElements = document.querySelectorAll('.invalid-feedback');
        errorElements.forEach(errorElement => {
            errorElement.remove();
        });

        const inputElements = document.querySelectorAll('.is-invalid');
        inputElements.forEach(inputElement => {
            inputElement.classList.remove('is-invalid');
        });

        try {
            const response = await fetch('{{ route('users.validateAddUserForm') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Add CSRF token
                },
                body: formData,
            });

            const data = await response.json();

            if (data.message === 'Validation failed') {
                createUserBtn.disabled = false;
                createUserBtn.textContent = 'Add';

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
                createUserForm.submit();
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