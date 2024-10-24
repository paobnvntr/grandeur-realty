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
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:question-circle-bold-duotone" class="fs-6 me-2"></iconify-icon>
                        <h5 class="card-title mb-0">Property Inquiries</h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-nowrap align-middle mb-0">
                            <thead>
                                <tr class="border-2 border-bottom border-primary border-0">
                                    <th scope="col" class="ps-0 sticky-column-left">No.</th>
                                    <th scope="col">Property Name</th>
                                    <th scope="col">Client Details</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Message</th>
                                    <th scope="col">Date Sent</th>
                                    <th scope="col" class="text-end sticky-column-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($inquiries->count() > 0)
                                    @foreach($inquiries as $index => $inquiry)
                                        <tr>
                                            <td class="ps-0 sticky-column-left">{{ $index + 1 }}</td>
                                            <td>{{ $inquiry->property_name }}</td>
                                            <td>
                                                <a class="fw-medium d-block link-info userUsername" data-bs-toggle="modal"
                                                    data-bs-target="#clientDetailsModal{{$inquiry->id}}">
                                                    View Details
                                                </a>
                                            </td>
                                            <td>{{ $inquiry->subject }}</td>
                                            <td>
                                                <a class="fw-medium d-block link-info userUsername" data-bs-toggle="modal"
                                                    data-bs-target="#messageModal{{ $inquiry->id }}">
                                                    {{ Str::limit($inquiry->message, 30) }}</a>
                                            </td>
                                            <td>{{ $inquiry->created_at->format('M d, Y') }}</td>
                                            <td class="text-center sticky-column-right">
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{$inquiry->id}}">Delete</button>
                                            </td>
                                        </tr>

                                        @include('admin.inquiries.clientDetailsInquiries')
                                        @include('admin.inquiries.messageInquiries')
                                        @include('admin.inquiries.deleteInquiries')
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" class="text-center">No inquiries found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="mb-0">Showing {{ $inquiries->firstItem() }} to {{ $inquiries->lastItem() }} of
                                {{ $inquiries->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $inquiries->links() }}
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