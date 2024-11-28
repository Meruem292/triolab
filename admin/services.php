<?php

require "db.php";
session_start();

$admin_id = $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
};

if (isset($_POST['add_service'])) {
    $category = $_POST['category'];
    $type = $_POST['type'];
    $service = $_POST['service'];
    $department = $_POST['department'];
    $cost = $_POST['cost'];

    $checkQuery = $pdo->prepare("SELECT * FROM services WHERE type = :type AND service = :service AND category = :category");
    $checkQuery->bindParam(':type', $type);
    $checkQuery->bindParam(':service', $service);
    $checkQuery->bindParam(':category', $category);
    $checkQuery->execute();

    if ($checkQuery->rowCount() > 0) {
        // Entry already exists
        $_SESSION['message'] = "Service already exists in the database.";
        $_SESSION['status'] = "warning";
    } else {
        // Insert the new service information into the database
        $insertQuery = $pdo->prepare("INSERT INTO services (category, type, service, department_id, cost) VALUES (:category, :type, :service, :department, :cost)");
        $insertQuery->bindParam(':category', $category);
        $insertQuery->bindParam(':type', $type);
        $insertQuery->bindParam(':service', $service);
        $insertQuery->bindParam(':department', $department);
        $insertQuery->bindParam(':cost', $cost);

        if ($insertQuery->execute()) {
            $_SESSION['message'] = "Service added successfully.";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Error inserting service.";
            $_SESSION['status'] = "error";
        }
    }
};

if (isset($_POST['edit_service'])) {
    // Include your database connection file

    // Prepare data for update
    $editServicesId = $_POST['editServicesId'];
    $editServiceCategory = $_POST['editServiceCategory'];
    $editServicesType = $_POST['editServicesType'];
    $editServicesService = $_POST['editServicesService'];
    $editServicesDepartment = $_POST['editServicesDepartment'];
    $editServicesCost = $_POST['editServicesCost'];

    // Update the service information in the database
    $updateQuery = $pdo->prepare("UPDATE services SET category = :category, type = :type, service = :service, department_id= :department_id, cost = :cost WHERE id = :id");
    $updateQuery->bindParam(':category', $editServiceCategory);
    $updateQuery->bindParam(':type', $editServicesType);
    $updateQuery->bindParam(':service', $editServicesService);
    $updateQuery->bindParam(':department_id', $editServicesDepartment);
    $updateQuery->bindParam(':cost', $editServicesCost);
    $updateQuery->bindParam(':id', $editServicesId);

    if ($updateQuery->execute()) {
        $_SESSION['message'] = "Service updated successfully.";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Error updating service.";
        $_SESSION['status'] = "error";
    }
};

