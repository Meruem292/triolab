<div id="editAppointment" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="editAppointmentForm" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Appointment Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="text" name="appointmentId" id="appointmentId" class="form-control" hidden>
                    <input type="text" name="id" id="id" class="form-control" hidden>
                    <input type="text" name="doctorInfo" id="doctorInfo" class="form-control" hidden>
                    <select name="status" id="status" class="form-control" hidden></select>
                    <select name="paid" id="paid" class="form-control" hidden></select>
                </div>

                <div class="col-md-12 mb-2">
                    <label for="patientName" class="form-label">Patient</label>
                    <input type="text" name="patientName" id="patientName" class="form-control">
                </div>

                <div class="col-md-12 mb-2">
                    <label for="appointmentDate" class="form-label">Appointment Date</label>
                    <input type="datetime-local" name="appointmentDate" id="appointmentDate" class="form-control">
                </div>

                <div class="col-md-12 mb-2">
                    <label for="servicesList" class="form-label">Services</label>
                    <table class="table" id="servicesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Service Name</th>
                                <th>Cost</th>
                                <th>Doctor</th>
                                <th>Payment Status</th>
                                <th>Service Status</th>
                            </tr>
                        </thead>
                        <tbody id="servicesList"></tbody>
                    </table>
                </div>

                <div class="col-md-12 mb-2">
                    <label for="totalCost" class="form-label">Total Cost</label>
                    <input type="text" name="totalCost" id="totalCost" class="form-control" readonly>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" id="saveChangesBtn" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>