<?php

require "db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

function generateEmployeeID($prefix)
{
    // Generate a random 4-digit number
    $randomNumber = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

    // Concatenate the prefix and the random 4-digit number
    return $prefix . $randomNumber;
}

$doctor_id = $_SESSION['user_id'];

if (isset($_POST['add_doctor'])) {
    $employee_id = $_POST['employee_id'];
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $department_id = $_POST['department_id']; // Using department_id now
    $password = $_POST['username']; // No hashing as per requirement

    $checkQuery = $pdo->prepare("SELECT * FROM doctor WHERE email = :email OR username = :username");
    $checkQuery->bindParam(':email', $email);
    $checkQuery->bindParam(':username', $username);
    $checkQuery->execute();

    if ($checkQuery->rowCount() > 0) {
        // Entry already exists
        $_SESSION['message'] = "Doctor already exists in the database.";
        $_SESSION['status'] = "warning";
    } else {
        // Insert the new doctor information into the database
        $insertQuery = $pdo->prepare("
            INSERT INTO doctor (employee_id, firstname, lastname, username, email, password, department_id) 
            VALUES (:employee_id, :firstname, :lastname, :username, :email, :password, :department_id)
        ");
        $insertQuery->bindParam(':employee_id', $employee_id);
        $insertQuery->bindParam(':firstname', $firstname);
        $insertQuery->bindParam(':lastname', $lastname);
        $insertQuery->bindParam(':username', $username);
        $insertQuery->bindParam(':email', $email);
        $insertQuery->bindParam(':password', $password);
        $insertQuery->bindParam(':department_id', $department_id);

        if ($insertQuery->execute()) {
            $_SESSION['message'] = "Doctor added successfully.";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Error inserting doctor.";
            $_SESSION['status'] = "error";
        }
    }
}

if (isset($_POST['edit_doctor'])) {
    // Prepare data for update
    $employee_id = $_POST['employee_id'];
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $department_id = $_POST['department_id']; // Using department_id now

    // Update the doctor information in the database
    $updateQuery = $pdo->prepare("
        UPDATE doctor 
        SET firstname = :firstname, lastname = :lastname, email = :email, username = :username, department_id = :department_id 
        WHERE employee_id = :id
    ");
    $updateQuery->bindParam(':firstname', $firstname);
    $updateQuery->bindParam(':lastname', $lastname);
    $updateQuery->bindParam(':email', $email);
    $updateQuery->bindParam(':username', $username);
    $updateQuery->bindParam(':department_id', $department_id);
    $updateQuery->bindParam(':id', $employee_id);

    if ($updateQuery->execute()) {
        $_SESSION['message'] = "Doctor updated successfully.";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Error updating doctor.";
        $_SESSION['status'] = "error";
    }
}

if (isset($_POST['archive_doctor'])) {
    $doctor_id = $_POST['doctorIdDelete'];
    $archive = 1;

    // Archive the doctor in the database
    $archiveQuery = $pdo->prepare("UPDATE doctor SET is_archive = :archive WHERE employee_id = :id");
    $archiveQuery->bindParam(':archive', $archive);
    $archiveQuery->bindParam(':id', $doctor_id);

    if ($archiveQuery->execute()) {
        $_SESSION['message'] = "Doctor archived successfully.";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Error archiving doctor.";
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
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDoctor">+ New Doctor</button>
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

                                    <!-- Tab panes -->
                                    <div class="tab-content text-muted">
                                        <div class="tab-pane fade show active" id="doctors" role="tabpanel">
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
                                                            $selectDoctor = $pdo->query("
                                                                SELECT 
                                                                    doctor.*, 
                                                                    departments.name AS department_name 
                                                                FROM doctor 
                                                                INNER JOIN departments ON doctor.department_id = departments.id 
                                                                WHERE doctor.is_archive = 0 
                                                                ORDER BY doctor.firstname ASC
                                                            ");

                                                            if ($selectDoctor->rowCount() > 0) {
                                                                while ($row = $selectDoctor->fetch(PDO::FETCH_ASSOC)) {
                                                                    $fullname = "Dr. " . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']);
                                                                    $formattedDate = date("F j, Y \a\\t g:i a", strtotime($row['date_added']));
                                                                    $departmentName = htmlspecialchars($row['department_name']);
                                                            ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div>
                                                                                <img class="image avatar-xs rounded-circle mx-2" alt=""
                                                                                    src="<?= htmlspecialchars($row['profile_img'] ?: "assets/images/dummy.png") ?>">
                                                                                <span><?= $fullname; ?></span>
                                                                            </div>
                                                                        </td>
                                                                        <td><?= htmlspecialchars($row['employee_id']); ?></td>
                                                                        <td><?= $departmentName; ?></td>
                                                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                                                        <td><?= $formattedDate; ?></td>
                                                                        <td>
                                                                            <div class="dropdown d-inline-block">
                                                                                <button class="btn btn-soft-success btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <i class="ri-more-fill align-middle"></i>
                                                                                </button>
                                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item remove-item-btn edit-btn"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#editDoctor"
                                                                                            data-doctor-id="<?= htmlspecialchars($row['employee_id']); ?>"
                                                                                            data-doctor-firstname="<?= htmlspecialchars($row['firstname']); ?>"
                                                                                            data-doctor-lastname="<?= htmlspecialchars($row['lastname']); ?>"
                                                                                            data-doctor-email="<?= htmlspecialchars($row['email']); ?>"
                                                                                            data-doctor-username="<?= htmlspecialchars($row['username']); ?>"
                                                                                            data-doctor-department-id="<?= htmlspecialchars($row['department_id']); ?>">
                                                                                            <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item remove-item-btn archive-btn"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#archiveDoctor"
                                                                                            data-doctor-id="<?= htmlspecialchars($row['employee_id']); ?>">
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
                                                                SELECT doctor.*, departments.name AS department_name 
                                                                FROM doctor 
                                                                JOIN departments ON doctor.department_id = departments.id 
                                                                WHERE departments.name = 'Medical Technologist' AND doctor.is_archive = 0 
                                                                ORDER BY doctor.firstname ASC
                                                            ");
                                                            $selectDoctor->execute();

                                                            if ($selectDoctor->rowCount() > 0) {
                                                                while ($row = $selectDoctor->fetch(PDO::FETCH_ASSOC)) {
                                                                    $fullname = "Dr. " . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']);
                                                                    $dateString = $row['date_added'];
                                                                    $formattedDate = date("F j, Y \a\\t g:i a", strtotime($dateString));
                                                            ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div>
                                                                                <img class="image avatar-xs rounded-circle mx-2" alt="Profile"
                                                                                    src="<?= !empty($row['profile_img']) ? htmlspecialchars($row['profile_img']) : 'assets/images/dummy.png' ?>">
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
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item edit-btn" data-bs-toggle="modal" data-bs-target="#editDoctor"
                                                                                            data-doctor-id="<?= htmlspecialchars($row['employee_id']) ?>"
                                                                                            data-doctor-firstname="<?= htmlspecialchars($row['firstname']) ?>"
                                                                                            data-doctor-lastname="<?= htmlspecialchars($row['lastname']) ?>"
                                                                                            data-doctor-email="<?= htmlspecialchars($row['email']) ?>"
                                                                                            data-doctor-username="<?= htmlspecialchars($row['username']) ?>"
                                                                                            data-doctor-department="<?= htmlspecialchars($row['department_name']) ?>">
                                                                                            <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item archive-btn" data-bs-toggle="modal" data-bs-target="#archiveDoctor"
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
                                                            // Fetch doctors based on the "Radiological Technologist" department dynamically
                                                            $selectDoctor = $pdo->query("SELECT * FROM doctor WHERE department = 'Radiological Technologist' AND is_archive = 0 ORDER BY firstname ASC");
                                                            if ($selectDoctor->rowCount() > 0) {
                                                                while ($row = $selectDoctor->fetch(PDO::FETCH_ASSOC)) {
                                                                    $fullname = "Dr. " . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']);
                                                                    $dateString = $row['date_added'];
                                                                    $formattedDate = date("F j, Y \a\\t g:i a", strtotime($dateString));
                                                            ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div>
                                                                                <img class="image avatar-xs rounded-circle mx-2" alt=""
                                                                                    src="<?= empty($row['profile_img']) ? 'assets/images/dummy.png' : htmlspecialchars($row['profile_img']) ?>">
                                                                                <span><?= $fullname; ?></span>
                                                                            </div>
                                                                        </td>
                                                                        <td><?= htmlspecialchars($row['employee_id']); ?></td>
                                                                        <td><?= htmlspecialchars($row['department']); ?></td>
                                                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                                                        <td><?= $formattedDate; ?></td>
                                                                        <td>
                                                                            <div class="dropdown d-inline-block">
                                                                                <button class="btn btn-soft-success btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <i class="ri-more-fill align-middle"></i>
                                                                                </button>
                                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item remove-item-btn edit-btn" data-bs-toggle="modal" data-bs-target="#editDoctor"
                                                                                            data-doctor-id="<?= htmlspecialchars($row['employee_id']) ?>" data-doctor-firstname="<?= htmlspecialchars($row['firstname']) ?>"
                                                                                            data-doctor-lastname="<?= htmlspecialchars($row['lastname']) ?>" data-doctor-email="<?= htmlspecialchars($row['email']) ?>"
                                                                                            data-doctor-username="<?= htmlspecialchars($row['username']) ?>" data-doctor-department="<?= htmlspecialchars($row['department']) ?>">
                                                                                            <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item remove-item-btn archive-btn" data-bs-toggle="modal" data-bs-target="#archiveDoctor"
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
                                                            // Fetch doctors for "Medical Consultant" department
                                                            $selectDoctor = $pdo->query("SELECT * FROM doctor WHERE department = 'Medical Consultant' AND is_archive = 0 ORDER BY firstname ASC");
                                                            if ($selectDoctor->rowCount() > 0) {
                                                                while ($row = $selectDoctor->fetch(PDO::FETCH_ASSOC)) {
                                                                    $fullname = "Dr. " . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']);
                                                                    $dateString = $row['date_added'];
                                                                    $formattedDate = date("F j, Y \a\\t g:i a", strtotime($dateString));
                                                            ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div>
                                                                                <img class="image avatar-xs rounded-circle mx-2" alt=""
                                                                                    src="<?= empty($row['profile_img']) ? 'assets/images/dummy.png' : htmlspecialchars($row['profile_img']) ?>">
                                                                                <span><?= $fullname; ?></span>
                                                                            </div>
                                                                        </td>
                                                                        <td><?= htmlspecialchars($row['employee_id']); ?></td>
                                                                        <td><?= htmlspecialchars($row['department']); ?></td>
                                                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                                                        <td><?= $formattedDate; ?></td>
                                                                        <td>
                                                                            <div class="dropdown d-inline-block">
                                                                                <button class="btn btn-soft-success btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <i class="ri-more-fill align-middle"></i>
                                                                                </button>
                                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item remove-item-btn edit-btn" data-bs-toggle="modal" data-bs-target="#editDoctor"
                                                                                            data-doctor-id="<?= htmlspecialchars($row['employee_id']) ?>" data-doctor-firstname="<?= htmlspecialchars($row['firstname']) ?>"
                                                                                            data-doctor-lastname="<?= htmlspecialchars($row['lastname']) ?>" data-doctor-email="<?= htmlspecialchars($row['email']) ?>"
                                                                                            data-doctor-username="<?= htmlspecialchars($row['username']) ?>" data-doctor-department="<?= htmlspecialchars($row['department']) ?>">
                                                                                            <i class="ri-edit-fill align-bottom me-2 text-muted"></i> Update
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href="#" class="dropdown-item remove-item-btn archive-btn" data-bs-toggle="modal" data-bs-target="#archiveDoctor"
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

    <!-- ADD DOCTOR Modal -->
    <div id="addDoctor" class="modal fade" tabindex="-1" aria-labelledby="addDoctorLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDoctorLabel">Add Doctor Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="firstname" required placeholder="Enter first name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="lastname" required placeholder="Enter last name">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                        <!-- Dynamically generated Employee ID -->
                        <input type="text" class="form-control" name="employee_id" value="<?= generateEmployeeID('TRLB') ?>" readonly required placeholder="Auto-generated employee ID">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="username" required placeholder="Enter username">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" required placeholder="Enter email address">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="department" class="form-select" required>
                            <option value="Doctor/Physician" selected>Doctor/Physician</option>
                            <option value="Medical Technician">Medical Technician</option>
                            <option value="Radiological Technologist">Radiological Technologist</option>
                            <option value="Medical Consultant">Medical Consultant</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_doctor" class="btn btn-primary">Add Doctor</button>
                </div>
            </form>
        </div>
    </div>


    <!-- UPDATE DOCTOR -->
    <div id="editDoctor" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Doctor Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="doctorFirstname" name="firstname" required placeholder="Enter first name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="doctorLastname" name="lastname" required placeholder="Enter last name">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="employee_id" id="doctorId" readonly required placeholder="Auto-generated or fixed ID">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="doctorUsername" name="username" required placeholder="Enter username">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="doctorEmail" name="email" required placeholder="Enter email address">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="department" id="doctorDepartment" class="form-select" required>
                            <option value="Doctor/Physician">Doctor/Physician</option>
                            <option value="Medical Technician">Medical Technician</option>
                            <option value="Radiological Technologist">Radiological Technologist</option>
                            <option value="Medical Consultant">Medical Consultant</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="edit_doctor" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ARCHIVE DOCTOR -->
    <div id="archiveDoctor" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Archive Doctor Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="doctorIdDelete" name="doctorIdDelete">
                    <p>Are you sure you want to archive the doctor <strong id="doctorName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="archive_doctor" class="btn btn-danger">Archive</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>
    


    <!-- Modern colorpicker bundle -->
    <script src="assets/js/pages/listjs.init.js"></script>
    <script src="assets/js/sweetalert.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <script>
        // Use JavaScript to dynamically populate the modal with the selected doctor's information
        document.addEventListener("DOMContentLoaded", function() {
            const editButtons = document.querySelectorAll(".edit-btn");
            editButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const doctorId = button.getAttribute("data-doctor-id");
                    const firstname = button.getAttribute("data-doctor-firstname");
                    const lastname = button.getAttribute("data-doctor-lastname");
                    const username = button.getAttribute("data-doctor-username");
                    const email = button.getAttribute("data-doctor-email");
                    const department = button.getAttribute("data-doctor-department");

                    // Populate the modal with the doctor's data
                    document.getElementById("doctorFirstname").value = firstname;
                    document.getElementById("doctorLastname").value = lastname;
                    document.getElementById("doctorId").value = doctorId;
                    document.getElementById("doctorUsername").value = username;
                    document.getElementById("doctorEmail").value = email;
                    document.getElementById("doctorDepartment").value = department;
                });
            });
        });
    </script>

    <script>
        // JavaScript to handle dynamic data population when archiving a doctor
        document.addEventListener("DOMContentLoaded", function() {
            const archiveButtons = document.querySelectorAll(".archive-btn");
            archiveButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const doctorId = button.getAttribute("data-doctor-id");
                    const doctorName = button.getAttribute("data-doctor-name");

                    // Populate the modal with the doctor’s ID and name
                    document.getElementById("doctorIdDelete").value = doctorId;
                    document.getElementById("doctorName").textContent = doctorName;
                });
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