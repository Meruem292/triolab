<div class="modal fade" id="addMedicalRecordModal" tabindex="-1" aria-labelledby="addMedicalRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMedicalRecordModalLabel">Add Medical Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../admin/modals/api.php">
                    <!-- Hidden input field for patient_id -->
                    <input type="hidden" name="patient_id" id="patient_id" />
                    
                    <div class="mb-3">
                        <label for="diagnosis" class="form-label">Diagnosis</label>
                        <textarea class="form-control" id="diagnosis" name="diagnosis" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="treatment" class="form-label">Treatment</label>
                        <textarea class="form-control" id="treatment" name="treatment" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="record_date" class="form-label">Record Date</label>
                        <input type="date" class="form-control" id="record_date" name="record_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit_medical_record">Save Record</button>
                </form>
            </div>
        </div>
    </div>
</div>
