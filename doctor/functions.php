<?php

function getPaymentMode($pdo, $paymentModeId)
{
    try {
        $query = $pdo->prepare("SELECT method FROM payment_mode WHERE id = :id");
        $query->bindParam(':id', $paymentModeId, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        // Check if a result was returned
        if ($result && isset($result['method'])) {
            return $result['method'];
        } else {
            return 'Unknown Payment Method'; // Default message if no result is found
        }
    } catch (PDOException $e) {
        // Handle any database errors
        return 'Error retrieving payment method';
    }
}



function displayTable($pdo, $table, $columns, $displayImageColumns = [], $includeActions = true, $imagePathPrefix = '', $includeArchived = false, $actions = [])
{
    // Check if the table has 'created_at' and 'is_archive' columns
    $query = $pdo->prepare("DESCRIBE $table");
    $query->execute();
    $columnsInfo = $query->fetchAll(PDO::FETCH_COLUMN);

    // Determine the ORDER BY clause based on 'created_at' column presence
    $orderBy = in_array('created_at', $columnsInfo) ? "ORDER BY created_at DESC" : "";

    // Handle the WHERE clause based on the presence of 'is_archive' column
    $whereClause = "";
    if (in_array('is_archive', $columnsInfo)) {
        $whereClause = $includeArchived ? "WHERE is_archive = 1" : "WHERE is_archive = 0";
    }

    // Fetch data from the specified table
    $query = $pdo->prepare("SELECT * FROM $table $whereClause $orderBy");
    $query->execute();

    // Start the table
    echo '<div class="search-box ms-2 mt-3 mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search for ' . htmlspecialchars($table) . '..." onkeyup="searchTable()">
            <i class="ri-search-line search-icon"></i>
          </div>';

    echo '<table id="' . htmlspecialchars($table) . 'Table" class="table table-bordered table-striped">';
    echo '<thead><tr>';

    // Generate table headers dynamically
    foreach ($columns as $column) {
        $header = ucfirst(str_replace('_', ' ', $column));
        echo "<th>" . htmlspecialchars($header) . "</th>";
    }

    // Add Options column if required
    if ($includeActions) {
        echo '<th style="width: 20%;">Options</th>';
    }
    echo '</tr></thead><tbody>';

    // Fetch and display table rows
    if ($query->rowCount() > 0) {
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            foreach ($columns as $column) {
                if (in_array($column, $displayImageColumns)) {
                    // echo '<td><a href="' . htmlspecialchars($imagePathPrefix . $row[$column]) . '" data-lightbox="gallery" data-lightbox="image-' . htmlspecialchars($row['id']) . '">
                    //         <img src="' . htmlspecialchars($imagePathPrefix . $row[$column]) . '" alt="Image" style="width: 100px; height: auto;">
                    //       </a></td>';
                    echo '<td>
                    <a href="' . htmlspecialchars($imagePathPrefix . $row[$column]) . '" 
                        data-lightbox="gallery" 
                        data-lightbox="image-' . htmlspecialchars($row['id']) . '">
                        Click to view image
                        </a>
                    </td>';
                } else {
                    echo '<td>' . htmlspecialchars($row[$column]) . '</td>';
                }
            }
            if ($includeActions) {
                echo '<td><div style="display: flex; gap: 5px;">';
                foreach ($actions as $action) {
                    handleActionButton($pdo, $action, $row, $table);
                }
                echo '</div></td>';
            }
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="' . (count($columns) + ($includeActions ? 1 : 0)) . '">No data available.</td></tr>';
    }

    echo '</tbody></table>';

    // Include DataTable functionality
    echo '<script>
            $(document).ready(function() {
                $("#' . htmlspecialchars($table) . 'Table").DataTable({
                    "aoColumnDefs": [{
                        "bSortable": false,
                        "aTargets": [' . count($columns) . '] // Disable sorting for the options column
                    }],
                    "aaSorting": []
                });
            });
          </script>';

    // JavaScript for table search functionality
    echo '<script>
            function searchTable() {
                const input = document.getElementById("searchInput");
                const filter = input.value.toUpperCase();
                const table = document.getElementById("' . htmlspecialchars($table) . 'Table");
                const tr = table.getElementsByTagName("tr");

                for (let i = 0; i < tr.length; i++) {
                    const td = tr[i].getElementsByTagName("td");
                    if (td.length > 0) {
                        let showRow = false;
                        for (let j = 0; j < td.length; j++) {
                            const txtValue = td[j].textContent || td[j].innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                showRow = true;
                                break;
                            }
                        }
                        tr[i].style.display = showRow ? "" : "none";
                    }
                }
            }
          </script>';
}

