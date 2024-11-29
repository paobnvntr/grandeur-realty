@extends('admin.layouts.app')

@section('contents')
<div class="container-fluid">
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

    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ route('properties.List') }}" class="btn btn-outline-secondary mb-3">
            ← Go Back
        </a>

    </div>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title fw-semibold mb-4 text-center">Add Property Form</h4>

            <div class="d-flex justify-content-center">
                <button class="btn btn-outline-dark" data-type="broker">Broker</button>
                <button class="btn btn-outline-dark ms-3" data-type="seller">Seller</button>
            </div>

            <form action="{{ route('properties.saveProperties') }}" method="POST" enctype="multipart/form-data"
                id="propertyForm" class="d-none">
                @csrf
                <h3 class="text-center fw-bold mt-4" id="formTitle">Broker Information</h3>
                <input type="hidden" name="user_type" id="userType" value="">

                <h5 class="mt-4">Personal Details:</h5>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror" required />
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-6 mb-3">
                        <label for="cell" class="form-label">Cellphone Number <span class="text-danger">*</span>
                            <span class="text-muted">(09---------)</span></label>
                        <input type="text" id="cell" name="cellphone_number" maxlength="11"
                            class="form-control @error('cell') is-invalid @enderror" />
                        @error('cell')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-6 mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror" required />
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <h5 class="mt-4">Property Information:</h5>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="propertyType" class="form-label">Property Type <span
                                class="text-danger">*</span></label>
                        <select id="propertyType" name="property_type"
                            class="form-select @error('propertyType') is-invalid @enderror" required>
                            <option value="" disabled selected>Select a property type</option>
                            <option value="house">House</option>
                            <option value="condominium">Condominium</option>
                            <option value="townhouse">Townhouse</option>
                            <option value="apartment">Apartment</option>
                            <option value="land">Land</option>
                            <option value="commercial">Commercial</option>
                        </select>
                        @error('propertyType')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                        <select id="city" name="city" class="form-select @error('city') is-invalid @enderror" required>
                            <option value="" disabled selected>Select a city</option>
                        </select>
                        @error('city')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="address" class="form-label">Full Address <span class="text-danger">*</span></label>
                        <input type="text" id="address" name="address"
                            class="form-control @error('address') is-invalid @enderror" required />
                        @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-6 mb-3">
                        <label for="size" class="form-label">Size (Square Meters) <span
                                class="text-danger">*</span></label>
                        <input type="number" id="size" name="size"
                            class="form-control @error('size') is-invalid @enderror" required />
                        @error('size')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-6 mb-3">
                        <label for="propertyStatus" class="form-label">Property Status <span
                                class="text-danger">*</span></label>
                        <select id="propertyStatus" name="property_status"
                            class="form-select @error('propertyStatus') is-invalid @enderror" required>
                            <option value="" disabled selected>Select status</option>
                            <option value="sale">For Sale</option>
                            <option value="rent">For Rent</option>
                        </select>
                        @error('propertyStatus')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12 mb-3 d-none" id="priceField">
                        <label for="price" class="form-label" id="priceLabel">Selling Price (₱)<span
                                class="text-danger">*</span></label>
                        <input type="number" id="price" name="price"
                            class="form-control @error('price') is-invalid @enderror" />
                        @error('price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-4 mb-3">
                        <label for="bedrooms" class="form-label">Bedroom(s)</label>
                        <input type="number" id="bedrooms" name="bedrooms" class="form-control"
                            value="{{ old('bedrooms') ?? 0 }}" />
                        @error('bedrooms')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-4 mb-3">
                        <label for="bathrooms" class="form-label">Bathroom(s)</label>
                        <input type="number" id="bathrooms" name="bathrooms" class="form-control"
                            value="{{ old('bathrooms') ?? 0 }}" />
                        @error('bathrooms')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-4 mb-3">
                        <label for="garage" class="form-label">Garage(s)</label>
                        <input type="number" id="garage" name="garage" class="form-control"
                            value="{{ old('garage') ?? 0 }}" />
                        @error('garage')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control"
                            rows="5">{{ old('description') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-6 mb-3">
                        <label for="image" class="form-label">Property Image(s) <span
                                class="text-danger">*</span></label>
                        <input type="file" id="image" name="image[]"
                            class="form-control @error('image') is-invalid @enderror" multiple
                            accept=".png, .jpeg, .jpg" />
                        @error('image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <div id="imagePreview" class="row mt-3"></div>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-dark" id="addPropertyBtn">Add Property</button>
                    </div>
                </div>
            </form>
        </div>
        @include('admin.properties.imagePreview')
    </div>
</div>

<script>
    document.getElementById('cell').addEventListener('input', function (e) {
        e.target.value = e.target.value.replace(/\D/g, '');
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

    const cities = [
        "Alaminos", "Angeles", "Antipolo", "Bacolod", "Bacoor", "Bago", "Baguio", "Bais", "Balanga", "Batac",
        "Batangas", "Bayawan", "Baybay", "Bayugan", "Biñan", "Bislig", "Bogo", "Borongan", "Butuan", "Cabadbaran",
        "Cabanatuan", "Cabuyao", "Cadiz", "Cagayan de Oro", "Calamba", "Calapan", "Calbayog", "Caloocan", "Candon",
        "Canlaon", "Carcar", "Catbalogan", "Cauayan", "Cavite", "Cebu", "Cotabato", "Dagupan", "Danao", "Dapitan",
        "Dasmariñas", "Davao", "Digos", "Dipolog", "Dumaguete", "El Salvador", "Escalante", "Gapan", "General Santos",
        "General Trias", "Gingoog", "Guihulngan", "Himamaylan", "Ilagan", "Iligan", "Iloilo", "Imus", "Iriga", "Isabela",
        "Kabankalan", "Kidapawan", "Koronadal", "La Carlota", "Lamitan", "Laoag", "Lapu-Lapu", "Las Piñas", "Legazpi",
        "Ligao", "Lipa", "Lucena", "Maasin", "Mabalacat", "Makati", "Malabon", "Malaybalay", "Malolos", "Mandaluyong",
        "Mandaue", "Manila", "Marawi", "Marikina", "Masbate", "Mati", "Meycauayan", "Muñoz", "Muntinlupa", "Naga",
        "Navotas", "Olongapo", "Ormoc", "Oroquieta", "Ozamiz", "Pagadian", "Palayan", "Panabo", "Parañaque", "Pasay",
        "Pasig", "Passi", "Puerto Princesa", "Quezon City", "Roxas", "Sagay", "Samal", "San Carlos (Negros Occidental)",
        "San Carlos (Pangasinan)", "San Fernando (La Union)", "San Fernando (Pampanga)", "San Jose", "San Jose del Monte",
        "San Juan", "San Pablo", "San Pedro", "Santa Rosa", "Santiago", "Silay", "Sipalay", "Sorsogon", "Surigao",
        "Tabaco", "Tabuk", "Tacloban", "Tacurong", "Tagaytay", "Tagbilaran", "Taguig", "Tagum", "Talisay (Cebu)",
        "Talisay (Negros Occidental)", "Tanauan", "Tandag", "Tangub", "Tanjay", "Tarlac", "Tayabas", "Toledo",
        "Trece Martires", "Tuguegarao", "Urdaneta", "Valencia", "Valenzuela", "Victorias", "Vigan", "Zamboanga"
    ];

    const citySelect = document.getElementById('city');

    cities.forEach(city => {
        const option = document.createElement('option');
        option.value = city;
        option.textContent = city;
        citySelect.appendChild(option);
    });

    document.querySelectorAll('.btn-outline-dark').forEach(button => {
        button.addEventListener('click', function () {
            const userType = this.getAttribute('data-type');
            const formTitle = document.getElementById('formTitle');
            const propertyForm = document.getElementById('propertyForm');

            document.getElementById('userType').value = userType;
            formTitle.textContent = userType === 'broker' ? 'Broker Information' : 'Seller Information';
            propertyForm.classList.remove('d-none');

            document.querySelectorAll('.btn-outline-dark').forEach(btn => {
                btn.style.opacity = "0.5";
                btn.classList.remove('active');
            });

            this.style.opacity = "1";
            this.classList.add('active');
        });
    });

    function updatePriceLabel(propertyStatus) {
        document.getElementById('priceLabel').innerHTML = propertyStatus === 'sale'
            ? 'Selling Price per SQM (₱) <span class="text-danger">*</span>'
            : 'Lease Rate per Month (₱) <span class="text-danger">*</span>';
    }

    document.getElementById('propertyStatus').addEventListener('change', function () {
        const priceField = document.getElementById('priceField');
        updatePriceLabel(this.value);
        priceField.classList.toggle('d-none', !this.value);
    });

    let uploadedImages = JSON.parse(localStorage.getItem('uploadedImages')) || [];
    let imageErrors = {};

    window.addEventListener('DOMContentLoaded', function () {
        if (uploadedImages.length > 0) {
            displayImagePreviews();
        }
    });

    window.addEventListener('beforeunload', function () {
        localStorage.removeItem('uploadedImages');
    });

    const imageInput = document.getElementById('image');
    imageInput.addEventListener('change', function (event) {
        const existingError = imageInput.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
            imageInput.classList.remove('is-invalid');
        }

        if (event.target.files.length + uploadedImages.length > 10) {
            displayError(imageInput, 'You can only upload up to 10 images.');
            event.target.value = ''; // Clear the file input
        } else {
            handleImageInput(event.target.files);
        }
    });

    function handleImageInput(files) {
        const newFiles = Array.from(files);
        uploadedImages = [...uploadedImages.map(img => img.file), ...newFiles].map(file => ({
            name: file.name,
            data: URL.createObjectURL(file),
            file: file
        }));

        updateImageInput();
        localStorage.setItem('uploadedImages', JSON.stringify(uploadedImages));
        displayImagePreviews();
    }

    function updateImageInput() {
        const dataTransfer = new DataTransfer();
        uploadedImages.forEach(img => dataTransfer.items.add(img.file));
        imageInput.files = dataTransfer.files;
    }

    function displayImagePreviews() {
        const imagePreviewContainer = document.getElementById('imagePreview');
        imagePreviewContainer.innerHTML = '';

        uploadedImages.forEach((fileObject, index) => {
            const col = document.createElement('div');
            col.classList.add('col-md-3', 'mb-3', 'text-center', 'position-relative');

            const img = document.createElement('img');
            img.src = fileObject.data;
            img.classList.add('img-fluid', 'rounded', 'shadow-sm', 'preview-image');
            img.style.height = '150px';
            img.style.objectFit = 'cover';
            img.style.cursor = 'pointer';
            img.addEventListener('click', function () {
                document.getElementById('modalImage').src = fileObject.data;
                new bootstrap.Modal(document.getElementById('imageModal')).show();
            });

            const removeBtn = document.createElement('button');
            removeBtn.classList.add('btn', 'btn-sm', 'text-danger', 'position-absolute', 'top-0', 'end-0');
            removeBtn.innerHTML = '&times;';
            removeBtn.onclick = function () {
                removeImage(index);
            };

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `image[${index}]`;
            hiddenInput.value = fileObject.file.name;

            col.appendChild(img);
            col.appendChild(removeBtn);
            col.appendChild(hiddenInput);
            imagePreviewContainer.appendChild(col);

            if (imageErrors[index]) {
                displayImageError(col, imageErrors[index], img);
            }
        });
    }

    function displayImageError(container, errorMessage, img) {
        const error = document.createElement('div');
        error.classList.add('invalid-feedback');
        error.style.fontSize = '0.875em';
        error.style.display = 'block';
        error.style.marginTop = '5px';
        error.textContent = errorMessage;

        container.appendChild(error);
        img.classList.add('border', 'border-danger');
    }

    function removeImage(index) {
        uploadedImages.splice(index, 1);
        imageErrors = {};
        updateImageInput();
        clearError(imageInput);
        displayImagePreviews();
    }

    function displayError(inputElement, errorMessage) {
        const error = document.createElement('div');
        error.classList.add('invalid-feedback');
        error.textContent = errorMessage;
        inputElement.classList.add('is-invalid');
        inputElement.parentNode.insertBefore(error, inputElement.nextSibling);
    }

    function clearError(inputElement) {
        const existingError = inputElement.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
            inputElement.classList.remove('is-invalid');
        }
    }

    const createAddPropertyBtn = document.getElementById('addPropertyBtn');

    createAddPropertyBtn.addEventListener("click", async () => {
        createAddPropertyBtn.disabled = true;
        let dots = 3;
        createAddPropertyBtn.textContent = 'Adding . . .';

        const interval = setInterval(() => {
            dots = dots < 3 ? dots + 1 : 1;
            createAddPropertyBtn.textContent = `Adding ${' . '.repeat(dots)}`;
        }, 500);

        const propertyForm = document.getElementById('propertyForm');
        const formData = new FormData(propertyForm);

        clearAllErrors();

        uploadedImages.forEach((fileObject, index) => {
            formData.append(`image[${index}]`, fileObject.file);
        });

        try {
            const response = await fetch('{{ route('properties.validateAddPropertiesForm') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData,
            });

            const data = await response.json();
            handleFormResponse(data);
            clearInterval(interval);

        } catch (error) {
            console.error('An error occurred:', error);
        }
    });

    function clearAllErrors() {
        document.querySelectorAll('.invalid-feedback').forEach(errorElement => errorElement.remove());
        document.querySelectorAll('.is-invalid').forEach(inputElement => inputElement.classList.remove('is-invalid'));
    }

    function handleFormResponse(data) {
        if (data.message === 'Validation failed') {
            createAddPropertyBtn.disabled = false;
            createAddPropertyBtn.textContent = 'Submit Listing';

            imageErrors = {};

            for (const [key, value] of Object.entries(data.errors)) {
                if (key === 'image') {
                    displayError(imageInput, value[0]);
                } else if (key.startsWith('image.')) {
                    imageErrors[key.split('.')[1]] = value[0];
                } else {
                    const input = document.querySelector(`[name="${key}"]`);
                    if (input) {
                        displayError(input, value[0]);
                    }
                }
            }

            displayImagePreviews();

        } else if (data.message === 'Validation passed') {
            localStorage.removeItem('uploadedImages');
            document.getElementById('propertyForm').submit();
        }
    }
</script>
@endsection