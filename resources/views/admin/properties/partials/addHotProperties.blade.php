<div class="modal fade" id="addHotPropertyModal" tabindex="-1" aria-labelledby="addHotPropertyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 shadow-lg">
            <form id="hot-properties-form" action="{{ route('properties.saveAddHotProperties') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header pb-2 border-bottom">
                    <h5 class="modal-title text-success">Add Hot Properties</h5>
                    <button type="button" class="btn btn-outline-link" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12 mb-3">
                        <label for="slot" class="form-label">Select Card Slot <span class="text-danger">*</span></label>
                        <select id="slot" name="slot" class="form-select @error('slot') is-invalid @enderror" required>
                            <option value="" disabled selected>Select a card slot</option>
                            @foreach($availableSlots as $slot)
                                <option value="{{ $slot }}">Card Slot #{{ $slot }}</option>
                            @endforeach
                        </select>
                        @error('slot')
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
                        <label for="title" class="form-label">Custom Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            id="title">
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <p class="form-text text-muted ms-2">
                            Must be unique <br>
                            Leave blank to use default title
                        </p>
                    </div>

                    <div class="form-group">
                        <label for="image-upload">Upload Background Image</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                            id="image-upload" accept=".jpg, .jpeg, .png">
                        @error('image')
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="save-hot-properties-btn">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
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

    const saveHotPropertiesBtn = document.getElementById('save-hot-properties-btn');

    saveHotPropertiesBtn.addEventListener('click', async () => {
        saveHotPropertiesBtn.disabled = true;
        saveHotPropertiesBtn.textContent = 'Saving...';

        const form = document.getElementById('hot-properties-form');
        const formData = new FormData(form);

        try {
            const response = await fetch('{{ route('properties.validateAddHotPropertiesForm') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData,
            });

            const data = await response.json();

            if (data.message === 'Validation failed') {
                saveHotPropertiesBtn.disabled = false;
                saveHotPropertiesBtn.textContent = 'Save';

                const inputElements = document.querySelectorAll('.is-invalid');
                inputElements.forEach(inputElement => {
                    inputElement.classList.remove('is-invalid');
                    const errorFeedback = inputElement.parentNode.querySelector('.invalid-feedback');
                    if (errorFeedback) {
                        errorFeedback.remove();
                    }
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
            saveHotPropertiesBtn.disabled = false;
            saveHotPropertiesBtn.textContent = 'Save';
        }
    });
</script>