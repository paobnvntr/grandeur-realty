<div class="modal fade" id="deleteModal{{$inquiry->id}}" tabindex="-1"
    aria-labelledby="deleteModalLabel{{$inquiry->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteModalLabel{{$inquiry->id}}">Delete Confirmation</h5>
                <button type="button" class="btn btn-outline-link" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the inquiry from <strong>{{ $inquiry->name }}</strong>?
            </div>
            <div class="modal-footer">
                <form action="{{ route('inquiries.deleteInquiry', $inquiry->id) }}" method="POST" class="inquiry">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-inquiry-delete-{{$inquiry->id}}">Confirm
                        Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteInquiryForm = document.querySelector(`#deleteModal{{$inquiry->id}} form`);
        const deleteInquiryButton = document.querySelector(`.btn-inquiry-delete-{{$inquiry->id}}`);

        deleteInquiryForm.addEventListener('submit', function () {
            deleteInquiryButton.disabled = true;
            deleteInquiryButton.textContent = 'Deleting...';
        });
    });
</script>