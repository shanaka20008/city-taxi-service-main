<?php
include('../includes/connect.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Fav Icon -->
    <link rel="shortcut icon" href="../assets/img/city-taxi-favicon.png" type="image/x-icon" class="object-fit-cover" />
    <title>Dashboard - CityTaxi</title>

    <!-- Google Font (Sen) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Sen:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Just Validate Dev CDN -->
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Bootsrap CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />

    <!-- External CSS -->
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="../assets/css/style2.css">
    <link rel="stylesheet" href="../assets/css/style3.css">
</head>

<body class="overflow-x-hidden bg-external-white">
    <!-- Header Area -->
    <header class="container-fluid background-black-color">
        <nav class="navbar navbar-expand-lg container py-3">
            <div class="container-fluid">
                <!-- Logo -->
                <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="../index.php" style="text-decoration: none;">
                    <img src="../assets/img/city-taxi-logo.png" alt="CityTaxi Logo" style="height: 40px; margin-right: 8px;"/> <!-- Added margin for spacing -->
                    <span class="text-white">CityTaxi</span>
                </a>

                <!-- Toggle Button (Responsive) -->
                <button class="navbar-toggler bg-warning" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menu -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link text-light rounded hover-effect me-2 px-3 fw-semibold" href="../index.php">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active text-light rounded hover-effect me-2 px-3 fw-semibold" href="admin-panel.php?dashboard">Overview</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-light rounded hover-effect me-2 px-3 fw-semibold" href="admin-panel.php?passengers">Passengers</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-light rounded hover-effect me-2 px-3 fw-semibold" href="admin-panel.php?drivers">Drivers</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-light rounded hover-effect me-2 px-3 fw-semibold" href="admin-panel.php?completed_reservations">Completed Reservations</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-light rounded hover-effect me-2 px-3 fw-semibold" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- End -->

    <!-- Dashboard Contents -->
    <main>
        <div class="row">
            <div class="col-md-12">
                <?php
                // PHP Code Dynamically Changing Layouts
                if (isset($_GET['dashboard'])) {
                    include('dashboard.php');
                }
                if (isset($_GET['passengers'])) {
                    include('total-passenger.php');
                }

                if (isset($_GET['drivers'])) {
                    include('drivers.php');
                }

                if (isset($_GET['edit_passenger'])) {
                    include('edit-passenger.php');
                }

                if (isset($_GET['edit_driver'])) {
                    include('edit-driver.php');
                }

                if (isset($_GET['completed_reservations'])) {
                    include('show-completed-reservations.php');
                }
                ?>
            </div>
        </div>
    </main>

    <!-- Boostrap JS Files -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    <!-- End -->
</body>

</html>
