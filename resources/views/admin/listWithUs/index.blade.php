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

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <iconify-icon icon="solar:document-add-bold-duotone" class="fs-6 me-2"></iconify-icon>
                            <h5 class="card-title mb-0">Listing Requests</h5>
                        </div>

                        <div class="d-flex align-items-center justify-content-end alert alert-warning mb-0" role="alert" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                            Note: Disapproving a request will result in its permanent deletion and it cannot be recovered.
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-nowrap align-middle mb-0">
                            <thead>
                                <tr class="border-2 border-bottom border-primary border-0">
                                    <th scope="col" class="ps-0">No.</th>
                                    <th scope="col">Client Name</th>
                                    <th scope="col">Property Type</th>
                                    <th scope="col">City</th>
                                    <th scope="col">Date Requested</th>
                                    <th scope="col" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($listings->count() > 0)
                                    @foreach($listings as $index => $listing)
                                        <tr>
                                            <td class="ps-0">{{ $index + 1 }}</td>
                                            <td>{{ \Illuminate\Support\Str::title($listing->name) }}</td>
                                            <td>{{ ucfirst($listing->property_type) }}</td>
                                            <td>{{ ucfirst($listing->city) }}</td>
                                            <td>{{ $listing->created_at->format('Y-m-d') }}</td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#detailsModal-{{ $listing->id }}">
                                                    View Details
                                                </button>
                                            </td>
                                        </tr>

                                        @include('admin.listWithUs.detailsListWithUs')
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No requests found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="mb-0">Showing {{ $listings->firstItem() }} to {{ $listings->lastItem() }} of
                                {{ $listings->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $listings->links() }}
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