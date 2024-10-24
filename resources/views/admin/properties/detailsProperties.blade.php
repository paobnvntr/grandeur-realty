<div class="modal fade" id="detailsModal-{{ $property->id }}" tabindex="-1"
    aria-labelledby="detailsModalLabel-{{ $property->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-3 shadow-lg">
            <div class="modal-header pb-2 border-bottom">
                <h4 class="modal-title text-info" id="detailsModalLabel-{{ $property->id }}">For
                    {{ ucfirst($property->property_status) }}:
                    {{ $property->size }} ft² {{ ucfirst($property->property_type) }} in {{ $property->city }}
                </h4>
                <button type="button" class="btn btn-outline-link" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Images Section -->
                @if(!empty($property->image)) <!-- Check if images are available -->
                                <div style="max-width: 60%; margin: 0 auto;">
                                    <div class="gallery-container mb-4 text-center">
                                        @php
                                            // Clean up the string and remove unwanted characters
                                            $cleanedImages = str_replace(['[', ']', '"'], '', $property->image);
                                            // Convert the cleaned string into an array by splitting on commas
                                            $images = explode(',', $cleanedImages);
                                        @endphp
                                        @if($images && is_array($images))
                                            <div class="current-image-container mb-2">
                                                <img id="currentImage-{{ $property->id }}"
                                                    src="{{ asset('uploads/property/' . $property->folder_name . '/' . trim($images[0])) }}"
                                                    class="img-fluid rounded" alt="Property Image" style="max-width: 50%; height: 350px;">
                                                <!-- Adjusted image size -->
                                            </div>
                                            <div class="navigation-buttons mb-3">
                                                <button type="button" class="btn btn-secondary"
                                                    id="prevImage-{{ $property->id }}">Previous</button>
                                                <button type="button" class="btn btn-secondary"
                                                    id="nextImage-{{ $property->id }}">Next</button>
                                            </div>
                                            <div class="row d-flex align-items-center justify-content-center"
                                                id="thumbnailContainer-{{ $property->id }}">
                                                @foreach($images as $index => $image)
                                                    <div class="col-3 mb-2 thumbnail-image p-0 ms-2 me-2 shadow-sm rounded"
                                                        data-index="{{ $index }}" data-property-id="{{ $property->id }}"
                                                        style="display: none; width: 100px; height: 100px; overflow: hidden">
                                                        <img src="{{ asset('uploads/property/' . $property->folder_name . '/' . trim($image)) }}"
                                                            class="img-fluid rounded shadow-sm" alt="Thumbnail"
                                                            style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>

                                                @endforeach
                                            </div>
                                        @else
                                            <p>No images available.</p>
                                        @endif
                                    </div>
                                </div>
                @endif

                <!-- Client Details Section -->
                <div class="client-details mb-4 ps-4">
                    <div class="d-flex align-items-center mb-4">
                        <h5 class="text-info me-4 mb-0">Personal Details</h5>
                        <button type="button" id="editButton_{{ $property->id }}" class="btn btn-warning btn-sm"
                            onclick="toggleEdit_{{ $property->id }}()">Edit Details</button>
                    </div>
                    <form action="" method="POST" id="updatePropertyForm" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-6">
                                <!-- Personal Details -->
                                <p class="ms-2"><strong>Client Type: <span class="text-danger d-none"
                                            id="required-icon">*</span></strong> <span
                                        id="clientType_{{ $property->id }}">{{ ucfirst($property->user_type) }}</span>
                                </p>
                                <p class="ms-2"><strong>Name: <span class="text-danger d-none"
                                            id="required-icon">*</span></strong> <span
                                        id="clientName_{{ $property->id }}">{{ \Illuminate\Support\Str::title($property->name) }}</span>
                                </p>
                                <p class="ms-2"><strong>Email: <span class="text-danger d-none"
                                            id="required-icon">*</span></strong> <span
                                        id="clientEmail_{{ $property->id }}">{{ $property->email }}</span></p>
                                <p class="ms-2"><strong>Cellphone Number:</strong> <span
                                        id="clientPhone_{{ $property->id }}">{{ $property->cellphone_number }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Property Details Section -->
                        <div class="property-details">
                            <h5 class="text-info mb-4">Property Details</h5>
                            <div class="row">
                                <div class="col-6">
                                    <p class="ms-2"><strong>Property Status: <span class="text-danger d-none"
                                                id="required-icon">*</span></strong> <span
                                            id="propertyStatus_{{ $property->id }}">{{ ucfirst($property->property_status) }}</span>
                                    </p>
                                    <p class="ms-2"><strong>Property Type: <span class="text-danger d-none"
                                                id="required-icon">*</span></strong> <span
                                            id="propertyType_{{ $property->id }}">{{ ucfirst($property->property_type) }}</span>
                                    </p>
                                    <p class="ms-2"><strong>City: <span class="text-danger d-none"
                                                id="required-icon">*</span></strong> <span
                                            id="propertyCity_{{ $property->id }}">{{ ucfirst($property->city) }}</span>
                                    </p>
                                    <p class="ms-2"><strong>Address: <span class="text-danger d-none"
                                                id="required-icon">*</span></strong> <span
                                            id="propertyAddress_{{ $property->id }}">{{ \Illuminate\Support\Str::title($property->address) }}</span>
                                    </p>
                                    <p class="ms-2"><strong>Size: <span class="text-danger d-none"
                                                id="required-icon">*</span></strong> <span
                                            id="propertySize_{{ $property->id }}">{{ $property->size }} sqm</span></p>
                                </div>
                                <div class="col-6">
                                    <p class="ms-2"><strong>Bedrooms:</strong> <span
                                            id="propertyBedrooms_{{ $property->id }}">{{ $property->bedrooms }}</span>
                                    </p>
                                    <p class="ms-2"><strong>Bathrooms:</strong> <span
                                            id="propertyBathrooms_{{ $property->id }}">{{ $property->bathrooms }}</span>
                                    </p>
                                    <p class="ms-2"><strong>Garage:</strong> <span
                                            id="propertyGarage_{{ $property->id }}">{{ $property->garage }}</span></p>
                                    <p class="ms-2"><strong>Price: <span class="text-danger d-none"
                                                id="required-icon">*</span></strong> <span
                                            id="propertyPrice_{{ $property->id }}">{{ $property->price }}</span></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <p class="ms-2"><strong>Description:</strong></p>
                                    <div style="max-height: 200px; overflow-y: auto;">
                                        <!-- Set a fixed height and make it scrollable -->
                                        <p class="ms-4"><span
                                                id="propertyDescription_{{ $property->id }}">{{ $property->description }}</span>
                                        </p>
                                        <!-- Long description will take full width -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-end">
                            <small class="text-danger me-4 d-none" id="error_no_changes_{{ $property->id }}"></small>
                            <button type="button" id="applyButton_{{ $property->id }}"
                                class="btn btn-warning d-none me-2" onclick="confirmUpdate('{{ $property->id }}')">Apply
                                Changes</button>
                            <button type="button" id="cancelButton_{{ $property->id }}" class="btn btn-danger d-none"
                                onclick="cancelEdit_{{ $property->id }}()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer border-top d-flex justify-content-between align-items-center">
                <p class="mb-0" style="font-size: 0.7rem; opacity: 0.7;">
                    <strong>Date Approved:</strong> {{ $property->created_at->format('Y-m-d H:i:s') }}
                </p>

                @php
                    $analytics = $property->analytics;
                    $views = $analytics ? $analytics->views : 0;
                    $interactions = $analytics ? $analytics->interactions : 0;
                @endphp

                <div>
                    <strong>Views:</strong> <span class="text-primary">{{ $views }}</span> |
                    <strong>Interactions:</strong> <span class="text-secondary">{{ $interactions }}</span>
                </div>

                <div>
                    @if ($property->status === 'sold')
                        <!-- Show only the 'Available' button if the property is sold -->
                        <button type="button" class="btn btn-success" id="available-button-{{ $property->id }}"
                            onclick="confirmAvailable('{{ $property->id }}')">
                            Mark as Available
                        </button>
                    @else
                        <!-- Show only the 'Sold' button if the property is available -->
                        <button type="button" class="btn btn-success" id="sold-button-{{ $property->id }}"
                            onclick="confirmSold('{{ $property->id }}')">
                            Mark as Sold
                        </button>
                    @endif

                    <button type="button" class="ms-2 btn btn-danger" id="delete-button-{{ $property->id }}"
                        onclick="confirmDelete('{{ $property->id }}')">
                        Delete Property
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Store original values globally to revert when cancel is clicked
    let originalValues_{{ $property->id }} = {};

    function toggleEdit_{{ $property->id }}() {
        // Store original values to revert when "Cancel" is clicked
        originalValues_{{ $property->id }} = {
            userType: document.getElementById('clientType_{{ $property->id }}').innerText,
            name: document.getElementById('clientName_{{ $property->id }}').innerText,
            email: document.getElementById('clientEmail_{{ $property->id }}').innerText,
            phone: document.getElementById('clientPhone_{{ $property->id }}').innerText,
            propertyStatus: document.getElementById('propertyStatus_{{ $property->id }}').innerText,
            propertyType: document.getElementById('propertyType_{{ $property->id }}').innerText,
            city: document.getElementById('propertyCity_{{ $property->id }}').innerText,
            address: document.getElementById('propertyAddress_{{ $property->id }}').innerText,
            size: document.getElementById('propertySize_{{ $property->id }}').innerText,
            bedrooms: document.getElementById('propertyBedrooms_{{ $property->id }}').innerText,
            bathrooms: document.getElementById('propertyBathrooms_{{ $property->id }}').innerText,
            garage: document.getElementById('propertyGarage_{{ $property->id }}').innerText,
            price: document.getElementById('propertyPrice_{{ $property->id }}').innerText,
            description: document.getElementById('propertyDescription_{{ $property->id }}').innerText,
        };

        const userType = originalValues_{{ $property->id }}.userType.trim();
        const userTypeOptions = [
            { value: "broker", label: "Broker" },
            { value: "seller", label: "Seller" }
        ];

        const userTypeSelectOptions = userTypeOptions.map(option => {
            if (capitalizeFirstLetter(option.value) === userType) {
                return `<option value="${option.value}" selected>${capitalizeFirstLetter(option.label)}</option>`;
            }
            return `<option value="${option.value}">${capitalizeFirstLetter(option.label)}</option>`;
        }).join('');

        document.getElementById('clientType_{{ $property->id }}').innerHTML = `
    <select id="input_user_type_{{ $property->id }}" name="user_type" class="form-select">
        ${userTypeSelectOptions}
    </select>
    <small class="text-danger" id="error_user_type_{{ $property->id }}"></small>`;


        document.getElementById('clientName_{{ $property->id }}').innerHTML = `
    <input type="text" id="input_name_{{ $property->id }}" name="name" class="form-control" value="${originalValues_{{ $property->id }}.name.trim()}">
    <small class="text-danger" id="error_name_{{ $property->id }}"></small>`;

        document.getElementById('clientEmail_{{ $property->id }}').innerHTML = `
    <input type="email" id="input_email_{{ $property->id }}" name="email" class="form-control" value="${originalValues_{{ $property->id }}.email.trim()}">
    <small class="text-danger" id="error_email_{{ $property->id }}"></small>`;

        document.getElementById('clientPhone_{{ $property->id }}').innerHTML = `
    <input type="text" id="input_cellphone_number_{{ $property->id }}" name="cellphone_number" class="form-control" value="${originalValues_{{ $property->id }}.phone.trim()}">
    <small class="text-danger" id="error_cellphone_number_{{ $property->id }}"></small>`;

        // Property Details
        const propertyStatus = originalValues_{{ $property->id }}.propertyStatus.trim();
        const statusOptions = [
            { value: "sale", label: "For Sale" },
            { value: "rent", label: "For Rent" }
        ];

        const statusSelectOptions = statusOptions.map(option => {
            const selected = capitalizeFirstLetter(option.value) === propertyStatus ? 'selected' : '';
            return `<option value="${option.value}" ${selected}>${option.label}</option>`;
        }).join('');

        document.getElementById('propertyStatus_{{ $property->id }}').innerHTML = `
    <select id="input_property_status_{{ $property->id }}" name="property_status" class="form-select">
        ${statusSelectOptions}
    </select>
    <small class="text-danger" id="error_property_status_{{ $property->id }}"></small>`;

        const propertyType = originalValues_{{ $property->id }}.propertyType.trim();
        const options = [
            { value: "house", label: "House" },
            { value: "condominium", label: "Condominium" },
            { value: "townhouse", label: "Townhouse" },
            { value: "apartment", label: "Apartment" },
            { value: "land", label: "Land" },
            { value: "commercial", label: "Commercial" }
        ];

        // Create the select options, excluding the selected type
        const selectOptions = options.map(option => {
            if (capitalizeFirstLetter(option.value) === propertyType) {
                return `<option value="${option.value}" selected>${capitalizeFirstLetter(option.label)}</option>`;
            }
            return `<option value="${option.value}">${capitalizeFirstLetter(option.label)}</option>`;
        }).join('');

        document.getElementById('propertyType_{{ $property->id }}').innerHTML = `
    <select id="input_property_type_{{ $property->id }}" name="property_type" class="form-select">
        ${selectOptions}
    </select>
    <small class="text-danger" id="error_property_type_{{ $property->id }}"></small>`;

        const city = originalValues_{{ $property->id }}.city.trim();
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

        const citySelectOptions = cities.map(option => {
            const selected = option === city ? 'selected' : '';
            return `<option value="${option}" ${selected}>${option}</option>`;
        }).join('');

        document.getElementById('propertyCity_{{ $property->id }}').innerHTML = `
    <select id="input_city_{{ $property->id }}" name="city" class="form-select">
        ${citySelectOptions}
    </select>
    <small class="text-danger" id="error_city_{{ $property->id }}"></small>`;

        document.getElementById('propertyAddress_{{ $property->id }}').innerHTML = `
    <input type="text" id="input_address_{{ $property->id }}" name="address" class="form-control" value="${originalValues_{{ $property->id }}.address.trim()}">
    <small class="text-danger" id="error_address_{{ $property->id }}"></small>`;

        document.getElementById('propertySize_{{ $property->id }}').innerHTML = `
    <input type="text" id="input_size_{{ $property->id }}" name="size" class="form-control" value="${originalValues_{{ $property->id }}.size.trim().replace(' sqm', '')}">
    <small class="text-danger" id="error_size_{{ $property->id }}"></small>`;

        document.getElementById('propertyBedrooms_{{ $property->id }}').innerHTML = `
    <input type="number" id="input_bedrooms_{{ $property->id }}" name="bedrooms" class="form-control" value="${originalValues_{{ $property->id }}.bedrooms.trim()}">
    <small class="text-danger" id="error_bedrooms_{{ $property->id }}"></small>`;

        document.getElementById('propertyBathrooms_{{ $property->id }}').innerHTML = `
    <input type="number" id="input_bathrooms_{{ $property->id }}" name="bathrooms" class="form-control" value="${originalValues_{{ $property->id }}.bathrooms.trim()}">
    <small class="text-danger" id="error_bathrooms_{{ $property->id }}"></small>`;

        document.getElementById('propertyGarage_{{ $property->id }}').innerHTML = `
    <input type="number" id="input_garage_{{ $property->id }}" name="garage" class="form-control" value="${originalValues_{{ $property->id }}.garage.trim()}">
    <small class="text-danger" id="error_garage_{{ $property->id }}"></small>`;

        document.getElementById('propertyPrice_{{ $property->id }}').innerHTML = `
    <input type="text" id="input_price_{{ $property->id }}" name="price" class="form-control" value="${originalValues_{{ $property->id }}.price.trim()}">
    <small class="text-danger" id="error_price_{{ $property->id }}"></small>`;

        document.getElementById('propertyDescription_{{ $property->id }}').innerHTML = `
    <textarea id="input_description_{{ $property->id }}" name="description" class="form-control" rows="3">${originalValues_{{ $property->id }}.description.trim()}</textarea>
    <small class="text-danger" id="error_description_{{ $property->id }}"></small>`;

        // Toggle buttons
        document.getElementById('editButton_{{ $property->id }}').classList.add('d-none');
        document.getElementById('cancelButton_{{ $property->id }}').classList.remove('d-none');
        document.getElementById('applyButton_{{ $property->id }}').classList.remove('d-none');

        // Disable Button
        if ('{{ $property->status }}' === 'sold') {
            document.getElementById('available-button-{{ $property->id }}').disabled = true;
        } else {
            document.getElementById('sold-button-{{ $property->id }}').disabled = true;
        }
        document.getElementById('delete-button-{{ $property->id }}').disabled = true;

        // Show required icon
        document.querySelectorAll('.text-danger').forEach(icon => icon.classList.remove('d-none'));
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function cancelEdit_{{ $property->id }}() {
        // Revert back to original values without reloading
        document.getElementById('clientType_{{ $property->id }}').innerHTML = originalValues_{{ $property->id }}.userType;
        document.getElementById('clientName_{{ $property->id }}').innerHTML = originalValues_{{ $property->id }}.name;
        document.getElementById('clientEmail_{{ $property->id }}').innerHTML = originalValues_{{ $property->id }}.email;
        document.getElementById('clientPhone_{{ $property->id }}').innerHTML = originalValues_{{ $property->id }}.phone;

        document.getElementById('propertyStatus_{{ $property->id }}').innerHTML = originalValues_{{ $property->id }}.propertyStatus;
        document.getElementById('propertyType_{{ $property->id }}').innerHTML = originalValues_{{ $property->id }}.propertyType;
        document.getElementById('propertyCity_{{ $property->id }}').innerHTML = originalValues_{{ $property->id }}.city;
        document.getElementById('propertyAddress_{{ $property->id }}').innerHTML = originalValues_{{ $property->id }}.address;
        document.getElementById('propertySize_{{ $property->id }}').innerHTML = originalValues_{{ $property->id }}.size;
        document.getElementById('propertyBedrooms_{{ $property->id }}').innerHTML = originalValues_{{ $property->id }}.bedrooms;
        document.getElementById('propertyBathrooms_{{ $property->id }}').innerHTML = originalValues_{{ $property->id }}.bathrooms;
        document.getElementById('propertyGarage_{{ $property->id }}').innerHTML = originalValues_{{ $property->id }}.garage;
        document.getElementById('propertyPrice_{{ $property->id }}').innerHTML = originalValues_{{ $property->id }}.price;
        document.getElementById('propertyDescription_{{ $property->id }}').innerHTML = originalValues_{{ $property->id }}.description;

        // Toggle buttons
        document.getElementById('editButton_{{ $property->id }}').classList.remove('d-none');
        document.getElementById('cancelButton_{{ $property->id }}').classList.add('d-none');
        document.getElementById('applyButton_{{ $property->id }}').classList.add('d-none');

        // Enable Button
        if ('{{ $property->status }}' === 'sold') {
            document.getElementById('available-button-{{ $property->id }}').disabled = false;
        } else {
            document.getElementById('sold-button-{{ $property->id }}').disabled = false;
        }
        document.getElementById('delete-button-{{ $property->id }}').disabled = false;

        // Hide required icon
        document.querySelectorAll('.text-danger').forEach(icon => icon.classList.add('d-none'));

        // Clear any previous error messages
        clearAllErrors();
        const errorElement = document.getElementById('error_no_changes_{{ $property->id }}');
        errorElement.classList.add('d-none');
        errorElement.textContent = '';
    }

    function confirmUpdate(propertyId) {
        const button = document.getElementById('applyButton_' + propertyId);

        if (button.innerText === "Apply Changes") {
            // Change text to confirm
            button.innerText = "Confirm Changes?";
            // Change the onclick to submit the update
            button.setAttribute('onclick', `submitUpdate('${propertyId}')`);

            // Change cancel button to "Go Back" and reset the action
            const cancelButton = document.getElementById('cancelButton_' + propertyId);
            cancelButton.innerText = "Go Back";
            cancelButton.classList.remove('btn-danger');
            cancelButton.classList.add('btn-secondary');
            cancelButton.setAttribute('onclick', `cancelUpdate('${propertyId}')`);
        }
    }

    function cancelUpdate(propertyId) {
        // Reset the apply button to its original state
        const applyButton = document.getElementById('applyButton_' + propertyId);
        applyButton.innerText = "Apply Changes";
        applyButton.classList.add('btn-warning');
        applyButton.classList.remove('btn-secondary');

        // Reset the onclick function to confirm the update
        applyButton.setAttribute('onclick', `confirmUpdate('${propertyId}')`);

        // Reset the cancel button to its original state
        const cancelButton = document.getElementById('cancelButton_' + propertyId);
        cancelButton.innerText = "Cancel";
        cancelButton.classList.add('btn-danger');
        cancelButton.classList.remove('btn-secondary');

        // Reset the onclick function to hide the input fields and revert changes
        cancelButton.setAttribute('onclick', `cancelEdit_${propertyId}()`);
    }

    async function submitUpdate(propertyId) {
        const applyButton = document.getElementById('applyButton_' + propertyId);
        const cancelButton = document.getElementById('cancelButton_' + propertyId);

        // Disable both buttons to prevent multiple submissions
        applyButton.disabled = true;
        cancelButton.disabled = true;

        // Display a loading state with a dot animation
        let dots = 3;
        applyButton.innerText = 'Applying Changes . . .';
        const interval = setInterval(() => {
            dots = dots < 3 ? dots + 1 : 1;
            applyButton.innerText = `Applying Changes${' . '.repeat(dots)}`;
        }, 500);

        // Construct the route URL for update submission
        const route = '{{ url('properties/validatePropertiesUpdateForm') }}/' + propertyId;

        // Create a FormData object to collect form data
        const formData = new FormData();

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', csrfToken);

        // Add all the updated fields from the modal/form
        const fields = ['user_type', 'name', 'email', 'cellphone_number', 'property_status', 'property_type', 'city', 'address', 'size', 'bedrooms', 'bathrooms', 'garage', 'price', 'description'];

        fields.forEach(field => {
            const inputElement = document.getElementById('input_' + field + '_' + propertyId);
            if (inputElement) {
                formData.append(field, inputElement.value);
            }
        });

        // Clear any previous error messages
        clearAllErrors();

        // Send the update request via Fetch API
        try {
            const response = await fetch(route, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData,
            });

            const data = await response.json();

            const errorElement = document.getElementById('error_no_changes_' + propertyId);
            errorElement.classList.add('d-none');

            if (data.message === 'No changes detected') {
                if (errorElement) {
                    errorElement.textContent = 'No changes detected.';
                    errorElement.classList.remove('d-none');
                }

                clearInterval(interval);
                cancelUpdate(propertyId);

                applyButton.disabled = false;
                cancelButton.disabled = false;
            } else if (data.message === 'Validation passed') {
                // Construct the route URL for update submission
                const updateRoute = '{{ url('properties/updateProperties', ) }}/' + propertyId;

                // Create a new FormData object for the update submission
                const updateFormData = new FormData();
                updateFormData.append('_token', csrfToken);

                // Append the same fields for the update
                fields.forEach(field => {
                    const inputElement = document.getElementById('input_' + field + '_' + propertyId);
                    if (inputElement) {
                        updateFormData.append(field, inputElement.value);
                    }
                });

                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }

                // Send the update request
                const updateResponse = await fetch(updateRoute, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: updateFormData,
                });

                const updateData = await updateResponse.json();

                if (updateData.message === 'Property updated successfully.') {
                    // Handle success, e.g., redirect or show a success message
                    window.location.href = '{{ route('properties.List') }}';
                } else {
                    // Handle errors if needed
                    console.error('Update failed:', updateData);
                    alert('An error occurred while updating the property.'); // Display error
                }
            } else {
                handleFormResponse(data, propertyId);
                clearInterval(interval);
                applyButton.disabled = false;
                cancelButton.disabled = false;
            }
        } catch (error) {
            console.error('An error occurred:', error);
            alert('An error occurred while processing the request.'); // Display error
        }
    }

    function clearAllErrors() {
        document.querySelectorAll('.invalid-feedback').forEach(errorElement => errorElement.remove());
        document.querySelectorAll('.is-invalid').forEach(inputElement => inputElement.classList.remove('is-invalid'));
    }

    function handleFormResponse(data, propertyId) {
        if (data.message === 'Validation failed') {
            cancelUpdate(propertyId);

            // Loop through validation errors and display them
            for (const [key, value] of Object.entries(data.errors)) {
                const errorElementId = `error_${key}_${propertyId}`;
                const errorElement = document.getElementById(errorElementId);
                if (errorElement) {
                    errorElement.textContent = value[0]; // Assuming each error message is in an array
                }

                const inputElement = document.getElementById(`input_${key}_${propertyId}`);
                if (inputElement) {
                    displayError(inputElement);
                }
            }
        }
    }

    function displayError(inputElement) {
        const error = document.createElement('div');
        error.classList.add('invalid-feedback');
        inputElement.classList.add('is-invalid');
        inputElement.parentNode.insertBefore(error, inputElement.nextSibling);
    }

    // Function to set up image navigation for a specific property
    function setupImageNavigation(propertyId, images, folderName) {
        let currentIndex = 0; // Track the current image index
        const imagesPerPage = 4; // Number of images to display at once
        let currentPage = 0; // Track the current page of images

        // Update the current image display
        function updateCurrentImage() {
            const currentImage = document.getElementById('currentImage-' + propertyId);
            if (!Array.isArray(images)) {
                console.error('Image array is not defined or is not an array:', images);
                return; // Exit if images is invalid
            }

            const totalImages = images.length;
            if (currentIndex < 0 || currentIndex >= totalImages) {
                console.error('Current index is out of bounds:', currentIndex);
                currentIndex = 0; // Reset to a valid index
            }

            const imageSource = images[currentIndex].trim();
            currentImage.src = "{{ asset('uploads/property/') }}" + '/' + folderName + '/' + imageSource;

            // Display only the images for the current page
            displayThumbnails();

            // Update button states
            updateButtonStates(totalImages);
        }

        function displayThumbnails() {
            const thumbnailContainer = document.getElementById('thumbnailContainer-' + propertyId);
            const totalImages = images.length;

            // Hide all thumbnails
            const thumbnails = thumbnailContainer.getElementsByClassName('thumbnail-image');
            Array.from(thumbnails).forEach((thumbnail, index) => {
                thumbnail.style.display = 'none';
            });

            // Calculate start and end for the current page
            const start = currentPage * imagesPerPage;
            const end = Math.min(start + imagesPerPage, totalImages);

            // Show thumbnails for the current page
            for (let i = start; i < end; i++) {
                thumbnails[i].style.display = 'block';
            }
        }

        // Update the states of the navigation buttons
        function updateButtonStates(totalImages) {
            const prevButton = document.getElementById('prevImage-' + propertyId);
            const nextButton = document.getElementById('nextImage-' + propertyId);

            // Disable buttons based on current index and page
            prevButton.disabled = (currentIndex === 0 && currentPage === 0);
            nextButton.disabled = (currentIndex >= totalImages - 1 && currentPage >= Math.floor((totalImages - 1) / imagesPerPage));
        }

        // Previous button functionality
        document.getElementById('prevImage-' + propertyId).onclick = function () {
            if (currentIndex > 0) {
                currentIndex--; // Move to the previous image
                // Check if we need to switch to the previous set of images
                if (currentIndex < currentPage * imagesPerPage) {
                    currentPage--; // Move to the previous page
                    currentIndex = currentPage * imagesPerPage + (imagesPerPage - 1); // Set index to the last image of the previous page
                }
            }
            updateCurrentImage();
        };

        // Next button functionality
        document.getElementById('nextImage-' + propertyId).onclick = function () {
            const totalImages = images.length;
            if (currentIndex < totalImages - 1) {
                currentIndex++; // Move to the next image
                // Check if we need to switch to the next set of images
                if (currentIndex >= (currentPage + 1) * imagesPerPage) {
                    currentPage++; // Move to the next page
                    currentIndex = currentPage * imagesPerPage; // Set index to the first image of the next page
                }
            }
            updateCurrentImage();
        };

        // Thumbnail click functionality
        document.querySelectorAll('.thumbnail-image[data-property-id="' + propertyId + '"]').forEach((thumbnail) => {
            thumbnail.onclick = function () {
                currentIndex = parseInt(thumbnail.getAttribute('data-index'));
                currentPage = Math.floor(currentIndex / imagesPerPage); // Update the current page based on index
                updateCurrentImage();
            };
        });

        // Initial image display
        updateCurrentImage();
    }

    // Define unique variable names for images and folder names for each property
    const propertyId_{{ $property->id }} = {{ $property->id }};
    const images_{{ $property->id }} = @json($images); // Pass the images array to JavaScript
    const folderName_{{ $property->id }} = "{{ $property->folder_name }}";

    // Set up image navigation for this property
    setupImageNavigation(propertyId_{{ $property->id }}, images_{{ $property->id }}, folderName_{{ $property->id }});

    // Function to confirm marking the property as sold
    function confirmSold(propertyId) {
        const soldButton = document.getElementById('sold-button-' + propertyId);

        if (soldButton.innerText === "Mark as Sold") {
            // Change text to confirm
            soldButton.innerText = "Confirm Mark as Sold?";
            // Change the onclick to submit
            soldButton.setAttribute('onclick', `submitSold('${propertyId}')`);

            // Change delete button to "Cancel"
            const deleteButton = document.getElementById('delete-button-' + propertyId);
            deleteButton.innerText = "Go Back";
            deleteButton.classList.remove('btn-danger');
            deleteButton.classList.add('btn-secondary');
            deleteButton.setAttribute('onclick', `cancelAction('${propertyId}')`);
        }
    }

    // Function to confirm property deletion
    function confirmDelete(propertyId) {
        const propertyStatus = '{{ $property->status }}';
        const deleteButton = document.getElementById('delete-button-' + propertyId);

        if (deleteButton.innerText === "Delete Property") {
            // Change text to confirm
            deleteButton.innerText = "Confirm Delete?";
            // Change the onclick to submit
            deleteButton.setAttribute('onclick', `submitDelete('${propertyId}')`);

            if (propertyStatus === 'sold') {
                // Change available button to "Cancel"
                const availableButton = document.getElementById('available-button-' + propertyId);
                availableButton.innerText = "Go Back";
                availableButton.classList.remove('btn-success');
                availableButton.classList.add('btn-secondary');
                availableButton.setAttribute('onclick', `cancelAction('${propertyId}')`);
            } else {
                // Change sold button to "Cancel"
                const soldButton = document.getElementById('sold-button-' + propertyId);
                soldButton.innerText = "Go Back";
                soldButton.classList.remove('btn-success');
                soldButton.classList.add('btn-secondary');
                soldButton.setAttribute('onclick', `cancelAction('${propertyId}')`);
            }
        }
    }

    // Function to confirm marking the property as available
    function confirmAvailable(propertyId) {
        const availableButton = document.getElementById('available-button-' + propertyId);

        if (availableButton.innerText === "Mark as Available") {
            // Change text to confirm
            availableButton.innerText = "Confirm Mark as Available?";
            // Change the onclick to submit
            availableButton.setAttribute('onclick', `submitAvailable('${propertyId}')`);

            // Change delete button to "Cancel"
            const deleteButton = document.getElementById('delete-button-' + propertyId);
            deleteButton.innerText = "Go Back";
            deleteButton.classList.remove('btn-danger');
            deleteButton.classList.add('btn-secondary');
            deleteButton.setAttribute('onclick', `cancelAction('${propertyId}')`);
        }
    }

    // Function to cancel the current action and revert the buttons
    function cancelAction(propertyId) {
        const soldButton = document.getElementById('sold-button-' + propertyId);
        const availableButton = document.getElementById('available-button-' + propertyId);
        const deleteButton = document.getElementById('delete-button-' + propertyId);

        if (soldButton) {
            // Reset sold button
            soldButton.innerText = "Mark as Sold";
            soldButton.classList.add('btn-success');
            soldButton.classList.remove('btn-secondary');
            soldButton.setAttribute('onclick', `confirmSold('${propertyId}')`);
        }

        if (availableButton) {
            // Reset available button
            availableButton.innerText = "Mark as Available";
            availableButton.classList.add('btn-success');
            availableButton.classList.remove('btn-secondary');
            availableButton.setAttribute('onclick', `confirmAvailable('${propertyId}')`);
        }

        // Reset delete button
        deleteButton.innerText = "Delete Property";
        deleteButton.classList.add('btn-danger');
        deleteButton.classList.remove('btn-secondary');
        deleteButton.setAttribute('onclick', `confirmDelete('${propertyId}')`);
    }

    // Function to handle "Mark as Sold" submission
    function submitSold(propertyId) {
        const soldButton = document.getElementById('sold-button-' + propertyId);
        const deleteButton = document.getElementById('delete-button-' + propertyId);

        // Disable both buttons to prevent multiple submissions
        soldButton.disabled = true;
        deleteButton.disabled = true;

        // Change text to "Marking as Sold..."
        soldButton.innerText = "Marking as Sold...";

        // Construct the route URL for marking as sold submission
        const route = `{{ route('properties.markAsSold', ':id') }}`.replace(':id', propertyId);

        // Create a form to submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = route;

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const inputCsrf = document.createElement('input');
        inputCsrf.type = 'hidden';
        inputCsrf.name = '_token';
        inputCsrf.value = csrfToken;
        form.appendChild(inputCsrf);

        // Append the form to the body and submit it
        document.body.appendChild(form);
        form.submit();
    }

    // Function to handle "Mark as Available" submission
    function submitAvailable(propertyId) {
        const availableButton = document.getElementById('available-button-' + propertyId);
        const deleteButton = document.getElementById('delete-button-' + propertyId);

        // Disable both buttons to prevent multiple submissions
        availableButton.disabled = true;
        deleteButton.disabled = true;

        // Change text to "Marking as Available..."
        availableButton.innerText = "Marking as Available...";

        // Construct the route URL for marking as available submission
        const route = `{{ route('properties.markAsAvailable', ':id') }}`.replace(':id', propertyId);

        // Create a form to submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = route;

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const inputCsrf = document.createElement('input');
        inputCsrf.type = 'hidden';
        inputCsrf.name = '_token';
        inputCsrf.value = csrfToken;
        form.appendChild(inputCsrf);

        // Append the form to the body and submit it
        document.body.appendChild(form);
        form.submit();
    }

    // Function to handle "Delete Property" submission
    function submitDelete(propertyId, propertyStatus) {
        const deleteButton = document.getElementById('delete-button-' + propertyId);
        const soldButton = document.getElementById('sold-button-' + propertyId);
        const availableButton = document.getElementById('available-button-' + propertyId);

        // Disable the delete button to prevent multiple submissions
        deleteButton.disabled = true;

        // Change text to "Deleting..."
        deleteButton.innerText = "Deleting...";

        // Disable the sold/available button based on the property status
        if (propertyStatus === 'sold') {
            soldButton.disabled = true;
        } else {
            availableButton.disabled = true;
        }

        // Construct the route URL for property deletion submission
        const route = `{{ route('properties.delete', ':id') }}`.replace(':id', propertyId);

        // Create a form to submit the deletion request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = route;

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const inputCsrf = document.createElement('input');
        inputCsrf.type = 'hidden';
        inputCsrf.name = '_token';
        inputCsrf.value = csrfToken;
        form.appendChild(inputCsrf);

        // Append the form to the body and submit it
        document.body.appendChild(form);
        form.submit();
    }
</script>