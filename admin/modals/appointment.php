<?php include('functions.php');

$slots = getAppointmentSlots();
$services = getServices();
$doctors = getDoctors();
$paitents = getPatients();
?>
<!-- Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="appointmentModalLabel">Appointment</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form action="" method="post">
          <div class="mb-3">
            <label for="appointmentDate" class="form-label">Date</label>
            <input type="date" class="form-control" id="appointmentDate" name="appointmentDate" required>
          </div>

          <div class="mb-3">
            <label for="appointmentPatientSearch" class="form-label">Patient</label>
            <input type="text" class="form-control" id="appointmentPatientSearch" placeholder="Search for a patient...">

            <select name="appointmentPatient" id="appointmentPatient" class="form-select" disabled>
              <option value="">Select a patient</option>
            </select>
          </div>


          <div class="mb-3">
            <label for="appointmentSchedule" class="form-label">Schedule</label>
            <select name="appointmentSchedule" id="appointmentSchedule">
              <?php foreach ($slots as $slot): ?>
                <option class="form-select" value="<?php echo $slot['id'] ?>"><?php echo $slot['schedule'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="appointmentTime" class="form-label">Time</label>
            <input type="time" class="form-control" id="appointmentTime" name="appointmentTime" required>
          </div>

          <div class="mb-3">
            <label for="appointmentService" class="form-label">Services</label>
            <select name="appointmentService" id="appointmentService">
              <?php foreach ($services as $service): ?>
                <option class="form-select" value="<?php echo $service['id'] ?>"><?php echo $service['type'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="appointmentDoctor" class="form-label">Doctor</label>
            <select name="appointmentDoctor" id="appointmentDoctor">
              <?php foreach ($doctors as $doctor): ?>
                <option class="form-select" value="<?php echo $doctor['employee_id'] ?>"><?php echo "Dr. " . $doctor['lastname'] . " - " . $doctor['department'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.getElementById('appointmentPatientSearch').addEventListener('input', function() {
    const searchQuery = this.value;

    // If there's no query, disable the select dropdown
    if (!searchQuery) {
      document.getElementById('appointmentPatient').disabled = true;
      return;
    }

    // Enable the select dropdown when there's a query
    document.getElementById('appointmentPatient').disabled = false;

    // Send an AJAX request to fetch matching patients
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'search_patients.php?query=' + encodeURIComponent(searchQuery), true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        const patientSelect = document.getElementById('appointmentPatient');

        // Clear current options
        patientSelect.innerHTML = '<option value="">Select a patient</option>';

        if (response.message) {
          // Display a message if no results found
          const option = document.createElement('option');
          option.disabled = true;
          option.textContent = response.message;
          patientSelect.appendChild(option);
        } else {
          // Add new options
          response.forEach(function(patient) {
            const option = document.createElement('option');
            option.value = patient.id;
            option.textContent = patient.lastname + ', ' + patient.firstname;
            patientSelect.appendChild(option);
          });
        }
      }
    };

    xhr.send();
  });
</script>