<div class="modal fade" id="viewMedicalRecordModal" tabindex="-1" aria-labelledby="viewMedicalRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewMedicalRecordModalLabel">Medical Records</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Table for medical records -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Record Date</th>
                            <th>Diagnosis</th>
                            <th>Treatment</th>
                            <th>Prescription</th>
                            <th>Print Record</th>
                        </tr>
                    </thead>
                    <tbody id="medicalRecordTableBody">
                        <!-- Medical record rows will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>