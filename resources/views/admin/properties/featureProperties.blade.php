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
            <h5 class="card-title pb-4">Properties Settings - Properties Features</h5>

            <div class="row" id="features-row">
                @foreach ($features as $index => $feature)
                    <div class="col-6 col-lg-3 mb-4">
                        <div class="box-feature shadow">
                            <span class="{{ $feature['icon'] }}"></span>
                            <h3 class="mb-3">{{ $feature['title'] }}</h3>
                            <p>{{ $feature['description'] }}</p>

                            <button class="btn btn-sm btn-warning" onclick="showEditForm({{ $index }})">Edit</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="edit-form-container" id="edit-form-container" style="display: none;">
                <hr>
                <h4 class="card-title text-warning fw-semibold mb-4">Edit Feature Form</h4>

                <form id="edit-feature-form" method="POST" action="">
                    @csrf

                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon"
                            name="icon" readonly>
                        @error('icon')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="icon-grid" id="icon-selection">
                            @foreach($icons as $icon)
                                <span class="{{ $icon }} icon-choice" onclick="selectIcon('{{ $icon }}')"></span>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title">
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description"></textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center justify-content-end">
                        <button type="button" class="btn btn-success me-2" id="save-feature-btn">Save Changes</button>
                        <button type="button" class="btn btn-danger" onclick="hideEditForm()">Cancel</button>
                    </div>
                </form>
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
    
    let features = @json($features); // Convert PHP array to JavaScript array
    const saveFeatureBtn = document.getElementById('save-feature-btn');

    function showEditForm(index) {
        const formContainer = document.getElementById('edit-form-container');

        const featureId = features[index].id;
        const form = document.getElementById('edit-feature-form');
        form.action = `{{ url('properties/saveEditPropertiesFeatures') }}/${featureId}`;

        // Populate the form fields with the selected feature's data
        document.getElementById('icon').value = features[index].icon;
        document.getElementById('title').value = features[index].title;
        document.getElementById('description').value = features[index].description;

        // Show the form
        formContainer.style.display = 'block';

        // Highlight the current icon
        highlightCurrentIcon(features[index].icon);
    }

    function hideEditForm() {
        document.getElementById('edit-form-container').style.display = 'none';
        resetIconSelection();
    }

    function selectIcon(iconClass) {
        document.getElementById('icon').value = iconClass;
        highlightCurrentIcon(iconClass);
    }

    function highlightCurrentIcon(iconClass) {
        const iconGrid = document.getElementById('icon-selection');
        iconGrid.querySelectorAll('.icon-choice').forEach(icon => {
            icon.classList.remove('selected');
        });

        const selectedIcon = iconGrid.querySelector(`.${iconClass}`);
        if (selectedIcon) {
            selectedIcon.classList.add('selected');
        }
    }

    function resetIconSelection() {
        document.querySelectorAll('.icon-choice').forEach(icon => {
            icon.classList.remove('selected');
        });

        const inputElements = document.querySelectorAll('.is-invalid');
        inputElements.forEach(inputElement => {
            inputElement.classList.remove('is-invalid');
        });
    }

    // AJAX call to validate and submit the edit form
    saveFeatureBtn.addEventListener('click', async () => {
        saveFeatureBtn.disabled = true;
        saveFeatureBtn.textContent = 'Saving...';

        const form = document.getElementById('edit-feature-form');
        const formData = new FormData(form);

        try {
            const response = await fetch('{{ route('properties.validateEditPropertiesFeaturesForm') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData,
            });

            const data = await response.json();

            if (data.message === 'Validation failed') {
                saveFeatureBtn.disabled = false;
                saveFeatureBtn.textContent = 'Save Changes';

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
                form.submit();
            }

        } catch (error) {
            console.error('Error:', error);
        }
    });
</script>
@endsection