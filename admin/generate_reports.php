<?php
// Include your DB connection file
include('db.php');

// Function to fetch departments
function getDepartments($pdo)
{
    $stmt = $pdo->query("SELECT * FROM departments WHERE is_archive = 0");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch doctors
function getDoctors($pdo)
{
    $stmt = $pdo->query("SELECT * FROM doctor WHERE is_archive = 0");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function generateReport($pdo, $filters)
{
    $sql = "SELECT a.id, a.appointment_date, a.appointment_time, p.firstname AS patient_name, 
            d.firstname AS doctor_fname, d.lastname AS doctor_lname, s.service AS service_name, a.selectedPayment, a.status, 
            a.paid, pr.amount AS payment_amount, pr.payment_mode_id, pr.status AS payment_status
            FROM appointment a
            LEFT JOIN patient p ON a.patient_id = p.id
            LEFT JOIN doctor d ON a.doctor_id = d.employee_id
            LEFT JOIN services s ON a.service_id = s.id
            LEFT JOIN payment_receipts pr ON a.id = pr.appointment_id
            WHERE a.is_archive = 0";

    $params = [];

    // Add dynamic filters
    if (!empty($filters['start_date'])) {
        $sql .= " AND a.appointment_date >= :start_date";
        $params['start_date'] = $filters['start_date'];
    }

    if (!empty($filters['end_date'])) {
        $sql .= " AND a.appointment_date <= :end_date";
        $params['end_date'] = $filters['end_date'];
    }

    if (!empty($filters['doctor_id'])) {
        $sql .= " AND a.doctor_id = :doctor_id";
        $params['doctor_id'] = $filters['doctor_id'];
    }

    if (!empty($filters['department_id'])) {
        $sql .= " AND s.department_id = :department_id";
        $params['department_id'] = $filters['department_id'];
    }

    if (!empty($filters['status'])) {
        $sql .= " AND a.status = :status";
        $params['status'] = $filters['status'];
    }

    if (!empty($filters['payment_status'])) {
        $sql .= " AND pr.status = :payment_status";
        $params['payment_status'] = $filters['payment_status'];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Analytics
    $totalAmount = 0;
    $totalAppointments = count($appointments);
    $appointmentStatuses = ['Pending' => 0, 'Completed' => 0, 'Cancelled' => 0];
    $paymentStatuses = ['Pending' => 0, 'Approved' => 0, 'Disapproved' => 0];
    $paymentAmounts = [];

    foreach ($appointments as $row) {
        $totalAmount += $row['payment_amount'];
        $appointmentStatuses[$row['status']]++;
        if (!empty($row['payment_status'])) {
            $paymentStatuses[$row['payment_status']]++;
        }
        $paymentAmounts[] = $row['payment_amount'];
    }

    $averagePayment = $totalAppointments > 0 ? $totalAmount / $totalAppointments : 0;

    return [
        'appointments' => $appointments,
        'analytics' => [
            'totalAmount' => $totalAmount,
            'averagePayment' => $averagePayment,
            'appointmentStatuses' => $appointmentStatuses,
            'paymentStatuses' => $paymentStatuses,
            'totalAppointments' => $totalAppointments
        ]
    ];
}

// Handle form submission for report generation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect filter values from the form
    $filters = [
        'start_date' => isset($_POST['start_date']) ? $_POST['start_date'] : '',
        'end_date' => isset($_POST['end_date']) ? $_POST['end_date'] : '',
        'doctor_id' => isset($_POST['doctor_id']) ? $_POST['doctor_id'] : '',
        'department_id' => isset($_POST['department_id']) ? $_POST['department_id'] : '',
        'status' => isset($_POST['status']) ? $_POST['status'] : '',
        'payment_status' => isset($_POST['payment_status']) ? $_POST['payment_status'] : ''
    ];

    // Generate report based on filters
    $reportData = generateReport($pdo, $filters);
}
?>


<style>
    /* Styles for hiding filters when printing */
    @media print {

        .filters-section,
        .report-section button {
            display: none;
        }
    }

    .report-section {
        width: 100%;
    }

    .report-table,
    .analytics {
        margin-top: 20px;
        width: 100%;
        border-collapse: collapse;
    }

    .report-table th,
    .report-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .analytics li {
        list-style-type: none;
    }
</style>


<h1>Generate Appointment Report</h1>

<div class="filters-section">
    <form method="POST">
        <div class="row">
            <div class="col-md-4">
                <!-- Form inputs for filters -->
                <label for="start_date">Start Date:</label>
                <input type="date" class="form-control" name="start_date" id="start_date" value="<?php echo $_POST['start_date'] ?? ''; ?>"><br>

                <label for="doctor_id">Doctor:</label>
                <select class="form-control" name="doctor_id" id="doctor_id">
                    <option value="">Select Doctor</option>
                    <?php
                    $doctors = getDoctors($pdo);
                    foreach ($doctors as $doctor) {
                        echo "<option value='{$doctor['employee_id']}' " . ($_POST['doctor_id'] == $doctor['employee_id'] ? 'selected' : '') . ">" . ucfirst($doctor['firstname']) . " " . ucfirst($doctor['lastname']) . "</option>";
                    }
                    ?>
                </select><br>
            </div>

            <div class="col-md-4">
                <label for="end_date">End Date:</label>
                <input type="date" class="form-control" name="end_date" id="end_date" value="<?php echo $_POST['end_date'] ?? ''; ?>"><br>

                <label for="department_id">Department:</label>
                <select class="form-control" name="department_id" id="department_id">
                    <option value="">Select Department</option>
                    <?php
                    $departments = getDepartments($pdo);
                    foreach ($departments as $department) {
                        echo "<option value='{$department['id']}' " . ($_POST['department_id'] == $department['id'] ? 'selected' : '') . ">{$department['name']}</option>";
                    }
                    ?>
                </select><br>
            </div>

            <div class="col-md-4">
                <label for="payment_status">Payment Status:</label>
                <select class="form-control" name="payment_status" id="payment_status">
                    <option value="">Select Payment Status</option>
                    <option value="Pending" <?php echo (isset($_POST['payment_status']) && $_POST['payment_status'] == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="Approved" <?php echo (isset($_POST['payment_status']) && $_POST['payment_status'] == 'Approved' ? 'selected' : ''); ?>>Approved</option>
                    <option value="Disapproved" <?php echo (isset($_POST['payment_status']) && $_POST['payment_status'] == 'Disapproved' ? 'selected' : ''); ?>>Disapproved</option>
                </select><br>

                <label for="status">Status:</label>
                <select class="form-control" name="status" id="appointment_status">
                    <option value="">Select Status</option>
                    <option value="Pending" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="Completed" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Completed' ? 'selected' : ''); ?>>Completed</option>
                    <option value="Cancelled" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                </select><br>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success mb-2">Generate Report</button>
        </div>
    </form>
</div>

<?php if (isset($reportData)) { ?>
    <div class="report-section" id="report-section">
        <h2>Generated Report</h2>
        <h3>
            <?php
            // Prepare an array to store dynamic title parts
            $titleParts = [];

            // Check if both start_date and end_date are set, and format the range
            if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
                $titleParts[] = "Date Range: " . $_POST['start_date'] . " to " . $_POST['end_date'];
            } elseif (!empty($_POST['start_date'])) {
                $titleParts[] = "Start Date: " . $_POST['start_date'];
            } elseif (!empty($_POST['end_date'])) {
                $titleParts[] = "End Date: " . $_POST['end_date'];
            }

            // Check and display the doctor's name if selected
            if (!empty($_POST['doctor_id'])) {
                $doctor = array_filter($doctors, fn($d) => $d['employee_id'] == $_POST['doctor_id']);
                $doctorName = reset($doctor);
                if ($doctorName) {
                    $titleParts[] = "Doctor: " . ucfirst(strtolower($doctorName['firstname'])) . " " . ucfirst(strtolower($doctorName['lastname']));
                }
            }

            // Check and display the department name if selected
            if (!empty($_POST['department_id'])) {
                $department = array_filter($departments, fn($d) => $d['id'] == $_POST['department_id']);
                $departmentName = reset($department);
                if ($departmentName) {
                    $titleParts[] = "Department: " . ucfirst(strtolower($departmentName['name']));
                }
            }

            // Check and display the appointment status if selected
            if (!empty($_POST['status'])) {
                $titleParts[] = "Status: " . ucfirst(strtolower($_POST['status']));
            }

            // Check and display the payment status if selected
            if (!empty($_POST['payment_status'])) {
                $titleParts[] = "Payment Status: " . ucfirst(strtolower($_POST['payment_status']));
            }

            // Combine all parts to create the dynamic title
            echo implode(" | ", $titleParts) ?: "No filters selected";
            ?>
        </h3>

        <!-- Table with report data -->
        <table class="report-table table table-striped">
            <thead>
                <tr>
                    <th>Appointment Date</th>
                    <th>Appointment Time</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Service</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reportData['appointments'] as $appointment) { ?>
                    <tr>
                        <td><?php echo $appointment['appointment_date']; ?></td>
                        <td><?php echo $appointment['appointment_time']; ?></td>
                        <td><?php echo ucfirst(strtolower($appointment['patient_name'])); ?></td>
                        <td><?php echo ucfirst(strtolower($appointment['doctor_fname'])) . " " . ucfirst(strtolower($appointment['doctor_lname'])); ?></td>
                        <td><?php echo ucfirst(strtolower($appointment['service_name'])); ?></td>
                        <td><?php echo number_format($appointment['payment_amount'], 2); ?></td>
                        <td><?php echo ucfirst(strtolower($appointment['status'])); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Analytics Section -->
        <h3>Analytics</h3>
        <ul class="analytics ">
            <li><strong>Total Amount:</strong> <?php echo $reportData['analytics']['totalAmount']; ?></li>
            <li><strong>Average Payment:</strong> <?php echo number_format($reportData['analytics']['averagePayment'], 2); ?></li>
            <li><strong>Total Appointments:</strong> <?php echo $reportData['analytics']['totalAppointments']; ?></li>
            <li><strong>Appointment Statuses:</strong>
                <ul>
                    <li>Pending: <?php echo $reportData['analytics']['appointmentStatuses']['Pending']; ?></li>
                    <li>Completed: <?php echo $reportData['analytics']['appointmentStatuses']['Completed']; ?></li>
                    <li>Cancelled: <?php echo $reportData['analytics']['appointmentStatuses']['Cancelled']; ?></li>
                </ul>
            </li>
            <li><strong>Payment Statuses:</strong>
                <ul>
                    <li>Pending: <?php echo $reportData['analytics']['paymentStatuses']['Pending']; ?></li>
                    <li>Approved: <?php echo $reportData['analytics']['paymentStatuses']['Approved']; ?></li>
                    <li>Disapproved: <?php echo $reportData['analytics']['paymentStatuses']['Disapproved']; ?></li>
                </ul>
            </li>
        </ul>
        <div class="d-flex justify-content-end">
            <button class="btn btn-success" onclick="window.print()">Print Report</button>
        </div>
    </div>
<?php } ?>