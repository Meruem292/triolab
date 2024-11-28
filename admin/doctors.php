<?php

require "db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
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
                                <h4 class="mb-sm-0">Doctors</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Doctors</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-body">
                                    <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addDoctor">+ New Doctor</button>
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified mb-3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#doctors" role="tab">
                                                DOCTORS/PHYSICIAN
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#medtech" role="tab">
                                                MEDICAL TECHNICIAN
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#radtech" role="tab">
                                                RADIOLOGIC TECHNOLOGIST
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#consultant" role="tab">
                                                MEDICAL CONSULTANT
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content text-muted">
                                        <!-- DOCTORS/PHYSICIANS -->
                                        <div class="tab-pane fade show active" id="doctors" role="tabpanel">
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
                                                                <th>Name</th>
                                                                <th>Employee ID</th>
                                                                <th>Department</th>
                                                                <th>Email</th>
                                                                <th>Date Created</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="list">
                                                            <?php
                                                            $selectDoctor = $pdo->query("
                    SELECT 
                                                                    doctor.*, 
                                                                    departments.name AS department_name 
                                                                FROM doctor 
                                                                INNER JOIN departments ON doctor.department_id = departments.id 
                                                                WHERE doctor.is_archive = 0 and doctor.department_id = 1
                                                                ORDER BY doctor.firstname ASC
    ");

                                                            if ($selectDoctor->rowCount() > 0) {
                                                                while ($row = $selectDoctor->fetch(PDO::FETCH_ASSOC)) {
                                                                    $fullname = "Dr. " . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']);
                                                                    $formattedDate = date("F j, Y \a\\t g:i a", strtotime($row['date_added']));
                                                            ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div>
                                                                                <img class="image avatar-xs rounded-circle mx-2" alt=""
                                                                                    src="../doctor/<?= htmlspecialchars($row['profile_img'] ?: "assets/images/dummy.png") ?>">
                                                                                <span><?= $fullname; ?></span>
                                                                            </div>
                                                                        </td>
                                                                        <td><?= htmlspecialchars($row['employee_id']); ?></td>
                                                                        <td><?= htmlspecialchars($row['department_name']); ?></td>
                                                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                                                        <td><?= $formattedDate; ?></td>
                                                                        <td>
                                                                            <div class="dropdown d-inline-block">
                                                                                <button class="btn btn-soft-success btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <i class="ri-more-fill align-middle"></i>
                                                                                </button>
                                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                                    <!-- Edit Button -->
                                                                                    <li>
                                                                                        <a href="#"
                                                                                            class="dropdown-item edit-btn"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#editDoctor"
                                                                                            data-employee-id="<?= htmlspecialchars($row['employee_id']); ?>"
                                                                                            data-firstname="<?= htmlspecialchars($row['firstname']); ?>"
                                                                                            data-lastname="<?= htmlspecialchars($row['lastname']); ?>"
                                                                                            data-username="<?= htmlspecialchars($row['username']); ?>"
                                                                                            data-email="<?= htmlspecialchars($row['email']); ?>"
                                                                                            data-department-id="<?= htmlspecialchars($row['department_id']); ?>">
                                                                                            <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update

                                                                                        </a>
                                                                                    </li>
                                                                                    <!-- Archive Button -->
                                                                                    <li>
                                                                                        <a href="#"
                                                                                            class="dropdown-item archive-btn"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#archiveDoctor"
                                                                                            data-doctor-id="<?= htmlspecialchars($row['employee_id']) ?>">
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
                                                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                                                                                    trigger="loop"
                                                                                    colors="primary:#121331,secondary:#08a88a"
                                                                                    style="width:75px;height:75px">
                                                                                </lord-icon>
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
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <div class="pagination-wrap hstack gap-2" style="display: flex;">
                                                        <a class="page-item pagination-prev disabled" href="javascript:void(0);">
                                                            Previous
                                                        </a>
                                                        <ul class="pagination listjs-pagination mb-0">
                                                            <li class="active"><a class="btn btn-primary btn-sm" href="#">1</a></li>
                                                            <li><a class="btn btn-sm" style="border: 1px solid #e9ebec;" href="#">2</a></li>
                                                            <li><a class="btn btn-sm" style="border: 1px solid #e9ebec;" href="#">3</a></li>
                                                        </ul>
                                                        <a class="page-item pagination-next" href="javascript:void(0);">
                                                            Next
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- DOCTORS/PHYSICIANS -->

                                        <!-- MEDTECH -->
                                        <div class="tab-pane fade" id="medtech" role="tabpanel">
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
                                                                <th>Name</th>
                                                                <th>Employee ID</th>
                                                                <th>Department</th>
                                                                <th>Email</th>
                                                                <th>Date Created</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="list">
                                                            <?php
                                                            // Fetch doctors for the "Medical Technologist" department
                                                            $selectDoctor = $pdo->prepare("
                                                                 SELECT 
                                                                    doctor.*, 
                                                                    departments.name AS department_name 
                                                                FROM doctor 
                                                                INNER JOIN departments ON doctor.department_id = departments.id 
                                                                WHERE doctor.is_archive = 0 and doctor.department_id = 3
                                                                ORDER BY doctor.firstname ASC
                                                            ");
                                                            $selectDoctor->execute();

                                                            if ($selectDoctor->rowCount() > 0) {
                                                                while ($row = $selectDoctor->fetch(PDO::FETCH_ASSOC)) {
                                                                    $fullname = "Dr. " . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']);
                                                                    $formattedDate = date("F j, Y \a\\t g:i a", strtotime($row['date_added']));
                                                            ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div>
                                                                                <img class="image avatar-xs rounded-circle mx-2" alt=""
                                                                                    src="../doctor/<?= htmlspecialchars($row['profile_img'] ?: "assets/images/dummy.png") ?>">
                                                                                <span><?= $fullname; ?></span>
                                                                            </div>
                                                                        </td>
                                                                        <td><?= htmlspecialchars($row['employee_id']); ?></td>
                                                                        <td><?= htmlspecialchars($row['department_name']); ?></td>
                                                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                                                        <td><?= $formattedDate; ?></td>
                                                                        <td>
                                                                            <div class="dropdown d-inline-block">
                                                                                <button class="btn btn-soft-success btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <i class="ri-more-fill align-middle"></i>
                                                                                </button>
                                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                                    <!-- Edit Button -->
                                                                                    <li>
                                                                                        <a href="#"
                                                                                            class="dropdown-item edit-btn"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#editDoctor"
                                                                                            data-employee-id="<?= htmlspecialchars($row['employee_id']); ?>"
                                                                                            data-firstname="<?= htmlspecialchars($row['firstname']); ?>"
                                                                                            data-lastname="<?= htmlspecialchars($row['lastname']); ?>"
                                                                                            data-username="<?= htmlspecialchars($row['username']); ?>"
                                                                                            data-email="<?= htmlspecialchars($row['email']); ?>"
                                                                                            data-department-id="<?= htmlspecialchars($row['department_id']); ?>">
                                                                                            <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update

                                                                                        </a>
                                                                                    </li>
                                                                                    <!-- Archive Button -->
                                                                                    <li>
                                                                                        <a href="#"
                                                                                            class="dropdown-item archive-btn"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#archiveDoctor"
                                                                                            data-doctor-id="<?= htmlspecialchars($row['employee_id']) ?>">
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
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <div class="pagination-wrap hstack gap-2">
                                                        <a class="page-item pagination-prev disabled" href="javascript:void(0);">Previous</a>
                                                        <ul class="pagination listjs-pagination mb-0">
                                                            <li class="active"><a class="btn btn-primary btn-sm" href="#">1</a></li>
                                                            <li><a class="btn btn-sm" style="border: 1px solid #e9ebec;" href="#">2</a></li>
                                                            <li><a class="btn btn-sm" style="border: 1px solid #e9ebec;" href="#">3</a></li>
                                                        </ul>
                                                        <a class="page-item pagination-next" href="javascript:void(0);">Next</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- MEDTECH -->

                                        <!-- RADTECH -->
                                        <div class="tab-pane fade" id="radtech" role="tabpanel">
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
                                                                <th>Name</th>
                                                                <th>Employee ID</th>
                                                                <th>Department</th>
                                                                <th>Email</th>
                                                                <th>Date Created</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="list">
                                                            <?php
                                                            // Fetch doctors where the department ID is 2 and is not archived
                                                            $selectDoctor = $pdo->prepare("
                                                                  SELECT 
                                                                    doctor.*, 
                                                                    departments.name AS department_name 
                                                                FROM doctor 
                                                                INNER JOIN departments ON doctor.department_id = departments.id 
                                                                WHERE doctor.is_archive = 0 and doctor.department_id = 2
                                                                ORDER BY doctor.firstname ASC
                                                            ");
                                                            $selectDoctor->execute();

                                                            if ($selectDoctor->rowCount() > 0) {
                                                                while ($row = $selectDoctor->fetch(PDO::FETCH_ASSOC)) {
                                                                    $fullname = "Dr. " . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']);
                                                                    $formattedDate = date("F j, Y \a\\t g:i a", strtotime($row['date_added']));
                                                            ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div>
                                                                                <img class="image avatar-xs rounded-circle mx-2" alt=""
                                                                                    src="../doctor/<?= htmlspecialchars($row['profile_img'] ?: "assets/images/dummy.png") ?>">
                                                                                <span><?= $fullname; ?></span>
                                                                            </div>
                                                                        </td>
                                                                        <td><?= htmlspecialchars($row['employee_id']); ?></td>
                                                                        <td><?= htmlspecialchars($row['department_name']); ?></td>
                                                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                                                        <td><?= $formattedDate; ?></td>
                                                                        <td>
                                                                            <div class="dropdown d-inline-block">
                                                                                <button class="btn btn-soft-success btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <i class="ri-more-fill align-middle"></i>
                                                                                </button>
                                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                                    <!-- Edit Button -->
                                                                                    <li>
                                                                                        <a href="#"
                                                                                            class="dropdown-item edit-btn"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#editDoctor"
                                                                                            data-employee-id="<?= htmlspecialchars($row['employee_id']); ?>"
                                                                                            data-firstname="<?= htmlspecialchars($row['firstname']); ?>"
                                                                                            data-lastname="<?= htmlspecialchars($row['lastname']); ?>"
                                                                                            data-username="<?= htmlspecialchars($row['username']); ?>"
                                                                                            data-email="<?= htmlspecialchars($row['email']); ?>"
                                                                                            data-department-id="<?= htmlspecialchars($row['department_id']); ?>">
                                                                                            <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update

                                                                                        </a>
                                                                                    </li>
                                                                                    <!-- Archive Button -->
                                                                                    <li>
                                                                                        <a href="#"
                                                                                            class="dropdown-item archive-btn"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#archiveDoctor"
                                                                                            data-doctor-id="<?= htmlspecialchars($row['employee_id']) ?>">
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
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <div class="pagination-wrap hstack gap-2">
                                                        <a class="page-item pagination-prev disabled" href="javascript:void(0);">Previous</a>
                                                        <ul class="pagination listjs-pagination mb-0">
                                                            <li class="active"><a class="btn btn-primary btn-sm" href="#">1</a></li>
                                                            <li><a class="btn btn-sm" style="border: 1px solid #e9ebec;" href="#">2</a></li>
                                                            <li><a class="btn btn-sm" style="border: 1px solid #e9ebec;" href="#">3</a></li>
                                                        </ul>
                                                        <a class="page-item pagination-next" href="javascript:void(0);">Next</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- RADTECH -->

                                        <!-- medical consultant -->
                                        <div class="tab-pane fade" id="consultant" role="tabpanel">
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
                                                                <th>Name</th>
                                                                <th>Employee ID</th>
                                                                <th>Department</th>
                                                                <th>Email</th>
                                                                <th>Date Created</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="list">
                                                            <?php
                                                            // Fetch doctors where the department ID is 2 and is not archived
                                                            $selectDoctor = $pdo->prepare("
                                                                  SELECT 
                                                                    doctor.*, 
                                                                    departments.name AS department_name 
                                                                FROM doctor 
                                                                INNER JOIN departments ON doctor.department_id = departments.id 
                                                                WHERE doctor.is_archive = 0 and doctor.department_id = 4
                                                                ORDER BY doctor.firstname ASC
                                                            ");
                                                            $selectDoctor->execute();

                                                            if ($selectDoctor->rowCount() > 0) {
                                                                while ($row = $selectDoctor->fetch(PDO::FETCH_ASSOC)) {
                                                                    $fullname = "Dr. " . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']);
                                                                    $formattedDate = date("F j, Y \a\\t g:i a", strtotime($row['date_added']));
                                                            ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div>
                                                                                <img class="image avatar-xs rounded-circle mx-2" alt=""
                                                                                    src="../doctor/<?= htmlspecialchars($row['profile_img'] ?: "assets/images/dummy.png") ?>">
                                                                                <span><?= $fullname; ?></span>
                                                                            </div>
                                                                        </td>
                                                                        <td><?= htmlspecialchars($row['employee_id']); ?></td>
                                                                        <td><?= htmlspecialchars($row['department_name']); ?></td>
                                                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                                                        <td><?= $formattedDate; ?></td>
                                                                        <td>
                                                                            <div class="dropdown d-inline-block">
                                                                                <button class="btn btn-soft-success btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <i class="ri-more-fill align-middle"></i>
                                                                                </button>
                                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                                    <!-- Edit Button -->
                                                                                    <li>
                                                                                        <a href="#"
                                                                                            class="dropdown-item edit-btn"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#editDoctor"
                                                                                            data-employee-id="<?= htmlspecialchars($row['employee_id']); ?>"
                                                                                            data-firstname="<?= htmlspecialchars($row['firstname']); ?>"
                                                                                            data-lastname="<?= htmlspecialchars($row['lastname']); ?>"
                                                                                            data-username="<?= htmlspecialchars($row['username']); ?>"
                                                                                            data-email="<?= htmlspecialchars($row['email']); ?>"
                                                                                            data-department-id="<?= htmlspecialchars($row['department_id']); ?>">
                                                                                            <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update

                                                                                        </a>
                                                                                    </li>
                                                                                    <!-- Archive Button -->
                                                                                    <li>
                                                                                        <a href="#"
                                                                                            class="dropdown-item archive-btn"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#archiveDoctor"
                                                                                            data-doctor-id="<?= htmlspecialchars($row['employee_id']) ?>">
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
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <div class="pagination-wrap hstack gap-2">
                                                        <a class="page-item pagination-prev disabled" href="javascript:void(0);">Previous</a>
                                                        <ul class="pagination listjs-pagination mb-0">
                                                            <li class="active"><a class="btn btn-primary btn-sm" href="#">1</a></li>
                                                            <li><a class="btn btn-sm" style="border: 1px solid #e9ebec;" href="#">2</a></li>
                                                            <li><a class="btn btn-sm" style="border: 1px solid #e9ebec;" href="#">3</a></li>
                                                        </ul>
                                                        <a class="page-item pagination-next" href="javascript:void(0);">Next</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- medical consultant -->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php require "footer.php"; ?>
        </div>
    </div>
    <?php include "../admin/modals/functions.php" ?>
    <?php include "../admin/modals/add_doctor.php" ?>
    <?php include "../admin/modals/edit_doctor.php" ?>
    <?php include "../admin/modals/archive_doctor.php" ?>

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
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const doctorId = button.getAttribute('data-employee-id');
                const firstname = button.getAttribute('data-firstname');
                const lastname = button.getAttribute('data-lastname');
                const username = button.getAttribute('data-username');
                const email = button.getAttribute('data-email');
                const departmentId = button.getAttribute('data-department-id');

                // Populate modal fields
                document.getElementById('editDoctorId').value = doctorId;
                document.getElementById('editDoctorFirstname').value = firstname;
                document.getElementById('editDoctorLastname').value = lastname;
                document.getElementById('editDoctorUsername').value = username;
                document.getElementById('editDoctorEmail').value = email;
                document.getElementById('editDoctorDepartment').value = departmentId;
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
        flatpickr("#datePicker");
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