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
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-title">Properties Settings - Hot Properties</h5>
                @if ($cities->count() < 6)
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                        data-bs-target="#addHotPropertyModal" onclick="hideEditForm()">
                        Add Hot Properties
                    </button>
                @endif
            </div>

            @include('admin.properties.partials.addHotProperties')

            <div class="card-body">
                @php
                    $totalSlots = 6;
                @endphp

                <div class="row button-row">
                    @for ($i = 1; $i <= $totalSlots; $i++)
                                        @php
                                            $city = $cities->firstWhere('priority', $i);
                                        @endphp

                                        <div class="col-md-6 mb-3">
                                            <a type="button" class="city-card"
                                                style="background-image: url('{{ $city ? $city->image_url : '' }}');"
                                                onclick="{{ $city ? 'showEditForm(\'' . $city->id . '\', \'' . $city->city . '\')' : 'openAddModal(' . $i . ')' }}">

                                                <div class="ribbon bg-dark">#{{ $i }}</div>
                                                @if ($city)
                                                    <button type="button" class="btn btn-sm btn-danger remove-btn" data-bs-toggle="modal"
                                                        data-bs-target="#removeHotPropertyModal{{$city->id}}">
                                                        Remove
                                                    </button>

                                                    @include('admin.properties.partials.removeHotProperties')
                                                @endif
                                                <div class="city-overlay">
                                                    @if ($city)
                                                        <span class="city-name text-center">
                                                            @if (!is_null($city->title))
                                                                {{ $city->title }}
                                                                <p class="form-text text-muted">
                                                                    ({{ $city->city }})
                                                                </p>
                                                            @else
                                                                {{ $city->city }}
                                                            @endif
                                                        </span>
                                                    @else
                                                        <span class="city-name">No City</span>
                                                    @endif
                                                </div>
                                            </a>
                                        </div>
                    @endfor
                </div>

                <!-- Hidden form for editing image, shown below the rows -->
                <div id="edit-form" class="edit-form" style="display: none;">
                    <hr>
                    <form id="city-form" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="city" id="city-name-input">
                        <div class="form-group">
                            <label for="title" class="form-label">Custom Title</label>
                            <input type="text" name="title_edit"
                                class="form-control @error('title_edit') is-invalid @enderror" id="title-edit">
                            @error('title_edit')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <p class="form-text text-muted ms-2">
                                Must be unique <br>
                                Leave blank to use default title
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="image-upload">Upload New Background Image for <strong><span
                                        id="city-name-label"></span></strong>:</span> <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="image_edit"
                                class="form-control @error('image_edit') is-invalid @enderror"
                                id="image-upload-placeholder" accept=".jpg, .jpeg, .png" required>
                            @error('image_edit')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <p class="form-text text-muted ms-2">
                                Maximum file size: 5 MB <br>
                                Recommended size: 1366 x 696 pixels <br>
                                Acceptable formats: .jpg, .jpeg, .png
                            </p>
                        </div>
                        <div class="mt-3 d-flex align-items-center justify-content-end">
                            <button type="button" class="btn btn-warning me-2" id="save-image-btn">Edit Image</button>
                            <button type="button" class="btn btn-danger" onclick="hideEditForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .empty-city-card {
        background-color: #e0e0e0;
    }

    .ribbon {
        position: absolute;
        top: 10px;
        left: 10px;
        color: #fff;
        padding: 5px 10px;
        font-weight: bold;
        font-size: 14px;
        border-radius: 4px;
        z-index: 1;
    }

    .remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1;
    }
</style>

<script>
    function openAddModal(slot) {
        const slotField = document.getElementById('slot');
        slotField.value = slot;

        var modal = new bootstrap.Modal(document.getElementById('addHotPropertyModal'));
        modal.show();
        hideEditForm();
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
        saveImageBtn.textContent = 'Editing...';

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
                saveImageBtn.textContent = 'Edit Image';

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
            saveImageBtn.textContent = 'Edit Image'; // Re-enable the button
        }
    });

</script>
@endsection