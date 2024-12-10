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

