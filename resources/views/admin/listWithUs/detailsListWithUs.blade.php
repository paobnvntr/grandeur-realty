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
                                                    class="img-fluid rounded" alt="Property Image"
                                                    style="max-width: 50%; height: 350px;"> <!-- Adjusted image size -->
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
                    <h5 class="text-info mb-4">Personal Details</h5>
                    <div class="row">
                        <div class="col-6">
                            <p class="ms-2"><strong>Client Type:</strong> {{ ucfirst($listing->user_type) }}</p>
                            <p class="ms-2"><strong>Name:</strong> {{ \Illuminate\Support\Str::title($listing->name) }}
                            </p>
                            <p class="ms-2"><strong>Email:</strong> {{ $listing->email }}</p>
                            <p class="ms-2"><strong>Cellphone Number:</strong> {{ $listing->cellphone_number }}</p>
                        </div>
                    </div>
                </div>

                <!-- Property Details Section -->
                <div class="property-details ps-4">
                    <h5 class="text-info mb-4">Property Details</h5>
                    <div class="row">
                        <div class="col-6">
                            <p class="ms-2"><strong>Property Status:</strong> {{ ucfirst($listing->property_status) }}</p>
                            <p class="ms-2"><strong>Property Type:</strong> {{ ucfirst($listing->property_type) }}</p>
                            <p class="ms-2"><strong>City:</strong> {{ ucfirst($listing->city)}}</p>
                            <p class="ms-2"><strong>Address:</strong>
                                {{ \Illuminate\Support\Str::title($listing->address) }}</p>
                            <p class="ms-2"><strong>Size:</strong> {{ $listing->size }} sqm</p>
                        </div>
                        <div class="col-6">
                            <p class="ms-2"><strong>Bedrooms:</strong> {{ $listing->bedrooms }}</p>
                            <p class="ms-2"><strong>Bathrooms:</strong> {{ $listing->bathrooms }}</p>
                            <p class="ms-2"><strong>Garage:</strong> {{ $listing->garage }}</p>
                            <p class="ms-2"><strong>Price:</strong> {{ $listing->price }}</p>
                        </div>
                    </div>

                    <!-- Separate Row for Description -->
                    <div class="row">
                        <div class="col-12">
                            <p class="ms-2"><strong>Description:</strong></p>
                            <div style="max-height: 200px; overflow-y: auto;">
                                <!-- Set a fixed height and make it scrollable -->
                                <p class="ms-4">{{ $listing->description }}</p>
                                <!-- Long description will take full width -->
                            </div>
                        </div>
                    </div>
                </div>
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

<script>
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