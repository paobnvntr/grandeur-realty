<div class="modal fade" id="deleteModal{{$user->id}}" tabindex="-1" aria-labelledby="deleteModalLabel{{$user->id}}"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteModalLabel{{$user->id}}">Delete
                    Confirmation</h5>
                <button type="button" class="btn btn-outline-link" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong>{{ $user->username }}</strong>?
            </div>
            <div class="modal-footer">
                <form action="{{ route('users.deleteUser', $user->id) }}" method="POST" class="user">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-user delete-btn-{{$user->id}}">Confirm
                        Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForm = document.querySelector(`#deleteModal{{$user->id}} form`);
        const deleteButton = document.querySelector(`.delete-btn-{{$user->id}}`);

        deleteForm.addEventListener('submit', function () {
            deleteButton.disabled = true;
            deleteButton.textContent = 'Deleting...';
        });
    });
</script>