@extends('admin.layouts.app')

@section('contents')
<div class="container-fluid pb-0">
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

    <div class="p-2">
        <div class="d-flex justify-content-end align-items-center">
            <a href="{{ route('properties.soldProperties') }}" class="btn btn-success btn-sm me-2">
                Sold Properties
            </a>

            <a href="{{ route('properties.settings') }}" class="btn btn-sm btn-light d-flex align-items-center">
                <iconify-icon icon="solar:settings-line-duotone" class="fs-6 me-1"></iconify-icon>
                Properties Settings
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6 me-2"></iconify-icon>
                            <h5 class="card-title mb-0">Properties List</h5>
                        </div>

                        <div class="d-flex align-items-center justify-content-end">
                            <a href="{{ route('properties.addProperties') }}" class="btn btn-dark btn-sm">
                                Add Properties
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-nowrap align-middle mb-0">
                            <thead>
                                <tr class="border-2 border-bottom border-primary border-0">
                                    <th scope="col" class="ps-0 sticky-column-left">No.</th>
                                    <th scope="col">Property Name</th>
                                    <th scope="col">Client Name</th>
                                    <th scope="col">Date Approved</th>
                                    <th scope="col" class="text-end sticky-column-right">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if($properties->count() > 0)
                                    @foreach($properties as $index => $property)
                                        <tr>
                                            <td class="ps-0 sticky-column-left">{{ $index + 1 }}</td>
                                            <td>For {{ ucfirst($property->property_status) }}: {{ $property->size }} ft²
                                                {{ ucfirst($property->property_type) }} in {{ $property->city }}</td>
                                            <td>{{ \Illuminate\Support\Str::title($property->name) }}</td>
                                            <td>{{ $property->created_at->format('Y-m-d') }}</td>
                                            <td class="text-end sticky-column-right">
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#detailsModal-{{ $property->id }}">
                                                    View Details
                                                </button>
                                            </td>
                                        </tr>

                                        @include('admin.properties.detailsProperties')
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No properties found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="mb-0">Showing {{ $properties->firstItem() }} to {{ $properties->lastItem() }} of
                                {{ $properties->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $properties->links() }}
                        </div>
                    </div>
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
</script>
@endsection