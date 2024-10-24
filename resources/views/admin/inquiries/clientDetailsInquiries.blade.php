<div class="modal fade" id="clientDetailsModal{{$inquiry->id}}" tabindex="-1"
    aria-labelledby="clientDetailsModalLabel{{$inquiry->id}}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 shadow-lg">
            <div class="modal-header pb-2 border-bottom">
                <h5 class="modal-title text-info" id="clientDetailsModalLabel{{$inquiry->id}}">Client Details</h5>
                <button type="button" class="btn btn-outline-link" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted">Client Name:</strong>
                        <span class="text-dark">{{ $inquiry->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted">Cellphone Number:</strong>
                        <span class="text-dark">{{ $inquiry->cellphone_number }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted">Email Address:</strong>
                        <span class="text-dark">{{ $inquiry->email }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>