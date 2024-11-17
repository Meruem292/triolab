<?php
require "db.php";
require "../admin/modals/functions.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}

$admin_id = $_SESSION['user_id'];


?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="green">

<head>
    <meta charset="utf-8" />
    <title>Triolab - Online Healthcare Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="assets/images/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/css/sweetalert.css">

    <script>
        // Prevents reloading on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</head>

<body style="background-color: #F2FFF1">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- HEADER -->
        <?php require "header.php"; ?>

        <!-- SIDEBAR -->
        <?php require "sidebar.php" ?>
        <div class="vertical-overlay"></div>

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                <h4 class="mb-sm-0">Medical Records</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Medical Records</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-body">

                                    <div class="d-flex justify-content-sm-start">
                                        <div class="search-box ms-2 mt-3 mb-3">
                                            <input type="text" id="searchInput" class="form-control" placeholder="Search for Medical Records..." onkeyup="searchTable()">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>

                                    <table class="table align-middle table-nowrap" id="patientTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Contact</th>
                                                <th>Email</th>
                                                <th>Address</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            <?php
                                            // Fetch patients where the patient is not archived
                                            $selectPatient = $pdo->prepare("
                                                SELECT * 
                                                FROM patient 
                                                ORDER BY firstname ASC
                                            ");
                                            $selectPatient->execute();

                                            if ($selectPatient->rowCount() > 0) {
                                                while ($row = $selectPatient->fetch(PDO::FETCH_ASSOC)) {
                                                    $fullname = htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']);

                                            ?>
                                                    <tr>
                                                        <td><?= $fullname; ?></td>
                                                        <td><?= htmlspecialchars($row['contact']); ?></td>
                                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                                        <td><?= htmlspecialchars($row['province'] . ', ' . $row['city'] . ', ' . $row['barangay'] . ', ' . $row['street']); ?></td>

                                                        <td>
                                                            <div class="dropdown d-inline-block">
                                                                <button class="btn btn-soft-success btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="ri-more-fill align-middle"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li>
                                                                        <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addMedicalRecordModal" data-patient-id="<?= htmlspecialchars($row['id']); ?>">
                                                                            <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Add Medical Record
                                                                        </a>
                                                                    </li>

                                                                    <li>
                                                                        <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewMedicalRecordModal" data-patient-id="<?= htmlspecialchars($row['id']); ?>">
                                                                            <i class="ri-eye-fill align-bottom me-2 text-muted"></i> View Medical Record
                                                                        </a>
                                                                    </li>
                                                                    <!-- Add other actions here if necessary -->
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="7">
                                                        <div class="noresult">
                                                            <div class="text-center">
                                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                                                <p class="text-muted mb-0">We've searched in our database but did not find any data yet!</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                </div><!-- end card-body -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <!-- FOOTER -->
            <?php require "footer.php"; ?>
        </div>
    </div>


    <!-- Modals -->
    <?php include "../admin/modals/add_medical_record.php"; ?>
    <?php include "../admin/modals/view_medical_record.php"; ?>


    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>

    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Modern colorpicker bundle -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="assets/js/pages/listjs.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <script src="assets/js/sweetalert.js"></script>

    <script>
        flatpickr("#datePicker");
    </script>

    <script>
        // JavaScript to set the patient_id in the modal
        document.addEventListener('DOMContentLoaded', function() {
            // Listen for the 'show.bs.modal' event (this is triggered when the modal is shown)
            var addMedicalRecordModal = document.getElementById('addMedicalRecordModal');
            addMedicalRecordModal.addEventListener('show.bs.modal', function(event) {
                // Get the button that triggered the modal
                var button = event.relatedTarget;

                // Extract the patient_id from the data attribute
                var patientId = button.getAttribute('data-patient-id');

                // Find the hidden input field in the modal and set its value
                var patientIdInput = document.getElementById('patient_id');
                patientIdInput.value = patientId;
            });
        });
    </script>

    <script>
        // When the View Medical Record button is clicked, populate the modal with data
        const viewRecordButtons = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#viewMedicalRecordModal"]');

        viewRecordButtons.forEach(button => {
            button.addEventListener('click', function() {
                const patientId = this.getAttribute('data-patient-id');

                // Log the patient ID for debugging
                console.log("Patient ID:", patientId);

                if (!patientId) {
                    console.error("No patient ID found.");
                    return; // Stop further execution if no patient ID
                }

                fetch('../admin/fetch_medical_records.php?patient_id=' + patientId)
                    .then(response => {
                        console.log("Response status:", response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log("Fetched data:", data); // Log the data for debugging
                        const tableBody = document.getElementById('medicalRecordTableBody');
                        tableBody.innerHTML = ''; // Clear the previous table contents

                        // Loop through the records and populate the table
                        if (data.records && data.records.length > 0) {
                            data.records.forEach(record => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                            <td>${record.record_date}</td>
                            <td>${record.diagnosis}</td>
                            <td>${record.treatment}</td>
                        `;
                                tableBody.appendChild(row);
                            });
                        } else {
                            tableBody.innerHTML = '<tr><td colspan="3" class="text-center">No records found for this patient.</td></tr>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching medical records:', error);
                    });
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
    </script>
    <?php if (isset($_SESSION['message']) && isset($_SESSION['status'])) { ?>
        <script>
            Swal.fire({
                text: "<?php echo $_SESSION['message']; ?>",
                icon: "<?php echo $_SESSION['status']; ?>",
            });
        </script>
    <?php
        unset($_SESSION['message']);
        unset($_SESSION['status']);
    } ?>

</body>

</html>