function handleActionButton($pdo, $action, $row, $table)
{
    switch ($action) {
        case 'unarchive':
            echo '<form method="POST" action="unarchive.php">
                    <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">
                    <input type="hidden" name="table" value="' . htmlspecialchars($table) . '">
                    <button class="btn btn-primary btn-sm" type="submit" name="unarchive" value="1">Unarchive</button>
                  </form>';
            break;
        case 'archive':
            echo '<form method="POST" action="archive.php">
                    <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '" />
                    <input type="hidden" name="table" value="' . htmlspecialchars($table) . '" />
                    <button type="submit" name="archive" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to archive this item?\')">
                        <i class="fa fa-trash"></i> Archive
                    </button>
                  </form>';
            break;
        case 'delete':
            echo '<form method="POST" action="delete.php">
                    <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '" />
                    <input type="hidden" name="table" value="' . htmlspecialchars($table) . '" />
                    <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this item?\')">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                  </form>';
            break;
        case 'edit_payment_receipt':
            echo '<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#testModal"
                        data-id="' . htmlspecialchars($row['id']) . '"
                        data-status="' . htmlspecialchars($row['status']) . '">
                        Edit
                    </button>';
            echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        $("#testModal").on("show.bs.modal", function(event) {
                            const button = $(event.relatedTarget);
                            const id = button.data("id");
                            const status = button.data("status");
                            const modal = $(this);
                            modal.find("#id").val(id);
                            modal.find("#status").val(status);
                        });
                    });
                  </script>';
            editFormPayments($pdo);
            break;
    }
}

function editFormPayments($pdo)
{
    // Modal for editing payment
?><div class="modal fade" id="testModal" tabindex="-1" aria-labelledby="testModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testModalLabel">Edit Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="action.php">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="status" class="mt-2">Status</label>
                            <select name="status" id="status" class="form-control w-50" style="margin-left: -30%;">
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Disapproved">Disapproved</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_payment" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
<?php
}

