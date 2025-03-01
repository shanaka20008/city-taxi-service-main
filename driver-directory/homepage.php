<?php
include('../includes/connect.php');
session_start();

// echo $_SESSION['username'];
$loggedInUsername = $_SESSION['username'];

$getLoggedInUsernameDetails = mysqli_query($con, "SELECT * FROM `table_driver` WHERE driver_username = '$loggedInUsername'");
$isLoggedInUsernameExist = mysqli_num_rows($getLoggedInUsernameDetails);
// echo var_dump($isLoggedInUsernameExist);

if ($isLoggedInUsernameExist > 0 && $isLoggedInUsernameExist == 1) {
    $fetchLoggedInUserDataFromDB = mysqli_fetch_assoc($getLoggedInUsernameDetails);

    $driverId = $fetchLoggedInUserDataFromDB['driver_id'];
    $availabilityStatus = $fetchLoggedInUserDataFromDB['availability_status'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Fav Icon -->
    <link rel="shortcut icon" href="../assets/img/taxi-img.png" type="image/x-icon" class="object-fit-cover" />
    <title>Profile - CityTaxi</title>

    <!-- Google Font (Sen) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Sen:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Just Validate Dev CDN -->
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>

    <!-- Boxicons Script -->
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Bootsrap CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />

    <!-- External CSS -->
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="../assets/css/style2.css" />
</head>
<!-- bg-external-white -->

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

                <!-- Toggle Button (Responsvie) -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menu -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link text-light rounded hover-effect me-2 px-3 fw-semibold" aria-current="page" href="../index.php">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-light rounded hover-effect me-2 px-3 fw-semibold" href="homepage.php?profile">Profile</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-light rounded hover-effect me-2 px-3 fw-semibold" href="homepage.php?history&driver_id=<?php echo $driverId; ?>">Service Log</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-light rounded hover-effect me-2 px-3 fw-semibold" href="homepage.php?logout">Logout</a>
                        </li>
                    </ul>

                    <!-- Availability Status -->
                    <div class="d-flex align-items-center gap-2">
                        <?php
                        if ($availabilityStatus == "busy") {
                            echo "
                            <label class='text-success fw-semibold text-danger me-2 px-3'>Busy</label>
                            <a href='status-changer.php?driverId={$driverId}' class='text-decoration-none fw-semibold text-light me-2 px-3'>(Change to Available)</a>";
                        } else {
                            echo "
                            <label class='text-success fw-semibold text-success me-2 px-3'>Available</label>
                            <a href='status-changer.php?driverId={$driverId}' class='text-decoration-none fw-semibold text-light me-2 px-3'>(Change to Busy)</a>";
                        }
                        ?>

                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- End -->

    <main>
        <div class="row">
            <div class="col-md-12">
                <?php
                if (isset($_GET['profile'])) {
                    include('profile.php');
                }

                if (isset($_GET['edit-profile'])) {
                    include('edit-profile.php');
                }

                if (isset($_GET['history'])) {
                    include('service-log.php');
                }

                if (isset($_GET['logout'])) {
                    include('logout.php');
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