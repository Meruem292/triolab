<!-- ADD DOCTOR Modal -->
<?php
$departments = getDepartments();
?>
<div id="addDoctor" class="modal fade" tabindex="-1" aria-labelledby="addDoctorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content" action="../admin/modals/api.php">
            <div class="modal-header">
                <h5 class="modal-title" id="addDoctorLabel">Add Doctor Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="firstname" required placeholder="Enter first name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="lastname" required placeholder="Enter last name">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                    <!-- Dynamically generated Employee ID -->
                    <input type="text" class="form-control" name="employee_id" value="<?= generateEmployeeID('TRLB') ?>" readonly required placeholder="Auto-generated employee ID">
                </div>
                <div class="mb-3">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="username" required placeholder="Enter username">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" required placeholder="Enter email address">
                </div>

                <div class="mb-3">
                    <label class="form-label">Department <span class="text-danger">*</span></label>
                    <select name="department_id" id="editDoctorDepartment" class="form-select" required>
                        <!-- Dynamically populate departments -->
                        <?php foreach ($departments as $department): ?>
                            <option value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="add_doctor" class="btn btn-primary">Add Doctor</button>
            </div>
        </form>
    </div>
</div>