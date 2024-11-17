<div id="editPatient" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content" action="../admin/modals/api.php">
            <div class="modal-header">
                <h5 class="modal-title">Update Patient Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="patientId" id="patientId"> <!-- Hidden field for patient ID -->

                    <div class="col-md-12 mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="firstname" id="patientFirstname" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="lastname" id="patientLastname" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="patientEmail" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact" id="patientContact" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" id="patientDob" class="form-control" required>
                    </div>

                    <!-- Address Fields -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Province</label>
                        <input type="text" name="province" id="patientProvince" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" id="patientCity" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Barangay</label>
                        <input type="text" name="barangay" id="patientBarangay" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Street</label>
                        <input type="text" name="street" id="patientStreet" class="form-control">
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="edit_patient" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>