@extends('landing-page.layouts.app')

@section('title', 'Grandeur Realty - Hot Properties')

@section('contents')
<div class="hero page-inner overlay" style="background-image: url('../images/hero_bg_1.jpg')">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-9 text-center mt-5">
                <h1 class="heading" data-aos="fade-up">Hot Properties - {{ $city }}</h1>

                <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                    <ol class="breadcrumb text-center justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('allProperties') }}">Properties</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page" style="cursor: default;">
                            {{ $city }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container p-5 search-container shadow-sm">
        <form action="#">
            <div class="row mb-4">
                <div class="col-md-4 mb-4">
                    <select id="propertyType" name="type" class="form-select">
                        <option value="">Select Type</option>
                        <option value="house">House</option>
                        <option value="condominium">Condominium</option>
                        <option value="land">Land</option>
                        <option value="townhouse">Townhouse</option>
                        <option value="apartment">Apartment</option>
                        <option value="commercial">Commercial</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <select id="propertyStatus" name="status" class="form-select">
                        <option value="">Select Status</option>
                        <option value="sale">For Sale</option>
                        <option value="rent">For Rent</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <input type="text" id="location" name="location" class="form-control" placeholder="Enter Location">
                </div>

                <div class="col-md-4">
                    <select id="priceRange" name="price_range" class="form-select">
                        <option value="">Select Price Range</option>
                        <option value="0-50000">₱0 - ₱50,000</option>
                        <option value="50001-100000">₱50,001 - ₱100,000</option>
                        <option value="100001-200000">₱100,001 - ₱200,000</option>
                        <option value="200001-500000">₱200,001 - ₱500,000</option>
                        <option value="500001-1000000">₱500,001 - ₱1,000,000</option>
                        <option value="1000001">₱1,000,001 and above</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <select id="sizeRange" name="size_range" class="form-select">
                        <option value="">Select Size Range</option>
                        <option value="0-500">0 - 500 m²</option>
                        <option value="501-1000">501 - 1000 m²</option>
                        <option value="1001-2000">1001 - 2000 m²</option>
                        <option value="2001-5000">2001 - 5000 m²</option>
                        <option value="5001">5001 m² and above</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-center justify-content-between">
                    <button type="reset" class="btn text-danger me-4 d-none d-sm-inline-block">Reset</button>
                    <button type="reset" class="btn text-danger d-inline-block d-sm-none">Reset</button>
                    <button type="button" id="searchButton" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="section pt-0">
    <div class="container">
        <div class="row d-flex justify-content-between">
            <div class="col-9 filter-buttons mb-4">
                <button data-filter="all" class="btn btn-outline-primary filter-btn active">All</button>
                <button data-filter="sale" class="btn btn-outline-primary filter-btn">For Sale</button>
                <button data-filter="rent" class="btn btn-outline-primary filter-btn">For Rent</button>
            </div>

            <div class="col-3 sort-by mb-4">
                <select id="sortDropdown" class="form-select">
                    <option value="default" selected>Default Order</option>
                    <option value="price-asc">Price (Low to High)</option>
                    <option value="price-desc">Price (High to Low)</option>
                    <option value="date-asc">Date (Old to New)</option>
                    <option value="date-desc">Date (New to Old)</option>
                </select>
            </div>
        </div>

        <div class="row" id="propertyContainer"></div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <p class="mb-0">Showing <span id="currentPageStart"></span> to <span id="currentPageEnd"></span> of
                    <span id="totalProperties"></span> results
                </p>
            </div>
            <div>
                <button id="prevPage" class="btn btn-primary">Previous</button>
                <button id="nextPage" class="btn btn-primary">Next</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const properties = @json($hotProperties);
        const filterButtons = document.querySelectorAll('.filter-btn');
        const sortDropdown = document.getElementById('sortDropdown');
        const propertyContainer = document.getElementById('propertyContainer');
        const prevPageButton = document.getElementById('prevPage');
        const nextPageButton = document.getElementById('nextPage');
        const totalPropertiesEl = document.getElementById('totalProperties');
        const currentPageStartEl = document.getElementById('currentPageStart');
        const currentPageEndEl = document.getElementById('currentPageEnd');
        const searchButton = document.getElementById('searchButton');
        const locationInput = document.getElementById('location');
        const propertyTypeSelect = document.getElementById('propertyType');
        const propertyStatusSelect = document.getElementById('propertyStatus');
        const priceRangeSelect = document.getElementById('priceRange');
        const sizeRangeSelect = document.getElementById('sizeRange');

        let currentPage = 1;
        const propertiesPerPage = 15;

        // Initialize filteredProperties with all properties
        let filteredProperties = properties;

        function updatePaginationButtons() {
            const totalPages = Math.ceil(filteredProperties.length / propertiesPerPage);
            prevPageButton.disabled = currentPage === 1;
            nextPageButton.disabled = currentPage === totalPages || filteredProperties.length === 0;
        }

        function timeDiffForHumans(dateString) {
            const now = new Date();
            const createdAt = new Date(dateString);
            const diffInMs = now - createdAt; // Difference in milliseconds

            const seconds = Math.floor(diffInMs / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);
            const months = Math.floor(days / 30);
            const years = Math.floor(months / 12);

            if (years > 0) {
                return years === 1 ? "1 year ago" : `${years} years ago`; // Corrected here
            } else if (months > 0) {
                return months === 1 ? "1 month ago" : `${months} months ago`; // Corrected here
            } else if (days > 0) {
                return days === 1 ? "1 day ago" : `${days} days ago`; // Corrected here
            } else if (hours > 0) {
                return hours === 1 ? "1 hour ago" : `${hours} hours ago`; // Corrected here
            } else if (minutes > 0) {
                return minutes === 1 ? "1 minute ago" : `${minutes} minutes ago`; // Corrected here
            } else {
                return seconds === 1 ? "1 second ago" : `${seconds} seconds ago`; // Corrected here
            }
        }

        function renderProperties() {
            const start = (currentPage - 1) * propertiesPerPage;
            const end = currentPage * propertiesPerPage;

            const currentProperties = filteredProperties.slice(start, end);
            propertyContainer.innerHTML = '';

            currentProperties.forEach(property => {
                const formattedDate = timeDiffForHumans(property.created_at);

                // Specs
                const specs = `
            <div class="specs d-flex mb-4">
                ${property.size !== 0 ? `
                    <span class="d-block d-flex align-items-center me-3">
                        <span class="icon-arrows-alt me-2"></span>
                        <span class="caption">${property.size} m²</span>
                    </span>
                ` : ''}
                ${property.bedrooms !== 0 ? `
                    <span class="d-block d-flex align-items-center me-3">
                        <span class="icon-bed me-2"></span>
                        <span class="caption">${property.bedrooms} beds</span>
                    </span>
                ` : ''}
                ${property.bathrooms !== 0 ? `
                    <span class="d-block d-flex align-items-center">
                        <span class="icon-bath me-2"></span>
                        <span class="caption">${property.bathrooms} baths</span>
                    </span>
                ` : ''}
            </div>
        `;

                // Property Type
                const propertyType = `
            <div class="specs d-flex mb-4">
                <span class="d-block d-flex align-items-center me-3 text-info">
                    <span class="icon-tag me-2"></span>
                    <span class="caption">${property.property_type.charAt(0).toUpperCase() + property.property_type.slice(1)}</span>
                </span>
            </div>
        `;

                // Property Element
                const propertyElement = `
            <div class="col-12 col-md-4 property-card" data-status="${property.property_status}" data-created-at="${property.created_at}">
                <div class="property-item mb-30 position-relative shadow-sm">
                    <div class="ribbon ${property.property_status === 'sale' ? 'ribbon-sale' : 'ribbon-rent'}">
                        For ${property.property_status.charAt(0).toUpperCase() + property.property_status.slice(1)}
                    </div>
                    <img src="{{ asset('uploads/property/') }}/${property.folder_name}/${JSON.parse(property.image)[0]}" alt="${property.name}" class="img img-fluid" />
                    <div class="property-content">
                        <div class="price mb-2"><span>₱${parseFloat(property.price).toLocaleString()}</span></div>
                        <div>
                            <span class="d-block mb-2 text-black-50">${property.address}</span>
                            <span class="city d-block mb-3">City of ${property.city}</span>
                        </div>
                        ${specs}
                        ${propertyType}
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="/property-details/${property.id}" class="btn btn-primary py-2 px-3">See details</a>
                            <div>
                                <span class="icon-calendar me-2"></span>
                                <span class="caption">${formattedDate}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

                // Insert property element into container
                propertyContainer.insertAdjacentHTML('beforeend', propertyElement);
            });

            // Update pagination info
            totalPropertiesEl.textContent = filteredProperties.length;
            currentPageStartEl.textContent = start + 1;
            currentPageEndEl.textContent = end > filteredProperties.length ? filteredProperties.length : end;

            // Update pagination buttons
            updatePaginationButtons();
        }

        function filterProperties() {
            // Get the currently active filter value
            const activeFilter = document.querySelector('.filter-buttons .active').dataset.filter;

            // Get the search criteria
            const location = locationInput.value;
            const type = propertyTypeSelect.value;
            const status = propertyStatusSelect.value;
            const priceRange = priceRangeSelect.value;
            const sizeRange = sizeRangeSelect.value;

            // Filter properties based on active filter
            filteredProperties = properties.filter(property => {
                // Apply the active filter to restrict the properties
                if (activeFilter !== 'all' && property.property_status !== activeFilter) {
                    return false; // Skip properties that don't match the active filter
                }

                // Match the location
                const matchesLocation = location
                    ? (property.city.toLowerCase().includes(location.toLowerCase()) ||
                        property.address.toLowerCase().includes(location.toLowerCase()))
                    : true;

                // Match the type
                const matchesType = type ? property.property_type === type : true;

                // Match the status
                const matchesStatus = status ? property.property_status === status : true;

                // Match the price range
                let matchesPrice = true;
                if (priceRange) {
                    const [minPrice, maxPrice] = priceRange.split('-').map(Number);
                    matchesPrice = property.price >= minPrice && (maxPrice ? property.price <= maxPrice : true);
                }

                // Match the size range
                let matchesSize = true;
                if (sizeRange) {
                    const [minSize, maxSize] = sizeRange.split('-').map(Number);
                    matchesSize = property.size >= minSize && (maxSize ? property.size <= maxSize : true);
                }

                return matchesLocation && matchesType && matchesStatus && matchesPrice && matchesSize;
            });

            currentPage = 1; // Reset to the first page
            renderProperties(); // Render the filtered properties
        }

        filterButtons.forEach(button => {
            button.addEventListener('click', function () {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const filterValue = this.dataset.filter;

                // Filtering logic
                if (filterValue === 'all') {
                    filteredProperties = properties; // Show all properties
                } else {
                    filteredProperties = properties.filter(property => property.property_status === filterValue);
                }

                currentPage = 1; // Reset to the first page
                renderProperties(); // Render the filtered properties
            });
        });

        renderProperties();

        sortDropdown.addEventListener('change', function () {
            const sortValue = this.value;
            if (sortValue === 'price-asc') {
                filteredProperties.sort((a, b) => a.price - b.price);
            } else if (sortValue === 'price-desc') {
                filteredProperties.sort((a, b) => b.price - a.price);
            } else if (sortValue === 'date-asc') {
                filteredProperties.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
            } else if (sortValue === 'date-desc') {
                filteredProperties.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            }
            renderProperties();
        });

        prevPageButton.addEventListener('click', function () {
            if (currentPage > 1) {
                currentPage--;
                renderProperties();
            }
        });

        nextPageButton.addEventListener('click', function () {
            const totalPages = Math.ceil(filteredProperties.length / propertiesPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderProperties();
            }
        });

        document.querySelector('button[type="reset"]').addEventListener('click', function () {
            propertyTypeSelect.value = '';
            priceRangeSelect.value = '';
            sizeRangeSelect.value = '';
            document.getElementById('location').value = '';

            const selectedStatus = document.querySelector('.filter-btn.active').dataset.filter;

            if (selectedStatus === 'all') {
                filteredProperties = properties;
            } else {
                filteredProperties = properties.filter(property => property.property_status === selectedStatus);
            }

            currentPage = 1;
            renderProperties();
        });

        searchButton.addEventListener('click', filterProperties);
    });
</script>
@endsection