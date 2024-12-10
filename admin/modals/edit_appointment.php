<div id="editAppointment" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="editAppointmentForm" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Appointment Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Hidden Appointment ID -->
                    <div class="col-md-12 mb-2">
                        <input type="text" name="appointmentId" id="appointmentId">
                    </div>

                    <!-- Patient Information -->
                    <div class="col-md-12 mb-2">
                        <label for="patientName" class="form-label">Patient</label>
                        <input type="text" name="patientName" id="patientName" class="form-control">
                    </div>

                    <!-- Appointment Date and Time -->
                    <div class="col-md-12 mb-2">
                        <label for="appointmentDate" class="form-label">Appointment Date</label>
                        <input type="datetime-local" name="appointmentDate" id="appointmentDate" class="form-control">
                    </div>

                    <!-- Status -->
                    <div class="col-md-12 mb-2">
                        <label for="status" class="form-label">Appointment Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <!-- Services Table -->
                    <div class="col-md-12 mb-2">
                        <label for="servicesList" class="form-label">Services</label>
                        <table class="table" id="servicesTable">
                            <thead>
                                <tr>
                                    <th>Service Name</th>
                                    <th>Cost</th>
                                    <th>Doctor</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="servicesList"></tbody>
                        </table>
                    </div>

                    <!-- Total Cost Field -->
                    <div class="col-md-12 mb-2">
                        <label for="totalCost" class="form-label">Total Cost</label>
                        <input type="text" name="totalCost" id="totalCost" class="form-control" readonly>
                    </div>


                    <!-- Doctor Information -->
                    <div class="col-md-12 mb-2">
                        <label for="doctorInfo" class="form-label">Doctor</label>
                        <input type="hidden" name="doctorInfo" id="doctorInfo" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <!-- Save Changes Button -->
                <button type="button" id="saveChangesBtn" class="btn btn-primary">Save Changes</button>

            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('saveChangesBtn').addEventListener('click', function() {
        const appId = document.getElementById('appointmentId').value;
        const patientName = document.getElementById('patientName').value;
        const appointmentDate = document.getElementById('appointmentDate').value;
        const status = document.getElementById('status').value;
        const totalCost = document.getElementById('totalCost').value;
        const services = []; // Collect the updated services info

        // Gather service data from the table (this assumes the services are dynamically added to the table)
        document.querySelectorAll('#servicesList tr').forEach(row => {
            const serviceId = row.getAttribute('data-service-id');
            services.push(serviceId);
        });

        // Create the data object to send to the server
        const data = {
            appId: appId,
            patientName: patientName,
            appointmentDate: appointmentDate,
            status: status,
            totalCost: totalCost,
            services: services
        };

        // Make an AJAX request to update the appointment
        fetch('update_appointment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Appointment updated successfully!');
                    // Optionally close the modal or reset the form here
                } else {
                    alert('Failed to update appointment.');
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>