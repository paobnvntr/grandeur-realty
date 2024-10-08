<div class="modal fade" id="messageModal{{ $contact->id }}" tabindex="-1"
    aria-labelledby="messageModalLabel{{ $contact->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="messageModalLabel{{ $contact->id }}">Message from {{ $contact->name }}</h5>
                <button type="button" class="btn btn-outline-link" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ $contact->message }}</p>
            </div>
        </div>
    </div>
</div>