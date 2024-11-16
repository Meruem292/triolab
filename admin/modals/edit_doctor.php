    <!-- UPDATE DOCTOR -->
    <div id="editDoctor" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content" action="../admin/modals/api.php">
                <div class="modal-header">
                    <h5 class="modal-title">Update Doctor Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="employee_id" id="editDoctorId">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editDoctorFirstname" name="firstname" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editDoctorLastname" name="lastname" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editDoctorUsername" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="editDoctorEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="department_id" id="editDoctorDepartment" class="form-select" required>
                            <option value="1">Doctor/Physician</option>
                            <option value="3">Medical Technician</option>
                            <option value="2">Radiological Technologist</option>
                            <option value="4">Medical Consultant</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="edit_doctor" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

 