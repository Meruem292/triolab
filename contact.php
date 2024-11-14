<?php

require "db.php";
session_start();
$user_id = $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
};

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Triolab - Online Healthcare Management System</title>

    <link rel="shortcut icon" href="assets/images/logo.png" type="image/png">

    <!-- Bootstrap Framework Version 4.5.3 -->
    <link href="assets/css/bootstrap.min.css" type="text/css" rel="stylesheet">

    <!-- Ion Icons Version 5.1.0 -->
    <link href="assets/css/ionicons.css" type="text/css" rel="stylesheet">

    <!-- Medical Icons -->
    <link href="assets/css/medwise-icons.css" type="text/css" rel="stylesheet">

    <!-- Stylesheets -->
    <link href="assets/css/vendors.min.css" type="text/css" rel="stylesheet">
    <link href="assets/css/style.min.css" type="text/css" rel="stylesheet" id="style">
    <link href="assets/css/components.min.css" type="text/css" rel="stylesheet" id="components">

    <!--Google Fonts-->
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;0,900;1,400;1,700;1,900&amp;family=Manrope:wght@300;400;600;800&amp;family=Volkhov:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap" rel="stylesheet">
</head>

<body class="coming-soon">

    <div class="coming-soon-box">
        <div class="icon">
            <i class="ion-alarm-outline"></i>
        </div>
        <h5 class="heading font-bold text-success">We'll be up soon</h5>
        <h6 class="heading h3 font-bold mb-50">Good things take a little time!</h6>
        <ul class="timer">
            <li><span id="days"></span>days</li>
            <li><span id="hours"></span>hrs</li>
            <li><span id="minutes"></span>min</li>
            <li><span id="seconds"></span>sec</li>
        </ul>
        
    <a href="index.php" class="btn btn-success mt-3"><i class="ion-home-outline icon-left"></i> Go Back Home</a>
    </div>


    <div id="back"><i class="ion-chevron-up-sharp"></i></div>

    <!-- JQuery Version 3.6.0 -->
    <script src="assets/js/jquery.min.js"></script>

    <!-- Bootstrap Version 4.5.3 -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery UI (Date Picker) -->
    <script src="assets/js/jquery-ui.min.js"></script>

    <!-- Slick Slider Version 1.8.1 -->
    <script src="assets/js/slick.min.js"></script>

    <!-- Appear JS -->
    <script src="assets/js/jquery.appear.min.js"></script>

    <!-- Count To JS -->
    <script src="assets/js/jquery.countTo.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/script.min.js"></script>

    <script>
        // Set the date you're counting down to
        var countDownDate = new Date("May 30, 2024 11:59:59").getTime();

        // Update the countdown every second
        var x = setInterval(function() {
            // Get the current date and time
            var now = new Date().getTime();

            // Calculate the remaining time
            var distance = countDownDate - now;

            // Calculate days, hours, minutes, and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the countdown in the HTML elements
            document.getElementById("days").innerHTML = days;
            document.getElementById("hours").innerHTML = hours;
            document.getElementById("minutes").innerHTML = minutes;
            document.getElementById("seconds").innerHTML = seconds;

            // If the countdown is over, display a message
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("timer").innerHTML = "EXPIRED";
            }
        }, 1000);
    </script>


</body>

</html>