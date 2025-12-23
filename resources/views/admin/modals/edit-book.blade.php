<!-- Edit Book Modal -->
<div class="modal fade" id="editBookModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="bi bi-pencil"></i> Edit Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBookForm" action="" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="editBookId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Book Name *</label>
                        <input type="text" class="form-control" id="editBookname" name="Bookname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Author *</label>
                        <input type="text" class="form-control" id="editAuthor" name="Author" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category *</label>
                        <input type="text" class="form-control" id="editCategory" name="Category" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity *</label>
                        <input type="number" class="form-control" id="editQuantity" name="Quantity" min="0" required>
                        <small class="text-muted">Note: Cannot reduce below currently borrowed books</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update Book</button>
                </div>
            </form>
        </div>
    </div>
</div>