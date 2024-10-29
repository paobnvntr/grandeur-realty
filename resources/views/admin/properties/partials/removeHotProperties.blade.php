<div class="modal fade" id="removeHotPropertyModal{{$city->id}}" tabindex="-1"
    aria-labelledby="removeHotPropertyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header pb-2 border-bottom">
                <h5 class="modal-title text-danger">Remove Hot Properties</h5>
                <button type="button" class="btn btn-outline-link" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-muted">
                Are you sure you want to remove the hot property for <strong>{{ $city->city }}</strong>?
            </div>
            <div class="modal-footer">
                <form action="{{ route('properties.deleteHotProperties', $city->id) }}" method="POST" class="user">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-user remove-btn-{{$city->id}}">Confirm
                        Remove</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForm = document.querySelector(`#removeHotPropertyModal{{ $city->id }} form`);
        const deleteButton = document.querySelector(`.remove-btn-{{ $city->id }}`);

        if (deleteForm && deleteButton) {
            deleteForm.addEventListener('submit', function () {
                deleteButton.disabled = true;
                deleteButton.textContent = 'Deleting...';
            });
        }
    });
</script>