<?php
session_start();
require 'db.php';
require 'functions.php';
$uploadBaseDir = 'uploads';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}

// Default user ID to show files for the first user (adjust as per your system)
$userId = isset($_POST['user_id']) ? $_POST['user_id'] : 9;  // Set user ID from POST or default to 9

// Handle file uploads
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user_id is set
    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        $userId = $_POST['user_id']; // Get user ID from the form
    } else {
        $_SESSION['message'] = "Error: User ID is required!";
        $_SESSION['status'] = "error";
        header("Location: user_files.php"); // Redirect back to the form page
        exit;
    }

    // Check if the form contains files
    if (isset($_FILES['fileToUpload']) && !empty($_FILES['fileToUpload']['name'][0])) {
        $files = $_FILES['fileToUpload'];
        $uploadOk = true;

        // Iterate over each file in the array
        for ($i = 0; $i < count($files['name']); $i++) {
            $file = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            ];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['message'] = "File upload error for " . htmlspecialchars($file['name']) . ": " . $file['error'];
                $_SESSION['status'] = "error";
                $uploadOk = false;
                break;
            }

            // Fetch patient last name and other details for renaming
            try {
                // Assuming you have a 'patients' table to get the patient's details
                $stmt = $pdo->prepare("SELECT lastname FROM patient WHERE id = :userId");
                $stmt->execute(['userId' => $userId]);
                $patient = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($patient) {
                    $lastname = $patient['lastname'];
                } else {
                    $_SESSION['message'] = "Patient not found!";
                    $_SESSION['status'] = "error";
                    $uploadOk = false;
                    break;
                }
            } catch (PDOException $e) {
                $_SESSION['message'] = "Error: " . $e->getMessage();
                $_SESSION['status'] = "error";
                $uploadOk = false;
                break;
            }

            // Get service and date from the form
            $serviceType = $_POST['service_type'];  // e.g., 'checkup', 'consultation'
            $serviceName = $_POST['service_name'];  // e.g., 'medical', 'dental'
            $date = $_POST['date'];  // e.g., '2024-12-01'

            // Create the new base file name in the format lastname_type_service_date
            $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
            $baseFileName = strtolower($lastname) . '_' . strtolower($serviceType) . '_' . strtolower($serviceName) . '_' . date('Y-m-d', strtotime($date));

            // Check if a file with the same name already exists and add a postfix number if it does
            $uploadBaseDir = 'uploads';  // Assuming a base upload directory
            $userDir = $uploadBaseDir . DIRECTORY_SEPARATOR . 'user_' . $userId;
            if (!is_dir($userDir)) {
                mkdir($userDir, 0755, true);
            }

            // Generate a unique filename with a number postfix if needed
            $newFileName = $baseFileName . '.' . $fileExt;
            $filePath = $userDir . DIRECTORY_SEPARATOR . $newFileName;
            $counter = 1;
            while (file_exists($filePath)) {
                $newFileName = $baseFileName . '_' . $counter . '.' . $fileExt;
                $filePath = $userDir . DIRECTORY_SEPARATOR . $newFileName;
                $counter++;
            }

            // Allowed file types
            $allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/bmp',
                'image/webp',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'text/plain',
                'application/zip',
                'application/x-rar-compressed',
                'application/sql',
                'text/x-sql'
            ];

            if (!in_array($file['type'], $allowedTypes)) {
                $_SESSION['message'] = "Unsupported file type: " . htmlspecialchars($file['name']);
                $_SESSION['status'] = "error";
                $uploadOk = false;
                break;
            }

            if ($uploadOk) {
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    // Save file details to the database
                    try {
                        $stmt = $pdo->prepare("INSERT INTO patient_files (patient_id, directory, file_name) VALUES (?, ?, ?)");
                        if ($stmt->execute([$userId, $userDir, $newFileName])) {
                            $_SESSION['message'] = "The file " . htmlspecialchars(basename($newFileName)) . " has been uploaded and saved!";
                            $_SESSION['status'] = "success";
                        } else {
                            $_SESSION['message'] = "Failed to save file details to the database.";
                            $_SESSION['status'] = "error";
                        }
                    } catch (PDOException $e) {
                        $_SESSION['message'] = "Error: " . $e->getMessage();
                        $_SESSION['status'] = "error";
                    }
                } else {
                    $_SESSION['message'] = "Error moving the uploaded file: " . htmlspecialchars($file['name']);
                    $_SESSION['status'] = "error";
                }
            }
        }
    }
    header("Location: user_files.php"); // Redirect back to the form page
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

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

    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">

    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Prevents reloading on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        // AJAX function to search for users
        function searchUsers() {
            let searchQuery = $('#searchQuery').val();
            if (searchQuery.length > 0) {
                $.ajax({
                    url: 'search_patient.php', // PHP file that performs the search query
                    type: 'GET',
                    data: {
                        query: searchQuery
                    },
                    success: function(response) {
                        $('#userResults').html(response);
                    }
                });
            } else {
                $('#userResults').empty();
            }
        }

        // Select a user and show the files
        function selectUser(userId, userName) {
            // Set the selected user's name in the search input field
            $('#searchQuery').val(userName);

            // Set user ID in hidden input and show files
            $('#user_id').val(userId);
            $('#userResults').empty(); // Clear the suggestions list
            $('#uploadSection').show(); // Show the file upload section
            loadFiles(userId); // Load the user's files

        }

        // Load user files
        function loadFiles(userId) {
            $.ajax({
                url: 'load_files.php',
                type: 'GET',
                data: {
                    user_id: userId
                },
                success: function(response) {
                    $('#fileTable').html(response);
                }
            });
        }

        // Add hover effect for suggestions
        $(document).on('mouseenter', '.list-group-item', function() {
            $(this).css('background-color', '#f1f1f1'); // Highlight on hover
        }).on('mouseleave', '.list-group-item', function() {
            $(this).css('background-color', ''); // Remove highlight when hover ends
        });
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
                                <h4 class="mb-sm-0">Files</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Files</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-body">
                                    <!-- Search bar -->
                                    <h5>Search for a Patient</h5>
                                    <input type="text" class="form-control" id="searchQuery" placeholder="Enter patient ID or name" onkeyup="searchUsers()">
                                    <div id="userResults" class="list-group"></div>

                                    <!-- File Upload Section -->
                                    <div id="uploadSection" style="display:none;">
                                        <span id="selectedUserId"></span>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <!-- Hidden user ID field -->
                                            <?php
                                            // Fetch distinct service types and services
                                            $serviceTypes = getDistinctServiceTypes();
                                            $services = getDistinctServices();
                                            ?>

                                            <div class="row mt-5">
                                                <h5>SELECT FIELD TO DESCRIBE YOUR FILE(s)</h5>
                                                <div class="col-4">
                                                    <!-- Service Type Dropdown -->
                                                    <select class="form-control" name="service_name" id="service_name" required>
                                                        <option value="" disabled selected>Select Service Type</option>
                                                        <?php foreach ($serviceTypes as $serviceType): ?>
                                                            <option value="<?php echo $serviceType; ?>"><?php echo $serviceType; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <select class="form-control" name="service_type" id="service_type" required>
                                                        <option value="" disabled selected>Select Service</option>
                                                        <?php foreach ($services as $service): ?>
                                                            <option value="<?php echo $service; ?>"><?php echo $service; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control" type="date" name="date" id="date" required>
                                                    <input type="hidden" name="user_id" id="user_id">
                                                </div>
                                            </div>



                                            <div class="row mt-3 mb-3">
                                                <div class="col-12">
                                                    <label for="fileToUpload" class="form-label">Select file(s) to upload:</label>
                                                    <input type="file" class="form-control" name="fileToUpload[]" id="fileToUpload" multiple required>
                                                </div>
                                                <div class="col-12 text-end mt-2">
                                                    <!-- Button aligned to the right -->
                                                    <button class="btn btn-success" name="buttonSubmit" id="buttonSubmit" type="submit">Upload File(s)</button>
                                                </div>
                                            </div>

                                        </form>


                                        <!-- Display Files for the Selected User -->
                                        <h1>Patients Files</h1>
                                        <div id="fileTable"></div>
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
    <style>
        /* Highlight suggestions on hover */
        #userResults .list-group-item:hover {
            background-color: #f1f1f1;
            /* Light background on hover */
            cursor: pointer;
            /* Pointer cursor */
        }
    </style>

    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>

    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

    <!-- SweetAlert Integration -->
    <?php if (isset($_SESSION['message']) && isset($_SESSION['status'])): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: "<?php echo $_SESSION['status'] == 'success' ? 'Success!' : 'Error!'; ?>",
                text: "<?php echo $_SESSION['message']; ?>",
                icon: "<?php echo $_SESSION['status']; ?>",
                confirmButtonText: 'OK'
            });
        </script>
        <?php
        unset($_SESSION['message']);
        unset($_SESSION['status']);
        ?>
    <?php endif; ?>

</body>

</html>