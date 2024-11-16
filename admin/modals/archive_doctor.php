<!-- ARCHIVE DOCTOR -->
<div id="archiveDoctor" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content" action="../admin/modals/api.php">
                <div class="modal-header">
                    <h5 class="modal-title">Archive Doctor Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="doctorIdDelete" name="doctorIdDelete">
                    <p>Are you sure you want to archive the doctor <strong id="doctorName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="archive_doctor" class="btn btn-danger">Archive</button>
                </div>
            </form>
        </div>
    </div>