<?php

require "db.php";
session_start();

$admin_id = $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
};

if (isset($_POST['add_slot'])) {
    $schedule = $_POST['schedule'];
    $date = $_POST['date'];
    $slot = $_POST['slot'];


    $checkQuery = $pdo->prepare("SELECT * FROM appointment_slots WHERE date = :date AND schedule = :schedule");
    $checkQuery->bindParam(':date', $date);
    $checkQuery->bindParam(':schedule', $schedule);
    $checkQuery->execute();

    if ($checkQuery->rowCount() > 0) {
        // Entry already exists
        $_SESSION['message'] = "Appointment slot already exists in the database.";
        $_SESSION['status'] = "warning";
    } else {
        // Insert the new doctor information into the database
        $insertQuery = $pdo->prepare("INSERT INTO appointment_slots (schedule, date, slot) VALUES (:schedule, :date, :slot)");
        $insertQuery->bindParam(':schedule', $schedule);
        $insertQuery->bindParam(':date', $date);
        $insertQuery->bindParam(':slot', $slot);

        if ($insertQuery->execute()) {
            $_SESSION['message'] = "Appointment slot added successfully.";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Error inserting appointment slot.";
            $_SESSION['status'] = "error";
        }
    }
};

if (isset($_POST['edit_slot'])) {
    $editSlotId = $_POST['editSlotId'];
    $editSlotSchedule = $_POST['editSlotSchedule'];
    $editSlotDate = $_POST['editSlotDate'];
    $editSlotsSlot = $_POST['editSlotsSlot'];

    $updateQuery = $pdo->prepare("UPDATE appointment_slots SET schedule = :editSlotSchedule, date = :editSlotDate, slot = :editSlotsSlot WHERE id = :editSlotId");
    $updateQuery->bindParam(':editSlotSchedule', $editSlotSchedule);
    $updateQuery->bindParam(':editSlotDate', $editSlotDate);
    $updateQuery->bindParam(':editSlotsSlot', $editSlotsSlot);
    $updateQuery->bindParam(':editSlotId', $editSlotId);

    if ($updateQuery->execute()) {
        $_SESSION['message'] = "Appointment slots updated successfully.";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Error updating appointment slots.";
        $_SESSION['status'] = "error";
    }
};

if (isset($_POST['archive_slot'])) {
    $slotIdDelete = $_POST['slotIdDelete'];
    $archive = '1';

    // Update the service information in the database
    $archiveQuery = $pdo->prepare("UPDATE appointment_slots SET is_archive = :archive WHERE id = :slotIdDelete");
    $archiveQuery->bindParam(':archive', $archive);
    $archiveQuery->bindParam(':slotIdDelete', $slotIdDelete);

    if ($archiveQuery->execute()) {
        $_SESSION['message'] = "Appointment slots archived successfully.";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Error archiving appointment slots.";
        $_SESSION['status'] = "error";
    }
}

?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="green">

