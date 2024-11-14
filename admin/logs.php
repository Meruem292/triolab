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

    <link href="assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet" />

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

</head>

<body style="background-color: #F2FFF1">

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
                        <div class="text-center mt-sm-5 pt-4 mb-4">
                            <div class="mb-sm-5 pb-sm-4 pb-5">
                                <img src="assets/images/comingsoon.png" alt="" height="120" class="move-animation">
                            </div>
                            <div class="mb-5">
                                <h1 class="display-2 coming-soon-text">Coming Soon</h1>
                            </div>
                            <div>
                                <div class="row justify-content-center mt-5">
                                    <div class="col-lg-8">
                                        <div id="countdown" class="countdownlist"></div>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <h4>Don't worry. We'll be up soon</h4>
                                    <a href="index.php" class="btn btn-primary mt-3">Go back to Dashboard</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <?php
    require "footer.php";
        ?>
        <!-- end Footer -->
    </div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- Swiper Js -->
    <script src="assets/libs/swiper/swiper-bundle.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var now = new Date().getTime();
            var endDate = new Date("May 30, 2024").getTime();
            var timeRemaining = endDate - now;

            if (timeRemaining < 0) {
                // May 30, 2024 has already passed
                var countdownEndMessage = document.createElement("div");
                countdownEndMessage.className = "countdown-endtxt";
                countdownEndMessage.innerHTML = "The countdown has ended!";
                var countdownElement = document.getElementById("countdown");
                if (countdownElement) {
                    countdownElement.innerHTML = ""; // Clear countdown
                    countdownElement.appendChild(countdownEndMessage);
                }
                return;
            }

            var days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
            var hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

            var countdownHtml =
                '<div class="countdownlist-item"><div class="count-title">Days</div><div class="count-num">' +
                days +
                '</div></div><div class="countdownlist-item"><div class="count-title">Hours</div><div class="count-num">' +
                hours +
                '</div></div><div class="countdownlist-item"><div class="count-title">Minutes</div><div class="count-num">' +
                minutes +
                '</div></div><div class="countdownlist-item"><div class="count-title">Seconds</div><div class="count-num">' +
                seconds +
                "</div></div>";

            var countdownElement = document.getElementById("countdown");
            if (countdownElement) {
                countdownElement.innerHTML = countdownHtml;
            }
        });
    </script>

</body>

</html>