// Week View Calendar for Doctor
function calendarWeekShowsDoctor()
{
    require 'db.php'; // Include the database connection

    // Check if user_id (doctor ID) is set in the session
    if (!isset($_SESSION['user_id'])) {
        echo "User not authenticated.";
        exit;
    }

    try {
        $doctor_id = $_SESSION['user_id']; // Retrieve the logged-in doctor's ID from the session

        // Fetch appointments for the logged-in doctor
        $query = "SELECT 
    a.id, 
    a.appointment_date, 
    a.appointment_time, 
    s.service, 
    p.firstname AS patient_firstname, 
    p.lastname AS patient_lastname, 
    d.firstname AS doctor_firstname, 
    d.lastname AS doctor_lastname, 
    dept.name AS department_name, 
    a.status AS appointment_status, 
    mr.status AS medical_record_status
FROM 
    appointment a
LEFT JOIN 
    services s ON a.service_id = s.id
LEFT JOIN 
    patient p ON a.patient_id = p.id
LEFT JOIN 
    doctor d ON a.doctor_id = d.employee_id
LEFT JOIN 
    departments dept ON a.department_id = dept.id
LEFT JOIN 
    medical_records mr ON a.id = mr.appointment_id
WHERE 
    a.is_archive = 0 
    AND a.doctor_id = :doctor_id;"; // Filter by doctor ID

        $stmt = $pdo->prepare($query);
        $stmt->execute(['doctor_id' => $doctor_id]); // Bind the doctor ID
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }

    // Prepare data for FullCalendar
    $events = [];
    foreach ($appointments as $appointment) {
        $start_time = $appointment['appointment_date'] . 'T' . $appointment['appointment_time'];
        $end_time = date('Y-m-d\TH:i:s', strtotime($start_time) + 3600); // Assuming 1-hour duration

        $events[] = [
            'title' => $appointment['service'] . ' - ' . $appointment['patient_firstname'] . ' ' . $appointment['patient_lastname'],
            'start' => $start_time, // FullCalendar expects 'start' as a full date-time string
            'end' => $end_time,     // End time, assuming 1 hour duration
            'extendedProps' => [
                'service' => $appointment['service'],
                'patient_name' => $appointment['patient_firstname'] . ' ' . $appointment['patient_lastname'],
                'doctor_name' => $appointment['doctor_firstname'] . ' ' . $appointment['doctor_lastname'],
                'department_name' => $appointment['department_name'],
                'status' => $appointment['medical_record_status'],
                'appointment_time' => $appointment['appointment_date'] . ' ' . $appointment['appointment_time']
            ]
        ];
    }

?>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendarWeek');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'listWeek', // Displaying the calendar in a weekly list view
                events: <?php echo json_encode($events); ?>, // Pass PHP events to JavaScript

                eventClick: function(info) {
                    // Show appointment details in a modal
                    var appointment = info.event.extendedProps;
                    var modalContent = ` 
                        <p><strong>Service:</strong> ${appointment.service}</p>
                        <p><strong>Patient:</strong> ${appointment.patient_name}</p>
                        <p><strong>Assigned Doctor:</strong> ${appointment.doctor_name}</p>
                        <p><strong>Department:</strong> ${appointment.department_name}</p>
                        <p><strong>Status:</strong> ${appointment.status}</p>
                        <p><strong>Appointment Time:</strong> ${new Date(info.event.start).toLocaleString()}</p>
                    `;
                    document.getElementById('appointmentModalBodyWeek').innerHTML = modalContent;
                    var myModal = new bootstrap.Modal(document.getElementById('appointmentModalWeek'));
                    myModal.show();
                },

                eventContent: function(arg) {
                    // Modify event rendering to show only time and service name
                    var time = document.createElement('div');
                    time.style.paddingLeft = "5px";
                    time.style.fontWeight = 'bold';
                    time.style.fontSize = '0.9em';
                    time.innerText = new Date(arg.event.start).toLocaleTimeString(); // Display time

                    var service = document.createElement('div');
                    service.style.fontSize = '0.9em';
                    service.style.fontWeight = 'bold';
                    service.innerText = arg.event.extendedProps.service; // Service name from extendedProps

                    return {
                        domNodes: [service, time]
                    };
                },

                eventDidMount: function(info) {
                    // Assign colors based on status directly
                    var status = info.event.extendedProps.status;
                    if (status === 'Completed') {
                        info.el.style.backgroundColor = '#8FD14F';
                    } else if (status === 'Pending') {
                        var appointmentTime = new Date(info.event.start);
                        var now = new Date();

                        if (appointmentTime < now) {
                            info.el.style.backgroundColor = '#FF6600'; // Expired
                        } else {
                            info.el.style.backgroundColor = 'blue'; // Pending and not expired
                        }
                    } else if (status === 'Cancelled') {
                        info.el.style.backgroundColor = 'gray';
                    }
                }
            });
            calendar.render();
        });
    </script>

    <div id="calendarWeek"></div>

    <!-- Modal HTML structure for week view -->
    <div id="appointmentModalWeek" class="modal" tabindex="-1" aria-labelledby="appointmentModalLabelWeek" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabelWeek">Appointment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="appointmentModalBodyWeek">
                    <!-- Appointment details will be injected here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<?php
}


// Month View Calendar for Doctor
function calendarMonthShowsDoctor()
{
?>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .fc-event-title {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

    <div id="calendar"></div>

    <!-- Modal HTML structure -->
    <div id="appointmentModal" class="modal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Appointment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Service:</strong> <span id="serviceName"></span></p>
                    <p><strong>Patient:</strong> <span id="patientId"></span></p>
                    <p><strong>Assigned Doctor:</strong> <span id="doctorId"></span></p>
                    <p><strong>Status:</strong> <span id="appointmentStatus"></span></p>
                    <p><strong>Appointment Time:</strong> <span id="appointmentTime"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                events: function(info, successCallback, failureCallback) {
                    fetch('fetch_appointments.php') // Endpoint to fetch event data
                        .then(response => response.json())
                        .then(data => {
                            successCallback(data);
                        })
                        .catch(error => {
                            failureCallback(error);
                        });
                },
                eventClick: function(info) {
                    // Access custom data from extendedProps
                    var appointment = info.event.extendedProps;

                    // Check if values exist, use fallback if they don't
                    var patientName = appointment.patient_name ? appointment.patient_name.replace(/\b\w/g, char => char.toUpperCase()) : "N/A";
                    var serviceName = appointment.service_name ? appointment.service_name : "N/A";
                    var doctorName = appointment.doctor_name ? appointment.doctor_name.replace(/\b\w/g, char => char.toUpperCase()) : "N/A";
                    var appointmentStatus = appointment.status ? appointment.status : "N/A";

                    // Update modal with appointment details
                    document.getElementById('serviceName').innerText = serviceName;
                    document.getElementById('patientId').innerText = patientName;
                    document.getElementById('doctorId').innerText = doctorName;
                    document.getElementById('appointmentStatus').innerText = appointmentStatus;
                    document.getElementById('appointmentTime').innerText = new Date(info.event.start).toLocaleString();

                    // Show the modal
                    var myModal = new bootstrap.Modal(document.getElementById('appointmentModal'));
                    myModal.show();
                },
                eventDidMount: function(info) {
                    // Assign colors based on status directly
                    var status = info.event.extendedProps.status;
                    if (status === 'Completed') {
                        info.el.style.backgroundColor = '#8FD14F'; // Green for Completed
                    } else if (status === 'Pending') {
                        var appointmentTime = new Date(info.event.start);
                        var now = new Date();

                        if (appointmentTime < now) {
                            info.el.style.backgroundColor = '#FF6600'; // Red for expired Pending
                        } else {
                            info.el.style.backgroundColor = 'blue'; // Blue for Pending and not expired
                        }
                    } else if (status === 'Cancelled') {
                        info.el.style.backgroundColor = 'gray'; // Gray for Cancelled
                    }
                }
            });

            calendar.render();
        });
    </script>