<head>

    <meta charset="utf-8" />
    <title>Triolab - Online Healthcare Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
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
                                <h4 class="mb-sm-0">Slots</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Slots</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSlot">+ New Slot</button>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="d-flex justify-content-sm-start">
                                            <div class="search-box ms-2">
                                                <input type="text" class="form-control search" placeholder="Search...">
                                                <i class="ri-search-line search-icon"></i>
                                            </div>
                                        </div>
                                        <div class="app-search d-none d-md-block">
                                            <div class="position-relative">
                                                <input id="datePicker" type="text" class="form-control" style="border: 1px solid #777;" placeholder="Filter by date" autocomplete="off">
                                                <span class="mdi mdi-calendar search-widget-icon"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="listjs-table" id="customerList">
                                        <div class="table-responsive table-card mt-3 mb-1">
                                            <table class="table align-middle table-nowrap" id="customerTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Schedule</th>
                                                        <th>Date</th>
                                                        <th>Slot</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="list">
                                                    <?php
                                                    $selectSlot = $pdo->query("SELECT * FROM appointment_slots WHERE is_archive = 0");
                                                    if ($selectSlot->rowCount() > 0) {
                                                        while ($row = $selectSlot->fetch(PDO::FETCH_ASSOC)) {
                                                            $dateString = $row['date'];
                                                            $formattedDate = date("F j, Y \a\\t g:i a", strtotime($dateString));
                                                    ?>
                                                            <tr>
                                                                <td><?= $row['schedule']; ?></td>
                                                                <td><?= $formattedDate; ?></td>
                                                                <td><?= $row['slot']; ?></td>
                                                                <td>
                                                                    <div class="dropdown d-inline-block">
                                                                        <button class="btn btn-soft-success btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <i class="ri-more-fill align-middle"></i>
                                                                        </button>
                                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                                            <li>
                                                                                <a href="#" class="dropdown-item remove-item-btn edit-btn" data-bs-toggle="modal" data-bs-target="#editSlot" data-slots-id="<?= $row['id'] ?>" data-slots-schedule="<?= $row['schedule'] ?>" data-slots-date="<?= $row['date'] ?>" data-slots-slot="<?= $row['slot'] ?>">
                                                                                    <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="#" class="dropdown-item remove-item-btn archive-btn" data-bs-toggle="modal" data-bs-target="#archiveSlot" data-slots-id="<?= $row['id'] ?>">
                                                                                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Archive
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="6">
                                                                <div class="noresult">
                                                                    <div class="text-center">
                                                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                                                        <h5 class="mt-2">Sorry! No Result Found</h5>
                                                                        <p class="text-muted mb-0">We've searched in our database but we did not find any data yet!</p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <div class="pagination-wrap hstack gap-2" style="display: flex;">
                                                <a class="page-item pagination-prev disabled" href="javascript:void(0);">
                                                    Previous
                                                </a>
                                                <ul class="pagination listjs-pagination mb-0">
                                                    <li clas="active"><a class="btn btn-primary btn-sm" href="#">1</a></li>
                                                    <li clas=""><a class="btn btn-sm" style="border: 1px solid #e9ebec;" href="#">2</a></li>
                                                    <li clas=""><a class="btn btn-sm" style="border: 1px solid #e9ebec;" href="#">3</a></li>
                                                </ul>
                                                <a class="page-item pagination-next" href="javascript:void(0);">
                                                    Next
                                                </a>
                                            </div>
                                        </div>
                                    </div>
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

    <!-- ADD SLOT -->
    <div id="addSlot" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Slot Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 mb-3">
                            <label class="form-label">Schedule <span class="text-danger">*</span></label>
                            <select name="schedule" class="form-select" required>
                                <option value="Morning" selected>Morning</option>
                                <option value="Afternoon">Afternoon</option>
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date" required>
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <label class="form-label">Slot <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="slot" required placeholder="Enter how many slot">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_slot" class="btn btn-primary ">Add Slot</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit SLOT -->
    <div id="editSlot" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Slot Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="editSlotId" id="slotsId">
                        <div class="col-md-12 col-sm-12 mb-3">
                            <label class="form-label">Schedule <span class="text-danger">*</span></label>
                            <select name="editSlotSchedule" id="slotsSchedule" class="form-select" required>
                                <option value="Morning" selected>Morning</option>
                                <option value="Afternoon">Afternoon</option>
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" id="slotsDate" class="form-control" name="editSlotDate" required>
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <label class="form-label">Slot <span class="text-danger">*</span></label>
                            <input type="text" id="slotsSlot" class="form-control" name="editSlotsSlot" required placeholder="Enter how many slot">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="edit_slot" class="btn btn-primary ">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ARCHIVE SLOT -->
    <div id="archiveSlot" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Archive Slot Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="slotIdDelete" name="slotIdDelete">
                    <p>Are you sure you want to archive this appoimtent slot?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="archive_slot" class="btn btn-danger">Archive</button>
                </div>
            </form>
        </div>
    </div>

    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!-- JAVASCRIPT -->
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
    <script src="assets/js/sweetalert.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <script>
        flatpickr("#datePicker");
    </script>

    <script>
        // JavaScript to handle populating data in the edit modal
        var editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var slotsId = this.getAttribute('data-slots-id');
                var slotsSchedule = this.getAttribute('data-slots-schedule');
                var slotsDate = this.getAttribute('data-slots-date');
                var slotsSlot = this.getAttribute('data-slots-slot');

                document.getElementById('slotsId').value = slotsId;
                document.getElementById('slotsSchedule').value = slotsSchedule;
                document.getElementById('slotsDate').value = slotsDate;
                document.getElementById('slotsSlot').value = slotsSlot;
            });
        });
    </script>

    <script>
        // JavaScript to handle populating data in the edit modal
        var editButtons = document.querySelectorAll('.archive-btn');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Get the product details from data attributes
                var slotId = this.getAttribute('data-slots-id');

                // Set the product details in the modal form
                document.getElementById('slotIdDelete').value = slotId;
            });
        });
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