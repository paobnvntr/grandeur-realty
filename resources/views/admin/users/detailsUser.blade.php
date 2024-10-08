<div class="modal fade" id="userDetailsModal{{$user->id}}" tabindex="-1"
    aria-labelledby="userDetailsModalLabel{{$user->id}}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 shadow-lg">
            <div class="modal-header pb-2 border-bottom">
                <h5 class="modal-title text-info" id="userDetailsModalLabel{{$user->id}}">User Details</h5>
                <button type="button" class="btn btn-outline-link" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted">Name:</strong>
                        <span class="text-dark">{{ $user->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted">Username:</strong>
                        <span class="text-dark">{{ $user->username }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted">Email:</strong>
                        <span class="text-dark">{{ $user->email }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted">Role:</strong>
                        <span class="text-dark">{{ $user->level }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted">Created At:</strong>
                        <span class="text-dark">{{ $user->created_at->format('M d, Y H:i') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted">Updated At:</strong>
                        <span class="text-dark">{{ $user->updated_at->format('M d, Y H:i') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>