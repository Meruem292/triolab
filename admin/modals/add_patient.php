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
                        <label class="form-label">Birthplace</label>
                        <input type="date" name="birthplace" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" class="form-control" required>
                    </div>
                    <!-- <div class="col-md-4">
                        <div class="form-group">
                            <label>Province <span class="text-danger">*</span></label>
                            <select id="province" class="form-control" required></select>
                            <input type="hidden" id="provinceName" class="form-control" name="province" placeholder="Province Name" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>City <span class="text-danger">*</span></label>
                            <select id="city" class="form-control" required></select>
                            <input type="hidden" id="cityName" class="form-control" name="city" placeholder="City Name" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Barangay <span class="text-danger">*</span></label>
                            <select id="barangay" class="form-control" required></select>
                            <input type="hidden" id="barangayName" class="form-control" name="barangay" placeholder="Barangay Name" readonly required>
                        </div>
                    </div> -->
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
    document.addEventListener('DOMContentLoaded', function() {
        const addressFields = ['province', 'city', 'barangay', 'street'];
        const fullAddressField = document.getElementById('fullAddress');

        // Function to update the full address
        function updateFullAddress() {
            let addressParts = addressFields.map(field => {
                return document.querySelector(`input[name="${field}"]`).value.trim();
            }).filter(part => part !== "");

            fullAddressField.value = addressParts.join(", ");
        }

        // Attach event listeners to the address fields
        addressFields.forEach(field => {
            const input = document.querySelector(`input[name="${field}"]`);
            input.addEventListener('input', updateFullAddress);
        });
    });
</script>