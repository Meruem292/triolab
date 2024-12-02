<?php
require "db.php";
session_start();
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

    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body>

    <?php require "header.php"; ?>

    <div class="pt-60 pb-60 pt-lg-120 pb-lg-120" style="background-image: url(assets/images/body-bg.png); background-size: cover; background-position: center left;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 pt-20 pb-20">
                    <h4 class="heading text-white font-bold mb-10">Triolab Diagnostics & Medical Clinic</h4>
                    <h3 class="heading font-20 text-white text-lh-4 font-bold mb-20">Your Health <br> is Our Priority </h3>
                    <p class="text-white h5 mb-0">Best healthcare for you and your family.</p>
                    <a href="services.php" class="btn btn-success btn-lg mt-20 mt-lg-40">Make Appointment</a>
                    <a href="contact.php" class="btn btn-outline-light btn-lg mt-20 mt-lg-40 ml-lg-10">Inquire Now
                        <i class="ion-arrow-forward-sharp icon-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container p-0 mt-80">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="heading-block">
                    <h3 class="heading font-bold text-lh-4">Our Services</h3>
                    <p class="sub-heading"></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 p-40 d-flex align-items-center flex-column bg-grey-1">
                <h4 class="heading font-bold text-lh-4 text-center">Pre-Employment</h4>
                <img src="assets/images/image1.png" width="100" alt="">
            </div>
            <div class="col-lg-3 p-40 d-flex align-items-center flex-column bg-grey-1">
                <h4 class="heading font-bold text-lh-4 text-center">Laboratory</h4>
                <img src="assets/images/image2.png" width="100" alt="">
            </div>
            <div class="col-lg-3 p-40 d-flex align-items-center flex-column bg-grey-1">
                <h4 class="heading font-bold text-lh-4 text-center">Consultation</h4>
                <img src="assets/images/image3.png" width="100" alt="">
            </div>
            <div class="col-lg-3 p-40 d-flex align-items-center flex-column bg-grey-1">
                <h4 class="heading font-bold text-lh-4 text-center">Imaging</h4>
                <img src="assets/images/image4.png" width="100" alt="">
            </div>
            <!-- sinadya ko  -->
            <section id="about">
        </div>
    </div>

    <div class="container mt-80">
        <div class="row">
            <div class="col-lg-7 pr-40">
                <h3 class="heading font-bold mb-10">About Us</h3>
                <p class="text-success h6 font-semi-bold mb-30">Best medical care near you - we are here for you</p>
                <div class="tabs-2">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#t2body1" aria-controls="t2body1" role="tab" data-toggle="tab">
                                <i class="ion-fitness-outline icon-left"></i>
                                Mission
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#t2body2" aria-controls="t2body2" role="tab" data-toggle="tab">
                                <i class="ion-bed-outline icon-left"></i>
                                Vision
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade show active" id="t2body1">
                            <p>To provide a responsive healing environment for patients and their families and to improve the quality of life of all the members of our community.</p>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="t2body2">
                            <p>To be a prominent community member known for meeting the healthcare needs of the entire community through incomparable patient care and wellness.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div style="height: 320px; background-image: url(assets/images/about.jpg); background-size: cover;"></div>
            </div>
        </div>
    </div>
    </section>


    <div class="container mt-80">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="heading-block">
                    <h3 class="heading font-bold text-lh-4">Simple &amp; Quick Process</h3>
                    <p class="sub-heading">Schedule your appointment in just a few clicks</p>
                </div>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-lg-3 mt-20">
                <div class="process-box-1 first">
                    <div class="process-box-header">
                        <i class="ion-add-circle-outline icon"></i>
                    </div>
                    <div class="process-box-body">
                        <h5 class="heading font-bold">Create an account</h5>
                        <p class="mb-0">Sign up for an account.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mt-20">
                <div class="process-box-1">
                    <div class="process-box-header">
                        <i class="ion-calendar-outline icon"></i>
                    </div>
                    <div class="process-box-body">
                        <h5 class="heading font-bold">Book Appointment</h5>
                        <p class="mb-0">Choose your preferred date, time, and payment method.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mt-20">
                <div class="process-box-1">
                    <div class="process-box-header">
                        <i class="ion-wallet-outline icon"></i>
                    </div>
                    <div class="process-box-body">
                        <h5 class="heading font-bold">Make Payment</h5>
                        <p class="mb-0">Securely pay for your appointment online using your preferred payment method.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mt-20">
                <div class="process-box-1 last">
                    <div class="process-box-header">
                        <i class="ion-time-outline icon"></i>
                    </div>
                    <div class="process-box-body">
                        <h5 class="heading font-bold">Wait for approval</h5>
                        <p class="mb-0">Receive a confirmation email of your appointment through your account.</p>
                    </div>
                </div>
            </div>
            <!-- sinadya ko rin -->
            <section id="inquiries">
        </div>
    </div>

    <div class="container mt-80 mb-80">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="heading-block">
                    <h3 class="heading font-bold text-lh-4">Frequently Asked Questions</h3>
                    <p class="sub-heading"></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 mt-20">
                <div id="a3" class="accordion-3" role="tablist">
                    <div class="accordion-item">
                        <div class="accordion-heading" role="tab" id="a3heading1">
                            <h6 class="accordion-title font-bold">
                                <a class="collapsed" role="button" data-toggle="collapse" href="#a3body1" aria-expanded="true" aria-controls="a3body1">
                                    What are the services of Triolab Diagnostic & Medical Clinic?
                                </a>
                            </h6>
                        </div>
                        <div id="a3body1" data-parent="#a3" class="accordion-body collapse" role="tabpanel" aria-labelledby="a3heading1">
                            <div class="accordion-data">
                                Triolab Diagnostic & Medical Clinic offers a wide range of healthcare services, including:
                                <ul>
                                    <li>Medical consultations</li>
                                    <li>Laboratory tests</li>
                                    <li>Diagnostic Imaging Services</li>
                                    <li>Ultrasound</li>
                                    <li>X-ray</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-heading" role="tab" id="a3heading2">
                            <h6 class="accordion-title font-bold">
                                <a class="collapsed" role="button" data-toggle="collapse" href="#a3body2" aria-expanded="false" aria-controls="a3body2">
                                    How could I book an appointment?
                                </a>
                            </h6>
                        </div>
                        <div id="a3body2" data-parent="#a3" class="accordion-body collapse" role="tabpanel" aria-labelledby="a3heading2">
                            <div class="accordion-data">
                                You can easily book an appointment through our online booking system or by calling our clinic directly.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-heading" role="tab" id="a3heading3">
                            <h6 class="accordion-title font-bold">
                                <a class="collapsed" role="button" data-toggle="collapse" href="#a3body3" aria-expanded="false" aria-controls="a3body3">
                                    What are the costs of your laboratory services?</a>
                            </h6>
                        </div>
                        <div id="a3body3" class="accordion-body collapse" data-parent="#a3" role="tabpanel" aria-labelledby="a3heading3">
                            <div class="accordion-data">
                                The cost of laboratory services varies depending on the specific tests required. Please consult with our clinic staff or visit our laboratory section for detailed pricing information.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-heading" role="tab" id="a3heading4">
                            <h6 class="accordion-title font-bold">
                                <a class="collapsed" role="button" data-toggle="collapse" href="#a3body4" aria-expanded="false" aria-controls="a3body4">
                                    Is my data protected and kept safe?</a>
                            </h6>
                        </div>
                        <div id="a3body4" class="accordion-body collapse" data-parent="#a3" role="tabpanel" aria-labelledby="a3heading4">
                            <div class="accordion-data">
                                We prioritize the privacy and security of your personal and medical information. We adhere to strict data protection protocols to ensure the confidentiality of your data.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-20">
                <div id="a2" class="accordion-3" role="tablist">
                    <div class="accordion-item">
                        <div class="accordion-heading" role="tab" id="a2heading1">
                            <h6 class="accordion-title font-bold">
                                <a class="collapsed" role="button" data-toggle="collapse" href="#a2body1" aria-expanded="true" aria-controls="a2body1">
                                    How can I pay for my appointments?
                                </a>
                            </h6>
                        </div>
                        <div id="a2body1" data-parent="#a2" class="accordion-body collapse" role="tabpanel" aria-labelledby="a2heading1">
                            <div class="accordion-data">
                                We accept various payment methods, including cash, and GCash. Please check with our clinic staff for the most up-to-date payment options.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-heading" role="tab" id="a2heading2">
                            <h6 class="accordion-title font-bold">
                                <a class="collapsed" role="button" data-toggle="collapse" href="#a2body2" aria-expanded="false" aria-controls="a2body2">
                                    How would I receive my results?
                                </a>
                            </h6>
                        </div>
                        <div id="a2body2" data-parent="#a2" class="accordion-body collapse" role="tabpanel" aria-labelledby="a2heading2">
                            <div class="accordion-data">
                                You can collect a physical copy of your results from our clinic, or we can send your results directly to your email address.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-heading" role="tab" id="a2heading3">
                            <h6 class="accordion-title font-bold">
                                <a class="collapsed" role="button" data-toggle="collapse" href="#a2body3" aria-expanded="false" aria-controls="a2body3">
                                    How soon can I get my results?</a>
                            </h6>
                        </div>
                        <div id="a2body3" class="accordion-body collapse" data-parent="#a2" role="tabpanel" aria-labelledby="a2heading3">
                            <div class="accordion-data">
                                The turnaround time for results varies depending on the specific test. Our staff will provide you with an estimated timeframe when you book your appointment or submit your samples.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-heading" role="tab" id="a2heading4">
                            <h6 class="accordion-title font-bold">
                                <a class="collapsed" role="button" data-toggle="collapse" href="#a2body4" aria-expanded="false" aria-controls="a2body3">
                                    If I have more questions, who can I direct them to?</a>
                            </h6>
                        </div>
                        <div id="a2body4" class="accordion-body collapse" data-parent="#a2" role="tabpanel" aria-labelledby="a2heading3">
                            <div class="accordion-data">
                                The turnaround time for results varies depending on the specific test. Our staff will provide you with an estimated timeframe when you book your appointment or submit your samples.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-50">
            <div class="col-lg-12 text-center">
                <p class="mb-20">If you could not find an answer to your query, please contact us.</p>
                <a href="contact.php" class="btn btn-success">Raise a Query</a>
            </div>
        </div>
    </div>
    </section>
    <section id="location">
        <?php require "footer.php"; ?>
    </section>

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