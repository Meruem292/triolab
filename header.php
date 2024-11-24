<header class="header-1">
    <div class="topbar">
        <div class="container-lg">
            <div class="row no-gutters">
                <div class="col-md-12">
                    <div class="topbar-items">
                        <ul class="topbar-social d-none d-lg-inline-flex">
                            <li><a href="#"><i class="ion-logo-facebook text-success"></i></a></li>
                            <li><a href="#"><i class="ion-logo-linkedin text-success"></i></a></li>
                            <li><a href="#"><i class="ion-logo-instagram text-success"></i></a></li>
                            <li><a href="#"><i class="ion-logo-twitter text-success"></i></a></li>
                        </ul>
                        <ul class="widgets">
                            <li class="email-widget d-none d-lg-inline-flex"><i class="ion-mail-outline"></i> triolab@gmail.com</li>
                            <li class="email-widget d-none d-lg-inline-flex"><i class="ion-call-outline"></i> 0938 453 8273 / 0991 645 7318</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg sticky-nav">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/images/logo.png" alt="" class="logo">
            </a>

            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#main-navigation">
                <span class="navbar-toggler-icon">
                    <span class="one"></span>
                    <span class="two"></span>
                    <span class="three"></span>
                </span>
            </button>

            <div class="navbar-collapse collapse" id="main-navigation">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="index.php">Home</a></li>
                    <li class="nav-item"><a href="services.php">Services</a></li>
                    <li class="nav-item" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <a class="nav-link" href="javascript:void(0);" id="openModal">Your Appointments</a>
                    </li>
                    <li class="nav-item"><a href="news.php">News</a></li>
                    <li class="nav-item"><a href="about.php">About Us</a></li>
                    <li class="nav-item"><a href="inquiries.php">Inquiries</a></li>
                    <li class="nav-item"><a href="location.php">Location</a></li>
                </ul>
                <?php
                if (isset($_SESSION['user_id'])) {
                    echo '<a class="btn btn-success text-white btn-sm mx-4" style="border-radius: 5px;" href="logout.php">Logout</a>';
                } else {
                    echo '<a class="btn btn-success text-white btn-sm mx-4" style="border-radius: 5px;" href="login.php">Login</a>';
                }
                ?>
            </div>
        </div>
    </nav>

    <?php
    include "db.php";
    if (isset($_SESSION['user_id'])) {
        try {
            // Fetch all appointment records for the logged-in user
            $query = "SELECT * FROM appointment WHERE patient_id = :patient_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['patient_id' => $_SESSION['user_id']]);
            $appointments = $stmt->fetchAll();

            // Fetch all doctors
            $queryDoctors = "SELECT employee_id, firstname FROM doctor";
            $stmtDoctors = $pdo->prepare($queryDoctors);
            $stmtDoctors->execute();
            $doctors = $stmtDoctors->fetchAll(PDO::FETCH_KEY_PAIR); // Use doctor ID as key

            $queryServices = "SELECT id,type type FROM services";
            $stmtServices = $pdo->prepare($queryServices);
            $stmtServices->execute();
            $services = $stmtServices->fetchAll(PDO::FETCH_KEY_PAIR); // Use doctor ID as key

            $queryPaymentMode = "SELECT id,method FROM payment_mode";
            $stmtPaymentMode = $pdo->prepare($queryPaymentMode);
            $stmtPaymentMode->execute();
            $paymentModes = $stmtPaymentMode->fetchAll(PDO::FETCH_KEY_PAIR); // Use doctor ID as key
        } catch (Exception $e) {
            die("Error fetching data: " . $e->getMessage());
        }
    } else {
        $appointments = [];
        $doctors = [];
        $services = [];
        $paymentModes = [];
    }
    ?>

    <!-- Modal Structure -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Your Appointments</h4>
                    <table id="appointmentTable" class="table table-light table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Appointment Date</th>
                                <th>Doctor</th>
                                <th>Selected Payment</th>
                                <th>Note</th>
                                <th>Paid</th>
                                <th>Appointment Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $appointment) {
                                $appointmentId = $appointment['id'];
                                $serviceName = htmlspecialchars($services[$appointment['service_id']]);
                                $appointmentDate = htmlspecialchars($appointment['appointment_date']);
                                $appointmentTime = htmlspecialchars($appointment['appointment_time']);
                                $appointmentPayment = htmlspecialchars($paymentModes[$appointment['selectedPayment']]);
                                $doctorId = $appointment['doctor_id'];
                                $doctorName = htmlspecialchars($doctors[$doctorId] ?? 'No doctor has been assigned');

                                $appointmentNote = htmlspecialchars($appointment['medical']);
                                $appointmentPaid = htmlspecialchars($appointment['paid']);
                            ?>
                                <tr>
                                    <td><?= $serviceName ?></td>
                                    <td><?= $appointmentDate . " (" . $appointmentTime . ")" ?></td>
                                    <td><?= $doctorName ?></td>
                                    <td><?= $appointmentPayment ?></td>
                                    <td><?= $appointmentNote ?></td>
                                    <td><?= $appointmentPaid ?></td>
                                    <td>
                                        <a href="assets/docs/appointment_receipt.php?appointment_id=<?= $appointmentId ?>" class="btn btn-primary">Download Receipt</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#appointmentTable').DataTable();
        });

        // Open modal programmatically
        document.getElementById("openModal").addEventListener("click", function(event) {
            var myModal = new bootstrap.Modal(document.getElementById("exampleModal"));
            myModal.show();
        });
    </script>
</header>