<?php }


function getTotalSalesByDoctor($doctorId)
{
    include "db.php"; // Include the database connection
    try {
        $query = "
            SELECT 
                SUM(pr.amount) AS total_sales
            FROM 
                appointment a
            INNER JOIN 
                payment_receipts pr ON a.id = pr.appointment_id
            WHERE 
                a.doctor_id = :doctorId AND 
                a.is_archive = 0 AND 
                pr.status = 'Approved'
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':doctorId', $doctorId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? (float)$result['total_sales'] : 0.0;
    } catch (PDOException $e) {
        // Handle error
        echo "Error: " . $e->getMessage();
        return 0.0;
    }
}

function getTotalPatientsByDoctor($doctorId)
{
    include "db.php"; // Include the database connection
    try {
        $query = "
            SELECT 
                COUNT(a.patient_id) AS total_patients
            FROM 
                appointment a
            WHERE 
                a.doctor_id = :doctorId AND 
                a.is_archive = 0
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':doctorId', $doctorId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? (int)$result['total_patients'] : 0;
    } catch (PDOException $e) {
        // Handle error
        echo "Error: " . $e->getMessage();
        return 0;
    }
}

function getTotalAppointmentsByDoctor($doctorId)
{
    include "db.php"; // Include the database connection
    try {
        $query = "
            SELECT 
                COUNT(a.id) AS total_appointments
            FROM 
                appointment a
            WHERE 
                a.doctor_id = :doctorId AND 
                a.is_archive = 0
                
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':doctorId', $doctorId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? (int)$result['total_appointments'] : 0;
    } catch (PDOException $e) {
        // Handle error
        echo "Error: " . $e->getMessage();
        return 0;
    }
}

function getDoctor($pdo, $doctorId)
{
    try {
        $query = $pdo->prepare("SELECT * FROM doctor WHERE employee_id = :doctorId");
        $query->bindParam(':doctorId', $doctorId, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        // Handle any database errors
        return null;
    }
}

// Function to get total sales
function getTotalSales($pdo)
{
    $sql = "SELECT SUM(amount) AS total_sales FROM payment_receipts";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_sales'] ? $result['total_sales'] : 0; // Return 0 if no sales
}

// Function to get total number of patients
function getTotalPatients($pdo)
{
    $sql = "SELECT COUNT(*) AS total_patients FROM patient";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_patients'];
}

// Function to get total number of appointments
function getTotalAppointments($pdo)
{
    $sql = "SELECT COUNT(*) AS total_appointments FROM appointment";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_appointments'];
}

function getTotalPendingAppointments($pdo)
{
    $sql = "SELECT COUNT(*) AS total_pending_appointments FROM appointment WHERE status = 'Pending'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_pending_appointments'];
}

function getCompletedAppointments($pdo){
    $sql = "SELECT COUNT(*) AS total_completed_appointments FROM appointment WHERE status = 'Completed'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_completed_appointments'];
}

function getTotalAppointmentSlots($pdo)
{
    $sql = "SELECT COUNT(*) AS total_slots FROM appointment_slots";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_slots'];
}
function getTotalDoctors($pdo)
{
    $sql = "SELECT COUNT(*) AS total_doctors FROM doctor";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_doctors'];
}
