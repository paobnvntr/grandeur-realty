@extends('admin.layouts.app')

@section('contents')
<div class="container-fluid">
    <!-- Back Button -->
    <a href="{{ route('users') }}" class="btn btn-outline-secondary mb-3">
        ← Go Back
    </a>

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

            <h4 class="card-title text-warning fw-semibold mb-4">Edit User Form</h4>
            <form action="{{ route('users.updateUser', $user->id) }}" method="POST" enctype="multipart/form-data"
                id="editUserForm">
                @csrf

                <!-- Name and Username Fields -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="userName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-user @error('name') is-invalid @enderror"
                            id="userName" name="name" value="{{ old('name', $user->name) }}" disabled>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="userUsername" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                            id="userUsername" name="username" value="{{ old('username', $user->username) }}" disabled>
                        @error('username')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="userEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="userEmail"
                        name="email" value="{{ old('email', $user->email) }}" aria-describedby="emailHelp" disabled>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password and Confirm Password Fields -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="userPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="userPassword" name="password" disabled>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="userConfirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="userConfirmPassword" name="password_confirmation" disabled>
                        @error('password_confirmation')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Edit, Cancel and Submit Buttons -->
                <div class="text-end">
                    <button type="button" class="btn btn-warning" id="editToggleBtn">Edit</button>
                    <button type="button" class="btn btn-danger d-none" id="cancelEditBtn">Cancel</button>
                    <button type="button" class="btn btn-warning d-none" id="updateUserBtn">Update</button>
                </div>
            </form>
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

    const editToggleBtn = document.getElementById('editToggleBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const updateUserBtn = document.getElementById('updateUserBtn');
    const editUserForm = document.getElementById('editUserForm');
    const originalValues = {
        name: document.getElementById('userName').value,
        username: document.getElementById('userUsername').value,
        email: document.getElementById('userEmail').value
    };

    editToggleBtn.addEventListener("click", () => {
        document.querySelectorAll('#editUserForm input').forEach(input => {
            input.disabled = false;
        });

        editToggleBtn.classList.add('d-none');
        cancelEditBtn.classList.remove('d-none');
        updateUserBtn.classList.remove('d-none');
    });

    cancelEditBtn.addEventListener("click", () => {
        const errorElements = document.querySelectorAll('.invalid-feedback');
        errorElements.forEach(errorElement => {
            errorElement.remove();
        });

        const inputElements = document.querySelectorAll('.is-invalid');
        inputElements.forEach(inputElement => {
            inputElement.classList.remove('is-invalid');
        });

        document.querySelectorAll('#editUserForm input').forEach(input => {
            input.disabled = true;
        });

        document.getElementById('userName').value = originalValues.name;
        document.getElementById('userUsername').value = originalValues.username;
        document.getElementById('userEmail').value = originalValues.email;
        document.getElementById('userPassword').value = '';
        document.getElementById('userConfirmPassword').value = '';

        editToggleBtn.classList.remove('d-none');
        cancelEditBtn.classList.add('d-none');
        updateUserBtn.classList.add('d-none');
    });

    updateUserBtn.addEventListener("click", async () => {
        updateUserBtn.disabled = true;
        updateUserBtn.textContent = 'Updating . . .';

        const formData = new FormData(editUserForm);

        // Clear previous errors
        const errorElements = document.querySelectorAll('.invalid-feedback');
        errorElements.forEach(errorElement => {
            errorElement.remove();
        });

        const inputElements = document.querySelectorAll('.is-invalid');
        inputElements.forEach(inputElement => {
            inputElement.classList.remove('is-invalid');
        });

        try {
            const response = await fetch('{{ route('users.validateEditUserForm', $user->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData,
            });

            const data = await response.json();

            if (data.message === 'Validation failed') {
                updateUserBtn.disabled = false;
                updateUserBtn.textContent = 'Update';

                // Display validation errors
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