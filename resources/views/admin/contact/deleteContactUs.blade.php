<div class="modal fade" id="deleteModal{{$contact->id}}" tabindex="-1"
    aria-labelledby="deleteModalLabel{{$contact->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteModalLabel{{$contact->id}}">Delete
                    Confirmation</h5>
                <button type="button" class="btn btn-outline-link" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the message from <strong>{{ $contact->name }}</strong>?
            </div>
            <div class="modal-footer">
                <form action="{{ route('contactUs.deleteContactUs', $contact->id) }}" method="POST" class="user">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-user delete-btn-{{$contact->id}}">Confirm
                        Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForm = document.querySelector(`#deleteModal{{$contact->id}} form`);
        const deleteButton = document.querySelector(`.delete-btn-{{$contact->id}}`);

        deleteForm.addEventListener('submit', function () {
            deleteButton.disabled = true;
            deleteButton.textContent = 'Deleting...';
        });
    });
</script>