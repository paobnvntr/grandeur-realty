@extends('admin.layouts.app')

@section('contents')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <iconify-icon icon="solar:file-text-bold-duotone" class="fs-6 me-2"></iconify-icon>
                            <h5 class="card-title mb-0">List of Logs</h5>
                        </div>

                        <div class="d-flex align-items-center justify-content-end alert alert-warning mb-0" role="alert" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                            Note: Logs are arranged in descending order by creation date.
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap align-middle mb-0">
                            <thead>
                                <tr class="border-2 border-bottom border-primary border-0">
                                    <th scope="col" class="ps-0">No.</th>
                                    <th scope="col" class="ps-0">Type</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Message</th>
                                    <th scope="col">Created At</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @if($logs->count() > 0)
                                    @foreach($logs as $log)
                                        <tr>
                                            <th class="align-middle text-center">
                                                {{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}
                                            </th>
                                            <td>{{ $log->type }}</td>
                                            <td>{{ $log->user }}</td>
                                            <td>{{ $log->subject }}</td>
                                            <td>{{ $log->message }}</td>
                                            <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No logs found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="mb-0">Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of
                                {{ $logs->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $logs->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection