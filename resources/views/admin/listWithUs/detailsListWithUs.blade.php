<div class="modal fade" id="detailsModal-{{ $listing->id }}" tabindex="-1"
    aria-labelledby="detailsModalLabel-{{ $listing->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-3 shadow-lg">
            <div class="modal-header pb-2 border-bottom">
                <h4 class="modal-title text-info" id="detailsModalLabel-{{ $listing->id }}">Listing Details</h4>
                <button type="button" class="btn btn-outline-link" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Images Section -->
                @if(!empty($listing->image)) <!-- Check if images are available -->
                                <div style="max-width: 60%; margin: 0 auto;">
                                    <div class="gallery-container mb-4 text-center">
                                        @php
                                            // Clean up the string and remove unwanted characters
                                            $cleanedImages = str_replace(['[', ']', '"'], '', $listing->image);
                                            // Convert the cleaned string into an array by splitting on commas
                                            $images = explode(',', $cleanedImages);
                                        @endphp
                                        @if($images && is_array($images))
                                            <div class="current-image-container mb-2">
                                                <img id="currentImage-{{ $listing->id }}"
                                                    src="{{ asset('uploads/list-with-us/' . $listing->folder_name . '/' . trim($images[0])) }}"
                                                    class="img-fluid rounded" alt="Property Image" style="max-width: 50%; height: 350px;">
                                                <!-- Adjusted image size -->
                                            </div>
                                            <div class="navigation-buttons mb-3">
                                                <button type="button" class="btn btn-secondary"
                                                    id="prevImage-{{ $listing->id }}">Previous</button>
                                                <button type="button" class="btn btn-secondary"
                                                    id="nextImage-{{ $listing->id }}">Next</button>
                                            </div>
                                            <div class="row d-flex align-items-center justify-content-center"
                                                id="thumbnailContainer-{{ $listing->id }}">
                                                @foreach($images as $index => $image)
                                                    <div class="col-3 mb-2 thumbnail-image p-0 ms-2 me-2 shadow-sm rounded"
                                                        data-index="{{ $index }}" data-listing-id="{{ $listing->id }}"
                                                        style="display: none; width: 100px; height: 100px; overflow: hidden">
                                                        <img src="{{ asset('uploads/list-with-us/' . $listing->folder_name . '/' . trim($image)) }}"
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
                        <button type="button" id="editButton_{{ $listing->id }}" class="btn btn-warning btn-sm"
                            onclick="toggleEdit_{{ $listing->id }}()">Edit Details</button>
                    </div>
                    <form action="" method="POST" id="updateListForm" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-6">
                                <!-- Personal Details -->
                                <p class="ms-2"><strong>Client Type: <span class="text-danger d-none"
                                            id="required-icon">*</span></strong> <span
                                        id="clientType_{{ $listing->id }}">{{ ucfirst($listing->user_type) }}</span></p>
                                <p class="ms-2"><strong>Name: <span class="text-danger d-none"
                                            id="required-icon">*</span></strong> <span
                                        id="clientName_{{ $listing->id }}">{{ \Illuminate\Support\Str::title($listing->name) }}</span>
                                </p>
                                <p class="ms-2"><strong>Email: <span class="text-danger d-none"
                                            id="required-icon">*</span></strong> <span
                                        id="clientEmail_{{ $listing->id }}">{{ $listing->email }}</span></p>
                                <p class="ms-2"><strong>Cellphone Number:</strong> <span
                                        id="clientPhone_{{ $listing->id }}">{{ $listing->cellphone_number }}</span></p>
                            </div>
                        </div>

                        <!-- Property Details Section -->
                        <div class="property-details">
                            <h5 class="text-info mb-4">Property Details</h5>
                            <div class="row">
                                <div class="col-6">
                                    <p class="ms-2"><strong>Property Status: <span class="text-danger d-none"
                                                id="required-icon">*</span></strong> <span
                                            id="propertyStatus_{{ $listing->id }}">{{ ucfirst($listing->property_status) }}</span>
                                    </p>
                                    <p class="ms-2"><strong>Property Type: <span class="text-danger d-none"
                                                id="required-icon">*</span></strong> <span
                                            id="propertyType_{{ $listing->id }}">{{ ucfirst($listing->property_type) }}</span>
                                    </p>
                                    <p class="ms-2"><strong>City: <span class="text-danger d-none"
                                                id="required-icon">*</span></strong> <span
                                            id="propertyCity_{{ $listing->id }}">{{ ucfirst($listing->city) }}</span>
                                    </p>
                                    <p class="ms-2"><strong>Address: <span class="text-danger d-none"
                                                id="required-icon">*</span></strong> <span
                                            id="propertyAddress_{{ $listing->id }}">{{ \Illuminate\Support\Str::title($listing->address) }}</span>
                                    </p>
                                    <p class="ms-2"><strong>Size: <span class="text-danger d-none"
                                                id="required-icon">*</span></strong> <span
                                            id="propertySize_{{ $listing->id }}">{{ $listing->size }} sqm</span></p>
                                </div>
                                <div class="col-6">
                                    <p class="ms-2"><strong>Bedrooms:</strong> <span
                                            id="propertyBedrooms_{{ $listing->id }}">{{ $listing->bedrooms }}</span></p>
                                    <p class="ms-2"><strong>Bathrooms:</strong> <span
                                            id="propertyBathrooms_{{ $listing->id }}">{{ $listing->bathrooms }}</span>
                                    </p>
                                    <p class="ms-2"><strong>Garage:</strong> <span
                                            id="propertyGarage_{{ $listing->id }}">{{ $listing->garage }}</span></p>
                                    <p class="ms-2"><strong>Price: <span class="text-danger d-none"
                                                id="required-icon">*</span></strong> <span
                                            id="propertyPrice_{{ $listing->id }}">{{ $listing->price }}</span></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <p class="ms-2"><strong>Description:</strong></p>
                                    <div style="max-height: 200px; overflow-y: auto;">
                                        <!-- Set a fixed height and make it scrollable -->
                                        <p class="ms-4"><span
                                                id="propertyDescription_{{ $listing->id }}">{{ $listing->description }}</span>
                                        </p>
                                        <!-- Long description will take full width -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-end">
                            <small class="text-danger me-4 d-none" id="error_no_changes_{{ $listing->id }}"></small>
                            <button type="button" id="applyButton_{{ $listing->id }}"
                                class="btn btn-warning d-none me-2" onclick="confirmUpdate('{{ $listing->id }}')">Apply
                                Changes</button>
                            <button type="button" id="cancelButton_{{ $listing->id }}" class="btn btn-danger d-none"
                                onclick="cancelEdit_{{ $listing->id }}()">Cancel</button>
                        </div>
                    </form>
                </div>

                <div class="modal-footer border-top d-flex justify-content-between align-items-center">
                    <p class="mb-0" style="font-size: 0.7rem; opacity: 0.7;"><strong>Date Requested:</strong>
                        {{ $listing->created_at->format('Y-m-d H:i:s') }}</p>
                    <div>
                        <button type="button" class="btn btn-success" id="approve-button-{{ $listing->id }}"
                            onclick="confirmApprove('{{ $listing->id }}')">
                            Approve
                        </button>
                        <button type="button" class="btn btn-danger" id="disapprove-button-{{ $listing->id }}"
                            onclick="confirmDisapprove('{{ $listing->id }}')">
                            Disapprove
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Store original values globally to revert when cancel is clicked
    let originalValues_{{ $listing->id }} = {};

    function toggleEdit_{{ $listing->id }}() {
        // Store original values to revert when "Cancel" is clicked
        originalValues_{{ $listing->id }} = {
            userType: document.getElementById('clientType_{{ $listing->id }}').innerText,
            name: document.getElementById('clientName_{{ $listing->id }}').innerText,
            email: document.getElementById('clientEmail_{{ $listing->id }}').innerText,
            phone: document.getElementById('clientPhone_{{ $listing->id }}').innerText,
            propertyStatus: document.getElementById('propertyStatus_{{ $listing->id }}').innerText,
            propertyType: document.getElementById('propertyType_{{ $listing->id }}').innerText,
            city: document.getElementById('propertyCity_{{ $listing->id }}').innerText,
            address: document.getElementById('propertyAddress_{{ $listing->id }}').innerText,
            size: document.getElementById('propertySize_{{ $listing->id }}').innerText,
            bedrooms: document.getElementById('propertyBedrooms_{{ $listing->id }}').innerText,
            bathrooms: document.getElementById('propertyBathrooms_{{ $listing->id }}').innerText,
            garage: document.getElementById('propertyGarage_{{ $listing->id }}').innerText,
            price: document.getElementById('propertyPrice_{{ $listing->id }}').innerText,
            description: document.getElementById('propertyDescription_{{ $listing->id }}').innerText,
        };

        const userType = originalValues_{{ $listing->id }}.userType.trim();
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

        document.getElementById('clientType_{{ $listing->id }}').innerHTML = `
        <select id="input_user_type_{{ $listing->id }}" name="user_type" class="form-select">
            ${userTypeSelectOptions}
        </select>
        <small class="text-danger" id="error_user_type_{{ $listing->id }}"></small>`;


        document.getElementById('clientName_{{ $listing->id }}').innerHTML = `
        <input type="text" id="input_name_{{ $listing->id }}" name="name" class="form-control" value="${originalValues_{{ $listing->id }}.name.trim()}">
        <small class="text-danger" id="error_name_{{ $listing->id }}"></small>`;

        document.getElementById('clientEmail_{{ $listing->id }}').innerHTML = `
        <input type="email" id="input_email_{{ $listing->id }}" name="email" class="form-control" value="${originalValues_{{ $listing->id }}.email.trim()}">
        <small class="text-danger" id="error_email_{{ $listing->id }}"></small>`;

        document.getElementById('clientPhone_{{ $listing->id }}').innerHTML = `
        <input type="text" id="input_cellphone_number_{{ $listing->id }}" name="cellphone_number" class="form-control" value="${originalValues_{{ $listing->id }}.phone.trim()}">
        <small class="text-danger" id="error_cellphone_number_{{ $listing->id }}"></small>`;

        // Property Details
        const propertyStatus = originalValues_{{ $listing->id }}.propertyStatus.trim();
        const statusOptions = [
            { value: "sale", label: "For Sale" },
            { value: "rent", label: "For Rent" }
        ];

        const statusSelectOptions = statusOptions.map(option => {
            const selected = capitalizeFirstLetter(option.value) === propertyStatus ? 'selected' : '';
            return `<option value="${option.value}" ${selected}>${option.label}</option>`;
        }).join('');

        document.getElementById('propertyStatus_{{ $listing->id }}').innerHTML = `
        <select id="input_property_status_{{ $listing->id }}" name="property_status" class="form-select">
            ${statusSelectOptions}
        </select>
        <small class="text-danger" id="error_property_status_{{ $listing->id }}"></small>`;

        const propertyType = originalValues_{{ $listing->id }}.propertyType.trim();
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

        document.getElementById('propertyType_{{ $listing->id }}').innerHTML = `
        <select id="input_property_type_{{ $listing->id }}" name="property_type" class="form-select">
            ${selectOptions}
        </select>
        <small class="text-danger" id="error_property_type_{{ $listing->id }}"></small>`;

        const city = originalValues_{{ $listing->id }}.city.trim();
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

        document.getElementById('propertyCity_{{ $listing->id }}').innerHTML = `
        <select id="input_city_{{ $listing->id }}" name="city" class="form-select">
            ${citySelectOptions}
        </select>
        <small class="text-danger" id="error_city_{{ $listing->id }}"></small>`;

        document.getElementById('propertyAddress_{{ $listing->id }}').innerHTML = `
        <input type="text" id="input_address_{{ $listing->id }}" name="address" class="form-control" value="${originalValues_{{ $listing->id }}.address.trim()}">
        <small class="text-danger" id="error_address_{{ $listing->id }}"></small>`;

        document.getElementById('propertySize_{{ $listing->id }}').innerHTML = `
        <input type="text" id="input_size_{{ $listing->id }}" name="size" class="form-control" value="${originalValues_{{ $listing->id }}.size.trim().replace(' sqm', '')}">
        <small class="text-danger" id="error_size_{{ $listing->id }}"></small>`;

        document.getElementById('propertyBedrooms_{{ $listing->id }}').innerHTML = `
        <input type="number" id="input_bedrooms_{{ $listing->id }}" name="bedrooms" class="form-control" value="${originalValues_{{ $listing->id }}.bedrooms.trim()}">
        <small class="text-danger" id="error_bedrooms_{{ $listing->id }}"></small>`;

        document.getElementById('propertyBathrooms_{{ $listing->id }}').innerHTML = `
        <input type="number" id="input_bathrooms_{{ $listing->id }}" name="bathrooms" class="form-control" value="${originalValues_{{ $listing->id }}.bathrooms.trim()}">
        <small class="text-danger" id="error_bathrooms_{{ $listing->id }}"></small>`;

        document.getElementById('propertyGarage_{{ $listing->id }}').innerHTML = `
        <input type="number" id="input_garage_{{ $listing->id }}" name="garage" class="form-control" value="${originalValues_{{ $listing->id }}.garage.trim()}">
        <small class="text-danger" id="error_garage_{{ $listing->id }}"></small>`;

        document.getElementById('propertyPrice_{{ $listing->id }}').innerHTML = `
        <input type="text" id="input_price_{{ $listing->id }}" name="price" class="form-control" value="${originalValues_{{ $listing->id }}.price.trim()}">
        <small class="text-danger" id="error_price_{{ $listing->id }}"></small>`;

        document.getElementById('propertyDescription_{{ $listing->id }}').innerHTML = `
        <textarea id="input_description_{{ $listing->id }}" name="description" class="form-control" rows="3">${originalValues_{{ $listing->id }}.description.trim()}</textarea>
        <small class="text-danger" id="error_description_{{ $listing->id }}"></small>`;

        // Toggle buttons
        document.getElementById('editButton_{{ $listing->id }}').classList.add('d-none');
        document.getElementById('cancelButton_{{ $listing->id }}').classList.remove('d-none');
        document.getElementById('applyButton_{{ $listing->id }}').classList.remove('d-none');

        // Disable Approve and Disapprove Button
        document.getElementById('approve-button-{{ $listing->id }}').disabled = true;
        document.getElementById('disapprove-button-{{ $listing->id }}').disabled = true;

        // Show required icon
        document.querySelectorAll('.text-danger').forEach(icon => icon.classList.remove('d-none'));
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function cancelEdit_{{ $listing->id }}() {
        // Revert back to original values without reloading
        document.getElementById('clientType_{{ $listing->id }}').innerHTML = originalValues_{{ $listing->id }}.userType;
        document.getElementById('clientName_{{ $listing->id }}').innerHTML = originalValues_{{ $listing->id }}.name;
        document.getElementById('clientEmail_{{ $listing->id }}').innerHTML = originalValues_{{ $listing->id }}.email;
        document.getElementById('clientPhone_{{ $listing->id }}').innerHTML = originalValues_{{ $listing->id }}.phone;

        document.getElementById('propertyStatus_{{ $listing->id }}').innerHTML = originalValues_{{ $listing->id }}.propertyStatus;
        document.getElementById('propertyType_{{ $listing->id }}').innerHTML = originalValues_{{ $listing->id }}.propertyType;
        document.getElementById('propertyCity_{{ $listing->id }}').innerHTML = originalValues_{{ $listing->id }}.city;
        document.getElementById('propertyAddress_{{ $listing->id }}').innerHTML = originalValues_{{ $listing->id }}.address;
        document.getElementById('propertySize_{{ $listing->id }}').innerHTML = originalValues_{{ $listing->id }}.size;
        document.getElementById('propertyBedrooms_{{ $listing->id }}').innerHTML = originalValues_{{ $listing->id }}.bedrooms;
        document.getElementById('propertyBathrooms_{{ $listing->id }}').innerHTML = originalValues_{{ $listing->id }}.bathrooms;
        document.getElementById('propertyGarage_{{ $listing->id }}').innerHTML = originalValues_{{ $listing->id }}.garage;
        document.getElementById('propertyPrice_{{ $listing->id }}').innerHTML = originalValues_{{ $listing->id }}.price;
        document.getElementById('propertyDescription_{{ $listing->id }}').innerHTML = originalValues_{{ $listing->id }}.description;

        // Toggle buttons
        document.getElementById('editButton_{{ $listing->id }}').classList.remove('d-none');
        document.getElementById('cancelButton_{{ $listing->id }}').classList.add('d-none');
        document.getElementById('applyButton_{{ $listing->id }}').classList.add('d-none');

        // Enable Approve and Disapprove Button
        document.getElementById('approve-button-{{ $listing->id }}').disabled = false;
        document.getElementById('disapprove-button-{{ $listing->id }}').disabled = false;

        // Hide required icon
        document.querySelectorAll('.text-danger').forEach(icon => icon.classList.add('d-none'));

        // Clear any previous error messages
        clearAllErrors();
        const errorElement = document.getElementById('error_no_changes_{{ $listing->id }}');
        errorElement.classList.add('d-none');
        errorElement.textContent = '';
    }

    function confirmUpdate(listingId) {
        const button = document.getElementById('applyButton_' + listingId);

        if (button.innerText === "Apply Changes") {
            // Change text to confirm
            button.innerText = "Confirm Changes?";
            // Change the onclick to submit the update
            button.setAttribute('onclick', `submitUpdate('${listingId}')`);

            // Change cancel button to "Go Back" and reset the action
            const cancelButton = document.getElementById('cancelButton_' + listingId);
            cancelButton.innerText = "Go Back";
            cancelButton.classList.remove('btn-danger');
            cancelButton.classList.add('btn-secondary');
            cancelButton.setAttribute('onclick', `cancelUpdate('${listingId}')`);
        }
    }

    function cancelUpdate(listingId) {
        // Reset the apply button to its original state
        const applyButton = document.getElementById('applyButton_' + listingId);
        applyButton.innerText = "Apply Changes";
        applyButton.classList.add('btn-warning');
        applyButton.classList.remove('btn-secondary');

        // Reset the onclick function to confirm the update
        applyButton.setAttribute('onclick', `confirmUpdate('${listingId}')`);

        // Reset the cancel button to its original state
        const cancelButton = document.getElementById('cancelButton_' + listingId);
        cancelButton.innerText = "Cancel";
        cancelButton.classList.add('btn-danger');
        cancelButton.classList.remove('btn-secondary');

        // Reset the onclick function to hide the input fields and revert changes
        cancelButton.setAttribute('onclick', `cancelEdit_${listingId}()`);
    }

    async function submitUpdate(listingId) {
        const applyButton = document.getElementById('applyButton_' + listingId);
        const cancelButton = document.getElementById('cancelButton_' + listingId);

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
        const route = '{{ url('listWithUs/validateListUpdateForm') }}/' + listingId;

        // Create a FormData object to collect form data
        const formData = new FormData();

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', csrfToken);

        // Add all the updated fields from the modal/form
        const fields = ['user_type', 'name', 'email', 'cellphone_number', 'property_status', 'property_type', 'city', 'address', 'size', 'bedrooms', 'bathrooms', 'garage', 'price', 'description'];

        fields.forEach(field => {
            const inputElement = document.getElementById('input_' + field + '_' + listingId);
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

            const errorElement = document.getElementById('error_no_changes_' + listingId);
            errorElement.classList.add('d-none');

            if (data.message === 'No changes detected') {
                if (errorElement) {
                    errorElement.textContent = 'No changes detected.';
                    errorElement.classList.remove('d-none');
                }

                clearInterval(interval);
                cancelUpdate(listingId);

                applyButton.disabled = false;
                cancelButton.disabled = false;
            } else if (data.message === 'Validation passed') {
                // Construct the route URL for update submission
                const updateRoute = '{{ url('listWithUs/updateList', ) }}/' + listingId;

                // Create a new FormData object for the update submission
                const updateFormData = new FormData();
                updateFormData.append('_token', csrfToken);

                // Append the same fields for the update
                fields.forEach(field => {
                    const inputElement = document.getElementById('input_' + field + '_' + listingId);
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

                if (updateData.message === 'Listing updated successfully.') {
                    // Handle success, e.g., redirect or show a success message
                    window.location.href = '{{ route('listWithUs') }}';
                } else {
                    // Handle errors if needed
                    console.error('Update failed:', updateData);
                    alert('An error occurred while updating the listing.'); // Display error
                }
            } else {
                handleFormResponse(data, listingId);
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

    function handleFormResponse(data, listingId) {
        if (data.message === 'Validation failed') {
            cancelUpdate(listingId);

            // Loop through validation errors and display them
            for (const [key, value] of Object.entries(data.errors)) {
                const errorElementId = `error_${key}_${listingId}`;
                const errorElement = document.getElementById(errorElementId);
                if (errorElement) {
                    errorElement.textContent = value[0]; // Assuming each error message is in an array
                }

                const inputElement = document.getElementById(`input_${key}_${listingId}`);
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

    // Function to set up image navigation for a specific listing
    function setupImageNavigation(listingId, images, folderName) {
        let currentIndex = 0; // Track the current image index
        const imagesPerPage = 4; // Number of images to display at once
        let currentPage = 0; // Track the current page of images

        // Update the current image display
        function updateCurrentImage() {
            const currentImage = document.getElementById('currentImage-' + listingId);
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
            currentImage.src = "{{ asset('uploads/list-with-us/') }}" + '/' + folderName + '/' + imageSource;

            // Display only the images for the current page
            displayThumbnails();

            // Update button states
            updateButtonStates(totalImages);
        }

        function displayThumbnails() {
            const thumbnailContainer = document.getElementById('thumbnailContainer-' + listingId);
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
            const prevButton = document.getElementById('prevImage-' + listingId);
            const nextButton = document.getElementById('nextImage-' + listingId);

            // Disable buttons based on current index and page
            prevButton.disabled = (currentIndex === 0 && currentPage === 0);
            nextButton.disabled = (currentIndex >= totalImages - 1 && currentPage >= Math.floor((totalImages - 1) / imagesPerPage));
        }

        // Previous button functionality
        document.getElementById('prevImage-' + listingId).onclick = function () {
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
        document.getElementById('nextImage-' + listingId).onclick = function () {
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
        document.querySelectorAll('.thumbnail-image[data-listing-id="' + listingId + '"]').forEach((thumbnail) => {
            thumbnail.onclick = function () {
                currentIndex = parseInt(thumbnail.getAttribute('data-index'));
                currentPage = Math.floor(currentIndex / imagesPerPage); // Update the current page based on index
                updateCurrentImage();
            };
        });

        // Initial image display
        updateCurrentImage();
    }

    // Define unique variable names for images and folder names for each listing
    const listingId_{{ $listing->id }} = {{ $listing->id }};
    const images_{{ $listing->id }} = @json($images); // Pass the images array to JavaScript
    const folderName_{{ $listing->id }} = "{{ $listing->folder_name }}";

    // Set up image navigation for this listing
    setupImageNavigation(listingId_{{ $listing->id }}, images_{{ $listing->id }}, folderName_{{ $listing->id }});

    function confirmApprove(listingId) {
        const button = document.getElementById('approve-button-' + listingId);

        if (button.innerText === "Approve") {
            // Change text to confirm
            button.innerText = "Confirm Approve?";
            // Change the onclick to submit
            button.setAttribute('onclick', `submitApproval('${listingId}')`);

            // Change disapprove button to "Cancel"
            const disapproveButton = document.getElementById('disapprove-button-' + listingId);
            disapproveButton.innerText = "Go Back";
            disapproveButton.classList.remove('btn-danger');
            disapproveButton.classList.add('btn-secondary');
            disapproveButton.setAttribute('onclick', `cancelAction('${listingId}')`);
        }
    }

    function confirmDisapprove(listingId) {
        const button = document.getElementById('disapprove-button-' + listingId);

        if (button.innerText === "Disapprove") {
            // Change text to confirm
            button.innerText = "Confirm Disapprove and Deletion?";
            // Change the onclick to submit
            button.setAttribute('onclick', `submitDisapproval('${listingId}')`);

            // Change approve button to "Cancel"
            const approveButton = document.getElementById('approve-button-' + listingId);
            approveButton.innerText = "Go Back";
            approveButton.classList.remove('btn-success');
            approveButton.classList.add('btn-secondary');
            approveButton.setAttribute('onclick', `cancelAction('${listingId}')`);
        }
    }

    function cancelAction(listingId) {
        // Reset buttons to original text
        const approveButton = document.getElementById('approve-button-' + listingId);
        approveButton.innerText = "Approve";
        approveButton.classList.add('btn-success');
        approveButton.classList.remove('btn-secondary');

        const disapproveButton = document.getElementById('disapprove-button-' + listingId);
        disapproveButton.innerText = "Disapprove";
        disapproveButton.classList.add('btn-danger');
        disapproveButton.classList.remove('btn-secondary');

        // Reset the onclick functions to original state
        document.getElementById('approve-button-' + listingId).setAttribute('onclick', `confirmApprove('${listingId}')`);
        document.getElementById('disapprove-button-' + listingId).setAttribute('onclick', `confirmDisapprove('${listingId}')`);
    }

    // Function to handle approval submission
    function submitApproval(listingId) {
        const approveButton = document.getElementById('approve-button-' + listingId);
        const disapproveButton = document.getElementById('disapprove-button-' + listingId);

        // Disable both buttons to prevent multiple submissions
        approveButton.disabled = true;
        disapproveButton.disabled = true;

        // Change text to "Approving..."
        approveButton.innerText = "Approving...";

        // Construct the route URL for approval submission
        const route = `{{ route('listWithUs.approveList', ':id') }}`.replace(':id', listingId);

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

    // Function to handle disapproval submission
    function submitDisapproval(listingId) {
        const disapproveButton = document.getElementById('disapprove-button-' + listingId);
        const approveButton = document.getElementById('approve-button-' + listingId);

        // Disable both buttons to prevent multiple submissions
        disapproveButton.disabled = true;
        approveButton.disabled = true;

        // Change text to "Deleting..."
        disapproveButton.innerText = "Deleting...";

        // Construct the route URL for disapproval submission
        const route = `{{ route('listWithUs.disapproveList', ':id') }}`.replace(':id', listingId);

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
</script>