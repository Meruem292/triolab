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
                    <li class="nav-item"><a href="news.php">News</a></li>
                    <li class="nav-item"><a href="about.php">About Us</a></li>
                    <li class="nav-item"><a href="inquiries.php">Inquiries</a></li>
                    <li class="nav-item"><a href="location.php">Location</a></li>
                </ul>
                <?php
                    if(isset($_SESSION['user_id'])) {
                        echo '<a class="btn btn-success text-white btn-sm mx-4" style="border-radius: 5px;" href="logout.php">Logout</a>';
                    }else {
                        echo '<a class="btn btn-success text-white btn-sm mx-4" style="border-radius: 5px;" href="login.php">Login</a>';
                    }
                ?>
            </div>
        </div>
    </nav>
</header>