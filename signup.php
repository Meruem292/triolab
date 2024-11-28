<?php

require "db.php";
session_start();

if (isset($_POST['register'])) {
    // Retrieve form data
    $firstname = ucfirst($_POST['firstname']);
    $lastname = ucfirst($_POST['lastname']);
    $email = $_POST['email'];
    $password = $_POST['password'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $dob = $_POST['dob'];
    $province = strtoupper($_POST['province']);
    $city = strtoupper($_POST['city']);
    $barangay = strtoupper($_POST['barangay']);
    $street = strtoupper($_POST['street']);
    $birthplace = strtoupper($_POST['birthplace']);
    $contact = $_POST['contact'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check if the email already exists
        $stmt = $pdo->prepare("SELECT * FROM patient WHERE email = ?");
        $stmt->execute([$email]);
        $existing_user = $stmt->fetch();

        if ($existing_user) {
            $_SESSION['message'] = "Email already exists. Please choose a different email.";
            $_SESSION['status'] = "error";
        } else {
            // SQL query to insert user data into the database
            $stmt = $pdo->prepare("INSERT INTO patient (firstname, lastname, email, password , age, sex, dob, province, city, barangay, street , birthplace, contact)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $email, $hashed_password, $age, $sex, $dob, $province, $city, $barangay, $street, $birthplace, $contact]);
            $_SESSION['message'] = "Registration successful! You can now sign in.";
            $_SESSION['status'] = "success";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="green">

<head>

    <meta charset="utf-8" />
    <title>Triolab - Online Healthcare Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/png">

    <!-- Layout config Js -->
    <script src="admin/assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="admin/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="admin/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="admin/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="admin/assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="admin/assets/css/sweetalert.css">
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</head>

<body>

    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="#" class="d-inline-block auth-logo">
                                    <img src="admin/assets/images/logo.png" class="logo" width="100" alt="">
                                    <p class="text-white fs-4">Triolab Medical and Diagnostic Clinic</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-8">
                        <div class="card mt-4 card-bg-fill">
                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Create New Account</h5>
                                    <p class="text-muted">Sign up to continue to Triolab.</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form class="needs-validation" method="POST" novalidate>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="firstname" class="form-label">First Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Enter first name" required>
                                                    <div class="invalid-feedback">
                                                        Please enter first name`
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter last name" required>
                                                    <div class="invalid-feedback">
                                                        Please enter last name
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="age" class="form-label">Age <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" name="age" id="age" placeholder="Enter you age" required>
                                                    <div class="invalid-feedback">
                                                        Please enter your age
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="sex" class="form-label">Sex<span class="text-danger">*</span></label>
                                                    <select name="sex" id="sex" class="form-control" required>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Please enter last name
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="dob" class="form-label">Date of Birth<span class="text-danger">*</span></label>
                                                    <input type="date" name="dob" class="form-control" required>
                                                    <div class="invalid-feedback">
                                                        Please enter last name
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="province" class="form-label">Province<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="province" id="province" required placeholder="ex. Cavite">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="city" class="form-label">City<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="city" id="city" required placeholder="ex. Dasmariñas">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="barangay" class="form-label">Barangay<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="barangay" id="barangay" required placeholder="ex. San Luis 1">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="street" class="form-label">Street<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="street" id="street" required placeholder="ex. Blk A-1 Lot 1">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <label for="birthplace" class="form-label">Birthplace<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="birthplace" id="birthplace" required placeholder="ex. Dasmariñas">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="contact" class="form-label">Contact<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="contact" id="contact" required placeholder="ex. 09123456789">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="Enter email address" required>
                                            <div class="invalid-feedback">
                                                Please enter email address
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="password-input">Password</label>
                                            <div class="position-relative auth-pass-inputgroup">
                                                <input type="password" name="password" class="form-control pe-5 password-input" onpaste="return false" placeholder="Enter password" id="password-input" required>
                                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                <div class="invalid-feedback">
                                                    Please enter password
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <p class="mb-0 fs-12 text-muted fst-italic">By registering you agree to the Triolab <a href="#" class="text-primary text-decoration-underline fst-normal fw-medium">Terms of Use</a></p>
                                        </div>
                                        <div class="mt-4">
                                            <button class="btn btn-primary w-100" name="register" type="submit">Sign up</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="mb-0">Already have an account ? <a href="login.php" class="fw-semibold text-primary text-decoration-underline"> Signin </a> </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © Triolab. Your Online Healthcare Management System
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end d-none d-sm-block">
                            All rights reserved.
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- JAVASCRIPT -->
    <script src="admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="admin/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="admin/assets/libs/node-waves/waves.min.js"></script>
    <script src="admin/assets/libs/feather-icons/feather.min.js"></script>
    <script src="admin/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="admin/assets/js/plugins.js"></script>

    <!-- particles js -->
    <script src="admin/assets/libs/particles.js/particles.js"></script>
    <!-- particles app js -->
    <script src="admin/assets/js/pages/particles.app.js"></script>
    <!-- validation init -->
    <script src="admin/assets/js/pages/form-validation.init.js"></script>
    <!-- password create init -->
    <script src="admin/assets/js/pages/passowrd-create.init.js"></script>
    <script src="admin/assets/js/sweetalert.js"></script>

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