if (isset($_POST['archive_service'])) {
    $servicesIdDelete = $_POST['servicesIdDelete'];
    $archive = '1';

    // Update the service information in the database
    $archiveQuery = $pdo->prepare("UPDATE services SET is_archive = :archive WHERE id = :id");
    $archiveQuery->bindParam(':archive', $archive);
    $archiveQuery->bindParam(':id', $servicesIdDelete);

    if ($archiveQuery->execute()) {
        $_SESSION['message'] = "Service archived successfully.";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Error archiving service.";
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
                                <h4 class="mb-sm-0">Services</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Services</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-body">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addService">+ New Service</button>
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified mb-3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#laboratory" role="tab">
                                                LABORATORY SERVICES
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#imaging" role="tab">
                                                IMAGING SERVICES
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#general" role="tab">
                                                GENERAL SERVICES
                                            </a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content text-muted">
                                        <div class="tab-pane active" id="laboratory" role="tabpanel">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="d-flex justify-content-sm-start">
                                                    <div class="search-box ms-2 mt-3 mb-3">
                                                        <input type="text" id="searchInput" class="form-control" placeholder="Search for patients..." onkeyup="searchTable()">
                                                        <i class="ri-search-line search-icon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="listjs-table" id="customerList">
                                                <div class="table-responsive table-card mt-3 mb-1">
                                                    <table class="table align-middle table-nowrap" id="customerTable">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Category</th>
                                                                <th>Type</th>
                                                                <th>Service</th>
                                                                <th>Department</th>
                                                                <th>Cost</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="list">
                                                            <?php
                                                            $selectlaboratory = $pdo->query("
    SELECT s.*, d.name AS department_name 
    FROM services s
    LEFT JOIN departments d ON s.department_id = d.id
    WHERE s.category = 'Laboratory Services' AND s.is_archive = 0
    ORDER BY s.service ASC
");

                                                            if ($selectlaboratory->rowCount() > 0) {
                                                                while ($row = $selectlaboratory->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                                    <tr>
                                                                        <td><?= $row['category']; ?></td>
                                                                        <td><?= $row['type']; ?></td>
                                                                        <td><?= $row['service']; ?></td>
                                                                        <td><?= htmlspecialchars($row['department_name'] ?? 'N/A'); ?></td>
                                                                        <td>₱<?= number_format($row['cost'], 2); ?></td>
                                                                        <td>
                                                                            <div class="dropdown d-inline-block">
                                                                                <button class="btn btn-soft-success btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <i class="ri-more-fill align-middle"></i>
                                                                                </button>
                                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item remove-item-btn edit-btn" data-bs-toggle="modal" data-bs-target="#editService" data-services-id="<?= $row['id'] ?>" data-services-category="<?= $row['category'] ?>" data-services-type="<?= $row['type'] ?>" data-services-service="<?= $row['service'] ?>" data-services-department="<?= htmlspecialchars($row['department_name'] ?? '') ?>" data-services-cost="<?= $row['cost'] ?>">
                                                                                            <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item remove-item-btn archive-btn" data-bs-toggle="modal" data-bs-target="#archiveService" data-services-id="<?= $row['id'] ?>">
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
                                        </div>
                                        <div class="tab-pane" id="imaging" role="tabpanel">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="d-flex justify-content-sm-start">
                                                    <div class="search-box ms-2">
                                                        <input type="text" class="form-control search" placeholder="Search...">
                                                        <i class="ri-search-line search-icon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="listjs-table" id="customerList">
                                                <div class="table-responsive table-card mt-3 mb-1">
                                                    <table class="table align-middle table-nowrap" id="customerTable">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Category</th>
                                                                <th>Type</th>
                                                                <th>Service</th>
                                                                <th>Department</th>
                                                                <th>Cost</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="list">
                                                            <?php
                                                            // Fetch services and their associated department names
                                                            $selectlaboratory = $pdo->query("
        SELECT s.*, d.name AS department_name 
        FROM services s
        LEFT JOIN departments d ON s.department_id = d.id
        WHERE s.category = 'Imaging Services' AND s.is_archive = 0
        ORDER BY s.service ASC
    ");

                                                            if ($selectlaboratory->rowCount() > 0) {
                                                                while ($row = $selectlaboratory->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                                    <tr>
                                                                        <td><?= $row['category']; ?></td>
                                                                        <td><?= $row['type']; ?></td>
                                                                        <td><?= $row['service']; ?></td>
                                                                        <td><?= htmlspecialchars($row['department_name'] ?? 'N/A'); ?></td>
                                                                        <td>₱<?= number_format($row['cost'], 2); ?></td>
                                                                        <td>
                                                                            <div class="dropdown d-inline-block">
                                                                                <button class="btn btn-soft-success btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <i class="ri-more-fill align-middle"></i>
                                                                                </button>
                                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item remove-item-btn edit-btn" data-bs-toggle="modal" data-bs-target="#editService" data-services-id="<?= $row['id'] ?>" data-services-category="<?= $row['category'] ?>" data-services-type="<?= $row['type'] ?>" data-services-service="<?= $row['service'] ?>" data-services-department="<?= htmlspecialchars($row['department_name'] ?? '') ?>" data-services-cost="<?= $row['cost'] ?>">
                                                                                            <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item remove-item-btn archive-btn" data-bs-toggle="modal" data-bs-target="#archiveService" data-services-id="<?= $row['id'] ?>">
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
                                        </div>
                                        <div class="tab-pane" id="general" role="tabpanel">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="d-flex justify-content-sm-start">
                                                    <div class="search-box ms-2">
                                                        <input type="text" class="form-control search" placeholder="Search...">
                                                        <i class="ri-search-line search-icon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="listjs-table" id="customerList">
                                                <div class="table-responsive table-card mt-3 mb-1">
                                                    <table class="table align-middle table-nowrap" id="customerTable">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Category</th>
                                                                <th>Type</th>
                                                                <th>Service</th>
                                                                <th>Department</th>
                                                                <th>Cost</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="list">
                                                            <?php
                                                            // Fetch services and their associated department names
                                                            $selectlaboratory = $pdo->query("
        SELECT s.*, d.name AS department_name 
        FROM services s
        LEFT JOIN departments d ON s.department_id = d.id
        WHERE s.category = 'General Services' AND s.is_archive = 0
        ORDER BY s.service ASC
    ");
                                                            if ($selectlaboratory->rowCount() > 0) {
                                                                while ($row = $selectlaboratory->fetch(PDO::FETCH_ASSOC)) {
                                                                    $selectdepartment = $pdo->query("SELECT * FROM departments WHERE id = " . $row['department_id']);
                                                            ?>
                                                                    <tr>
                                                                        <td><?= $row['category']; ?></td>
                                                                        <td><?= $row['type']; ?></td>
                                                                        <td><?= $row['service']; ?></td>

                                                                        <td><?= htmlspecialchars($row['department_name'] ?? 'N/A'); ?></td>
                                                                        <td>₱<?= number_format($row['cost'], 2); ?></td>
                                                                        <td>
                                                                            <div class="dropdown d-inline-block">
                                                                                <button class="btn btn-soft-success btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <i class="ri-more-fill align-middle"></i>
                                                                                </button>
                                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item remove-item-btn edit-btn" data-bs-toggle="modal" data-bs-target="#editService" data-services-id="<?= $row['id'] ?>" data-services-category="<?= $row['category'] ?>" data-services-type="<?= $row['type'] ?>" data-services-service="<?= $row['service'] ?>" data-services-department="<?= htmlspecialchars($row['department_name'] ?? '') ?>"
                                                                                            data-services-cost="<?= $row['cost'] ?>">
                                                                                            <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item remove-item-btn archive-btn" data-bs-toggle="modal" data-bs-target="#archiveService" data-services-id="<?= $row['id'] ?>">
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

    <!-- ADD SERVICE -->
    <div id="addService" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Service Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="Imaging Services" selected>Imaging Services</option>
                            <option value="Laboratory Services">Laboratory Services</option>
                            <option value="General Services">General Services</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="type" required placeholder="eg. X-ray, Ultrasound, ECG">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="service" required placeholder="eg. Chest, Pelvic, BPS">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="department" class="form-select" required>
                            <option value="Doctor/Physician" selected>Doctor/Physician</option>
                            <option value="Radiological Technologist">Radiological Technologist</option>
                            <option value="Medical Consultant">Medical Consultant</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cost (₱) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="cost" required placeholder="eg. 300, 799.80">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_service" class="btn btn-primary ">Add Service</button>
                </div>
            </form>
        </div>
    </div>

    <!-- UPDATE SERVICE -->
    <div id="editService" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Service Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="servicesId" name="editServicesId">
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="editServiceCategory" id="servicesCategory" class="form-select" required>
                            <option value="Imaging Services" selected>Imaging Services</option>
                            <option value="Laboratory Services">Laboratory Services</option>
                            <option value="General Services">General Services</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="editServicesType" id="servicesType" required placeholder="eg. X-ray, Ultrasound, ECG">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="editServicesService" id="servicesService" required placeholder="eg. Chest, Pelvic, BPS">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="editServicesDepartment" id="servicesDepartment" class="form-select" required>
                            <option value="1" selected>Doctor/Physician</option>
                            <option value="2">Radiological Technologist</option>
                            <option value="3">Medical Technician</option>
                            <option value="4">Medical Consultant</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cost (₱) <span class="text-danger">*</span></label>
                        <input type="number" step="any" class="form-control" name="editServicesCost" id="servicesCost" required placeholder="eg. 300, 799.80">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="edit_service" class="btn btn-primary ">Save Changes</button>
                </div>
            </form>
            
        </div>
    </div>

    <!-- ARCHIVE SERVICE -->
    <div id="archiveService" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Archive Service Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="servicesIdDelete" name="servicesIdDelete">
                    <p>Are you sure you want to archive this service?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="archive_service" class="btn btn-danger">Archive</button>
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
    <script src="assets/js/pages/listjs.init.js"></script>
    <script src="assets/js/sweetalert.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <script>
        // JavaScript to handle populating data in the edit modal
        var editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var servicesId = this.getAttribute('data-services-id');
                var servicesCategory = this.getAttribute('data-services-category');
                var servicesType = this.getAttribute('data-services-type');
                var servicesService = this.getAttribute('data-services-service');
                var servicesDepartment = this.getAttribute('data-services-department');
                var servicesCost = this.getAttribute('data-services-cost');

                document.getElementById('servicesId').value = servicesId;
                document.getElementById('servicesCategory').value = servicesCategory;
                document.getElementById('servicesType').value = servicesType;
                document.getElementById('servicesService').value = servicesService;
                document.getElementById('servicesDepartment').value = servicesDepartment;
                document.getElementById('servicesCost').value = servicesCost;
            });
        });
    </script>

    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("customerTable");
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

    <script>
        // JavaScript to handle populating data in the edit modal
        var editButtons = document.querySelectorAll('.archive-btn');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Get the product details from data attributes
                var servicesId = this.getAttribute('data-services-id');

                // Set the product details in the modal form
                document.getElementById('servicesIdDelete').value = servicesId;
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