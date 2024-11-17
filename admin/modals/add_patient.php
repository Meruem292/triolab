<div id="addPatientModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content" action="../admin/modals/api.php">
            <div class="modal-header">
                <h5 class="modal-title">Add New Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="firstname" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="lastname" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="number" name="contact" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" class="form-control" required>
                    </div>
                    <!-- Address Fields -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Province</label>
                        <input type="text" name="province" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Barangay</label>
                        <input type="text" name="barangay" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Street</label>
                        <input type="text" name="street" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Full Address</label>
                        <input type="text" id="fullAddress" class="form-control" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="add_patient" class="btn btn-primary">Save Patient</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelector('form').addEventListener('input', function() {
        // Get the values from each address field
        var province = document.querySelector('input[name="province"]').value;
        var city = document.querySelector('input[name="city"]').value;
        var barangay = document.querySelector('input[name="barangay"]').value;
        var street = document.querySelector('input[name="street"]').value;

        // Create the full address
        var addressParts = [province, city, barangay, street];
        var fullAddress = addressParts.filter(function(part) {
            return part.trim() !== ""; // Filter out empty parts
        }).join(", ");

        // Update the full address field
        document.getElementById('fullAddress').value = fullAddress;
    });
</script>
