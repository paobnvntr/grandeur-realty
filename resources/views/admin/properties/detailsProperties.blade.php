<div class="modal fade" id="detailsModal-{{ $property->id }}" tabindex="-1"
    aria-labelledby="detailsModalLabel-{{ $property->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-3 shadow-lg">
            <div class="modal-header pb-2 border-bottom">
                <h4 class="modal-title text-info" id="detailsModalLabel-{{ $property->id }}">Property Details</h4>
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
                                                    class="img-fluid rounded" alt="Property Image"
                                                    style="max-width: 50%; height: 350px;"> <!-- Adjusted image size -->
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
                                                        data-index="{{ $index }}" data-listing-id="{{ $property->id }}"
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
                    <h5 class="text-info mb-4">Personal Details</h5>
                    <div class="row">
                        <div class="col-6">
                            <p class="ms-2"><strong>Client Type:</strong> {{ ucfirst($property->user_type) }}</p>
                            <p class="ms-2"><strong>Name:</strong> {{ \Illuminate\Support\Str::title($property->name) }}
                            </p>
                            <p class="ms-2"><strong>Email:</strong> {{ $property->email }}</p>
                            <p class="ms-2"><strong>Cellphone Number:</strong> {{ $property->cellphone_number }}</p>
                        </div>
                    </div>
                </div>

                <!-- Property Details Section -->
                <div class="property-details ps-4">
                    <h5 class="text-info mb-4">Property Details</h5>
                    <div class="row">
                        <div class="col-6">
                            <p class="ms-2"><strong>Property Status:</strong> {{ ucfirst($property->property_status) }}
                            </p>
                            <p class="ms-2"><strong>Property Type:</strong> {{ ucfirst($property->property_type) }}</p>
                            <p class="ms-2"><strong>City:</strong> {{ ucfirst($property->city)}}</p>
                            <p class="ms-2"><strong>Address:</strong>
                                {{ \Illuminate\Support\Str::title($property->address) }}</p>
                            <p class="ms-2"><strong>Size:</strong> {{ $property->size }} sqm</p>
                        </div>
                        <div class="col-6">
                            <p class="ms-2"><strong>Bedrooms:</strong> {{ $property->bedrooms }}</p>
                            <p class="ms-2"><strong>Bathrooms:</strong> {{ $property->bathrooms }}</p>
                            <p class="ms-2"><strong>Garage:</strong> {{ $property->garage }}</p>
                            <p class="ms-2"><strong>Price:</strong> {{ $property->price }}</p>
                            @if ($property->status == 'sold')
                                <p class="ms-2"><strong>Date Sold:</strong> {{ $property->date_sold }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Separate Row for Description -->
                    <div class="row">
                        <div class="col-12">
                            <p class="ms-2"><strong>Description:</strong></p>
                            <div style="max-height: 200px; overflow-y: auto;">
                                <!-- Set a fixed height and make it scrollable -->
                                <p class="ms-4">{{ $property->description }}</p>
                                <!-- Long description will take full width -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top d-flex justify-content-between align-items-center">
                <p class="mb-0" style="font-size: 0.7rem; opacity: 0.7;">
                    <strong>Date Accepted:</strong> {{ $property->created_at->format('Y-m-d H:i:s') }}
                </p>
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
    // Function to set up image navigation for a specific listing
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
        document.querySelectorAll('.thumbnail-image[data-listing-id="' + propertyId + '"]').forEach((thumbnail) => {
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
    const propertyId_{{ $property->id }} = {{ $property->id }};
    const images_{{ $property->id }} = @json($images); // Pass the images array to JavaScript
    const folderName_{{ $property->id }} = "{{ $property->folder_name }}";

    // Set up image navigation for this listing
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