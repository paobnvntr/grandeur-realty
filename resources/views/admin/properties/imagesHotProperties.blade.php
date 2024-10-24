@extends('admin.layouts.app')

@section('contents')
<div class="container-fluid">

    <a href="{{ route('properties.settings') }}" class="btn btn-outline-secondary mb-3">
        ← Go Back
    </a>

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

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Properties Settings - Hot Properties Images</h5>

            <div class="card-body">
                <div class="row button-row">
                    @foreach($cities as $city)
                        <div class="col-md-6 mb-3">
                            <a type="button" class="city-card" style="background-image: url('{{ $city->image_url }}');"
                                onclick="showEditForm('{{ $city->id }}', '{{ $city->city }}')">
                                <div class="city-overlay">
                                    <span class="city-name">{{ $city->city }}</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Hidden form for editing image, shown below the rows -->
                <div id="edit-form" class="edit-form" style="display: none;">
                    <hr>
                    <form id="city-form" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="city" id="city-name-input">
                        <div class="form-group">
                            <label for="image-upload">Upload New Background Image for <strong><span
                                        id="city-name-label"></span></strong>:</span></label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                id="image-upload" accept=".jpg, .jpeg, .png">
                            @error('image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mt-3 d-flex align-items-center justify-content-end">
                            <button type="button" class="btn btn-success me-2" id="save-image-btn">Save</button>
                            <button type="button" class="btn btn-danger" onclick="hideEditForm()">Cancel</button>
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

    function showEditForm(cityId, cityName) {
        // Hide any currently shown form
        document.querySelectorAll('.edit-form').forEach(form => form.style.display = 'none');

        // Set the city ID in the form
        document.getElementById('city-name-input').value = cityName;

        // Set the city name in the form
        document.getElementById('city-name-label').innerText = cityName;

        // Set the form's action dynamically for the selected city
        document.getElementById('city-form').action = `{{ route('properties.saveEditHotPropertiesImages', '') }}/${cityName}`;

        // Show the form at the bottom
        document.getElementById('edit-form').style.display = 'block';

        const inputElements = document.querySelectorAll('.is-invalid');
        inputElements.forEach(inputElement => {
            inputElement.classList.remove('is-invalid');
        });
    }

    function hideEditForm() {
        // Hide the form
        document.getElementById('edit-form').style.display = 'none';

        const inputElements = document.querySelectorAll('.is-invalid');
        inputElements.forEach(inputElement => {
            inputElement.classList.remove('is-invalid');
        });
    }

    const saveImageBtn = document.getElementById('save-image-btn');

    saveImageBtn.addEventListener('click', async () => {
        saveImageBtn.disabled = true;
        saveImageBtn.textContent = 'Saving...';

        const form = document.getElementById('city-form');
        const formData = new FormData(form);

        try {
            const response = await fetch('{{ route('properties.validateEditHotPropertiesImagesForm') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData,
            });

            const data = await response.json();

            if (data.message === 'Validation failed') {
                saveImageBtn.disabled = false;
                saveImageBtn.textContent = 'Save';

                // Clear previous error messages
                const inputElements = document.querySelectorAll('.is-invalid');
                inputElements.forEach(inputElement => {
                    inputElement.classList.remove('is-invalid');
                    const errorFeedback = inputElement.parentNode.querySelector('.invalid-feedback');
                    if (errorFeedback) {
                        errorFeedback.remove();
                    }
                });

                // Show new error messages
                for (const [key, value] of Object.entries(data.errors)) {
                    const input = document.querySelector(`[name="${key}"]`);
                    const error = document.createElement('div');
                    error.classList.add('invalid-feedback');
                    error.textContent = value;
                    input.classList.add('is-invalid');
                    input.parentNode.insertBefore(error, input.nextSibling);
                }
            } else if (data.message === 'Validation passed') {
                form.submit();
            }

        } catch (error) {
            console.error('Error:', error);
            saveImageBtn.disabled = false;
            saveImageBtn.textContent = 'Save'; // Re-enable the button
        }
    });

</script>
@endsection