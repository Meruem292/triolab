<?php include('functions.php');

$slots = getAppointmentSlots();
$services = getServices();
$doctors = getDoctors();
$paitents = getPatients();
$departments = getDepartments();
?>


<div class="modal fade" id="addAppointmentModal" tabindex="-1" role="dialog" aria-labelledby="addAppointmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="addAppointmentForm" method="post" action="../admin/modals/api.php">
        <div class="modal-header">
          <h5 class="modal-title" id="addAppointmentModalLabel">Add Appointment</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <!-- Patient -->
          <div class="form-group mb-2">
            <label for="patient">Patient</label>
            <select name="patient" id="patient" class="form-control">
              <option value="">Select a patient</option>
              <?php foreach ($paitents as $patient) : ?>
                <option value="<?php echo $patient['id']; ?>"><?php echo $patient['firstname'] . ' ' . $patient['lastname']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Service -->
          <div class="form-group mb-2">
            <label for="service">Service</label>
            <select name="service" id="service" class="form-control">
              <option value="">Select a service</option>
              <?php foreach ($services as $service) : ?>
                <option value="<?php echo $service['id']; ?>"><?php echo $service['type']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Department -->
          <div class="form-group mb-2">
            <label for="department">Department</label>
            <select name="department" id="department" class="form-control">
              <option value="">Select a department</option>
              <?php
              // Example: Assuming $departments is an array of department data
              foreach ($departments as $department) : ?>
                <option value="<?php echo $department['id']; ?>">
                  <?php echo $department['name']; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Doctor -->
          <div class="form-group mb-2">
            <label for="doctor">Doctor</label>
            <select name="doctor" id="doctor" class="form-control">
              <option value="">Select a doctor</option>
              <!-- Options will be populated dynamically -->
            </select>
          </div>

          <!-- Date -->
          <div class="form-group mb-2">
            <label for="date">Date</label>
            <input type="date" name="date" id="date" class="form-control">
          </div>

          <!-- schedule -->
          <div class="form-group mb-2">
            <label for="schedule">Schedule</label>
            <select name="schedule" id="schedule" class="form-control">
              <option value="">Select a time</option>
              <?php foreach ($slots as $slot) : ?>
                <option value="<?php echo $slot['id']; ?>"><?php echo $slot['schedule']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <!-- time -->
          <div class="form-group mb-2">
            <label for="time">Time</label>
            <input type="time" name="time" id="time" class="form-control">
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="add_appointment" class="btn btn-primary">Add Appointment</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  const doctors = <?php echo json_encode($doctors); ?>;

  document.getElementById('department').addEventListener('change', function() {
    const departmentId = this.value; 
    const doctorSelect = document.getElementById('doctor');


    doctorSelect.innerHTML = '<option value="">Select a doctor</option>';

    doctors.forEach(doctor => {
      if (doctor.department_id == departmentId) {
        const option = document.createElement('option');
        option.value = doctor.employee_id;
        option.textContent = `${doctor.firstname} ${doctor.lastname}`;
        doctorSelect.appendChild(option);
      }
    });
  });
</script>

<script>
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("patientTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td");
                if (td.length > 0) {
                    var showRow = false;
                    for (var j = 0; j < td.length; j++) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            showRow = true;
                            break; // Stop looking at other columns for this row
                        }
                    }
                    if (showRow) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>

    