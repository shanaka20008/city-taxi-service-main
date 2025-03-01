<?php
include('../includes/connect.php');
session_start();

// echo $_SESSION['passengerUsername'];
// Todo: Need to Update Passenger Ride status based on Beginning & Ending of Taxi Ride.
// * Storyline
// * 1. Need to get passenger_id from `table_passenger`. Using SESSION, we can fetch all the detail.
$sessionPassengerUsername = $_SESSION['passengerUsername'];

$fetchAllDetailsOfPassengerUsername = mysqli_query($con, "SELECT id FROM `table_passenger` WHERE passenger_username = '$sessionPassengerUsername'");
$arrayOfPassengerDetail = mysqli_fetch_assoc($fetchAllDetailsOfPassengerUsername);

$isPassengerUsernameExist = mysqli_num_rows($fetchAllDetailsOfPassengerUsername);
if ($isPassengerUsernameExist > 0 && $isPassengerUsernameExist == 1) {
  $passengerId = $arrayOfPassengerDetail['id'];
  // echo $passengerId;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Fav Icon -->
  <link rel="shortcut icon" href="../assets/img/taxi-img.png" type="image/x-icon" class="object-fit-cover" />
  <title>Passenger Profile - CityTaxi</title>

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

        <!-- Toggle Button (Responsvie) -->
        <button class="navbar-toggler bg-warning" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link text-light rounded hover-effect me-2 px-3 fw-semibold" aria-current="page" href="../index.php">Home</a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link active text-light rounded hover-effect me-2 px-3 fw-semibold" href="passenger-homepage.php?profile">Profile</a>
            </li>

            <li class="nav-item">
              <a class="nav-link text-light rounded hover-effect me-2 px-3 fw-semibold" href="../select-driver.php">Reserve a Taxi</a>
            </li>

            <li class="nav-item">
              <a class="nav-link text-light rounded hover-effect me-2 px-3 fw-semibold" href="passenger-homepage.php?history">Trip Log</a>
            </li>

            <li class="nav-item">
              <a class="nav-link text-light rounded hover-effect me-2 px-3 fw-semibold" href="logout.php">Logout</a>
            </li>

            <li class="nav-item">
              <!-- 
                Logic of Status
                1. 

               -->
              <?php
              $getStatusOfTrip = mysqli_query($con, "SELECT * FROM `table_reservation` WHERE passenger_id = $passengerId AND reservation_status = 'on process'");

              $isStatusExist = mysqli_num_rows($getStatusOfTrip);
              if ($isStatusExist == 1) {
                $arrayOfStatus = mysqli_fetch_assoc($getStatusOfTrip);

                $reservationId = $arrayOfStatus['reservation_id'];
                $onProcessState = $arrayOfStatus['reservation_status'];

                if ($onProcessState == "on process") {
                  echo "<a class='nav-link bg-danger text-light btn me-2 px-3 fw-semibold' href='./payment/payment.php?reservation_id={$reservationId}'>Tap & Pay</a>";
                }
              }
              // else {
              //   echo "<a class='nav-link bg-success text-light btn me-2 px-3 fw-semibold' href='./payment/payment.php?passenger_id={$passengerId}'> Tap to Pay</a>";
              // }
              ?>
            </li>
          </ul>
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
          include('passenger-profile.php');
        }

        if (isset($_GET['history'])) {
          include('trip-history.php');
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