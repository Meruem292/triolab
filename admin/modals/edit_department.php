<!-- edit department modal -->
<div id="editDepartment" class="modal fade" tabindex="-1" aria-labelledby="editDepartmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content" action="../admin/modals/api.php">
            <div class="modal-header">
                <h5 class="modal-title" id="editDepartmentLabel">Edit Department Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <input type="hidden" name="department_id" id="department_id">
                            <label class="form-label">Department Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="department" required placeholder="Enter Department Name">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="edit_department" class="btn btn-primary">Edit Doctor</button>
            </div>
        </form>
    </div>
</div>