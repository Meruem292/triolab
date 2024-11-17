<?php

require "db.php";
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($_SESSION['user_id'])){
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

    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

    <style>
        .dropdown-custom {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .dropdown-custom-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 100%;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
            padding: 12px;
            z-index: 1;
        }

        .dropdown-custom:hover .dropdown-custom-content {
            display: block;
        }

        .dropdown-custom-content a {
            color: black;
            padding: 8px 12px;
            text-decoration: none;
            display: block;
        }

        .dropdown-custom-content a:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>

    <?php require "header.php"; ?>

    <div class="page-header">
        <div class="container">
            <h3 class="text-white" style="z-index: 3; position: relative; font-weight: bold;">What do you need?</h3>
            <div class="d-flex align-items-center justify-content-center">
                <div class="dropdown-custom">
                    <form class="form-inline">
                        <div class="input-group" style="z-index: 3; width: 100%;">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ion-search-outline"></i></span>
                            </div>
                            <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                        </div>
                    </form>
                    <div id="searchResults" class="dropdown-custom-content mt-2">
                        Please type any keyword.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-80 mb-80">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="heading-block">
                    <h3 class="heading font-bold text-lh-4">Services Offered</h3>
                    <p class="sub-heading">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur, ea.</p>
                </div>
            </div>
        </div>
        <div class="row" id="searchResultsContainer">
            <?php
            $selectQuery = $pdo->query("SELECT * FROM services WHERE is_archive = 0");
            if ($selectQuery->rowCount() > 0) {
                while ($row = $selectQuery->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <div class="col-lg-4 mt-20">
                        <div class="department-box-1 bg-light rounded">
                            <h4 class="heading font-bold mt-10 mb-10"><?= $row['category']; ?></h4>
                            <p class="heading h6 font-semi-bold text-success"><?= $row['service'] . " (" . $row['type'] . ")"; ?></p>
                            <p class="mb-20 text-lh-7 text-black font-semi-bold">₱<?= number_format($row['cost'], 2); ?></p>
                            <a href="appointment.php?service_id=<?= $row['id']; ?>" class="btn btn-success btn-sm">Book Now</a>
                        </div>
                    </div>
                <?php
                }
            } else {
                ?>
                <div class="mx-auto text-center">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                    <h5 class="mt-2">Sorry! No Result Found</h5>
                    <p class="text-muted mb-0">We've searched in our database but we did not find any data yet!</p>
                </div>
            <?php
            }
            ?>
            <div class="col-lg-12">
                <nav>
                    <ul class="pagination mt-30 justify-content-center">
                        <li>
                            <a href="#" aria-label="Previous">
                                <i class="ion-chevron-back-sharp"></i>
                            </a>
                        </li>
                        <li class="active"><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li>
                            <a href="#" aria-label="Next">
                                <i class="ion-chevron-forward-sharp"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <?php require "footer.php"; ?>

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
        // Function to display search results in container
        function showResultsInContainer(results) {
            const containerElement = $('#searchResultsContainer'); // Select the container where you want to display the search results
            containerElement.empty(); // Clear previous search results
            if (results.length > 0) {
                results.forEach(result => {
                    // Create a new service box element
                    const serviceBox = $('<div>').addClass('col-lg-4 mt-20')
                        .append(
                            $('<div>').addClass('department-box-1 bg-light rounded')
                            .append(
                                $('<h4>').addClass('heading font-bold mt-10 mb-10').text(result.category),
                                $('<p>').addClass('heading h6 font-semi-bold text-success').text(result.service + ' (' + result.type + ')'),
                                $('<p>').addClass('mb-20 text-lh-7 text-black font-semi-bold').text('₱' + parseFloat(result.cost).toFixed(2)),
                                $('<a>').addClass('btn btn-success btn-sm').attr('href', 'appointment.php?service_id=' + result.id).text('Book Now')
                            )
                        );
                    // Append the service box to the container
                    containerElement.append(serviceBox);
                });
            } else {
                // If no results found, display a message
                containerElement.html('<div class="mx-auto text-center">' +
                    '<lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>' +
                    '<h5 class="mt-2">Sorry! No Result Found</h5>' +
                    '<p class="text-muted mb-0">We\'ve searched in our database but we did not find any data yet!</p>' +
                    '</div>');
            }
        }

        // Function to display search results in dropdown
        function showResults(results) {
            const searchResultsElement = $('#searchResults');
            searchResultsElement.empty();
            if (results.length > 0) {
                results.forEach(result => {
                    const link = $('<a>').addClass('dropdown-item').text(result.service);
                    searchResultsElement.append(link);
                });
            } else {
                const message = $('<div>').addClass('dropdown-header').text('No results found');
                searchResultsElement.append(message);
            }
        }


        // Function to fetch keywords from the database via AJAX
        function fetchKeywordsFromDatabase(searchValue) {
            $.ajax({
                url: 'fetch_services.php',
                method: 'POST',
                data: {
                    searchValue: searchValue
                },
                dataType: 'json',
                success: function(response) {
                    showResultsInContainer(response);
                    showResults(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching keywords:', error);
                }
            });
        }

        // Event listener for input change
        $('#searchInput').on('input', function() {
            const searchValue = $(this).val().trim();
            fetchKeywordsFromDatabase(searchValue);
            showResults(searchValue);
        });
    </script>

</body>

</html>