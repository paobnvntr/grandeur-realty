@extends('admin.layouts.app')

@section('contents')
<div class="container-fluid">
    @if(Session::has('success'))
        <div class="alert alert-success floating-alert" id="alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    @if(Session::has('failed'))
        <div class="alert alert-danger floating-alert" id="alert-failed" role="alert">
            {{ Session::get('failed') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">List of Contact Us Inquiries</h5>
                    <div class="table-responsive">
                        <table class="table text-nowrap align-middle mb-0">
                            <thead>
                                <tr class="border-2 border-bottom border-primary border-0">
                                    <th scope="col" class="ps-0 sticky-column-left">No.</th>
                                    <th scope="col" class="ps-0">Name</th>
                                    <th scope="col">Cellphone Number</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Message</th>
                                    <th scope="col">Date Sent</th>
                                    <th scope="col" class="text-center sticky-column-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @if($contacts->count() > 0)
                                    @foreach($contacts as $contact)
                                        <tr>
                                            <th class="align-middle text-center sticky-column-left">
                                                {{ $loop->iteration + ($contacts->currentPage() - 1) * $contacts->perPage() }}
                                            </th>
                                            <td>{{ $contact->name }}</td>
                                            <td>{{ $contact->cellphone_number }}</td>
                                            <td>{{ $contact->email }}</td>
                                            <td>{{ $contact->subject }}</td>
                                            <td>
                                                <a class="fw-medium d-block link-info userUsername" data-bs-toggle="modal"
                                                    data-bs-target="#messageModal{{ $contact->id }}">
                                                    {{ Str::limit($contact->message, 30) }}</a>
                                            </td>
                                            <td>{{ $contact->created_at->format('M d, Y') }}</td>
                                            <td class="text-center sticky-column-right">
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{$contact->id}}">Delete</button>
                                            </td>
                                        </tr>

                                        @include('admin.contact.messageContactUs')
                                        @include('admin.contact.deleteContactUs')
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">No contact inquiries found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="mb-0">Showing {{ $contacts->firstItem() }} to {{ $contacts->lastItem() }} of
                                {{ $contacts->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $contacts->links() }}
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