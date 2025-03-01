<?php

use GuzzleHttp\Promise\Is;

include('./includes/connect.php');
session_start();
// echo $_SESSION['username'];
// echo $_SESSION['passengerUsername'];
// $sessionDriverName = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="./assets/img/city-taxi-favicon.png" type="image/x-icon" class="object-fit-cover" />
  <title>Home - CityTaxi</title>

  <!-- Google Font (Sen) -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Sen:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <!-- Bootsrap CSS -->
  <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />

  <!-- External CSS -->
  <link rel="stylesheet" href="./assets/css/style.css" />

</head>

<body class="overflow-x-hidden">
  <!-- Header -->
   <header class="container-fluid background-black-color">
  <nav class="navbar navbar-expand-lg container py-3">
    <div class="container-fluid">
      <!-- Logo -->
      <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="./index.php" style="text-decoration: none;">
        <img src="./assets/img/city-taxi-logo.png" alt="CityTaxi Logo" style="height: 40px; margin-right: 8px;"/> <!-- Added margin for spacing -->
        <span class="text-white">CityTaxi</span>
      </a>
      
      <!-- Toggle Button (Responsvie) -->
      <button class="navbar-toggler bg-primary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <!-- Menu -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <!-- Home -->
          <li class="nav-item">
            <a class="nav-link text-white rounded hover-effect me-4 px-3 fw-bold" aria-current="page" href="./index.php">Home</a>
          </li>
        </ul>
        
        <!-- Login Button -->
        <div class="btn-group me-4">
          <?php
            if (isset($_SESSION['username'])) {
              echo "
              <a href='logout.php' class='btn btn-primary fw-bold'>
              Logout
              </a>
              ";
            }
            
            if (isset($_SESSION['passengerUsername'])) {
              echo "
              <a href='logout.php' class='btn btn-primary fw-bold'>
              Logout
              </a>
              ";
            }
            
            if (!(isset($_SESSION['username'])) && !((isset($_SESSION['passengerUsername'])))) {
              echo "
              <button type='button' class='btn btn-primary dropdown-toggle fw-bold' data-bs-toggle='dropdown' aria-expanded='false'>
              Login
              </button>
              <ul class='dropdown-menu background-black-color mt-2 p-2'>
              <li><a class='dropdown-item text-light hover-effect' href='./admin-panel/index.php'>Admin</a></li>
              <li><a class='dropdown-item text-light hover-effect' href='./driver-directory/login.php'>Driver</a></li>
              <li><a class='dropdown-item text-light hover-effect' href='./passenger-directory/passenger-login.php'>Passenger</a></li>
              <li><a class='dropdown-item text-light hover-effect' href='./operator/index.php'>Operator</a></li>
              </ul>
              ";
            }
            ?>
          </div>
          
          
          <!-- Sign-Up Button -->
          <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle fw-bold" data-bs-toggle="dropdown" aria-expanded="false">
              Sign Up
            </button>
            <ul class="dropdown-menu background-black-color mt-2 p-2">
              <li><a class="dropdown-item text-light hover-effect" href="./sign-up/driver.php">Driver</a></li>
              
              <li><a class="dropdown-item text-light hover-effect" href="./sign-up/customer.php">Passenger</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <!-- End -->

  <!-- Hero Section -->
  <div class="container-fluid vh-100 d-flex flex-column justify-content-center align-items-center" style="position: relative; overflow: hidden;">
    <div class="background-image" style="background-image: url('./assets/img/1.webp'); background-size: cover; background-position: center; width: 100%; height: 100%; position: absolute; top: 0; left: 0;"></div>
    <div class="overlay" style="background: rgba(0, 0, 0, 0.5); width: 100%; height: 100%; position: absolute; top: 0; left: 0;"></div>
    
    <div class="text-white d-flex flex-column justify-content-center align-items-center position-relative z-index-1">
      <h1 class="display-4 fw-bold text-uppercase">City Taxi Service</h1>
      <p class="lead">Reliable and Safe Rides Across the City</p>
      <a href="tel:+12345678900" class="btn btn-lg btn-primary fw-bold text-uppercase mt-3">Call Us</a>
    </div>
  </div>

  <!-- Why Choose Us Section -->
  <div class="container-fluid bg-light mt-5 mb-5 p-5">
    <h3 class="text-center mb-5 fw-bold">Why Choose Us</h3>

    <div class="row justify-content-center gap-5 mb-5">
      <div class="col-12 col-md-4 border d-flex justify-content-between align-items-start gap-3 p-4 white-variant rounded-1">
        <i class="fa-solid fa-clock bg-primary p-3 rounded-5"></i>
        <div>
          <p class="mb-2 fw-bold">24/7 Availability</p>
          <p>
            We are here for you around the clock, every day of the week.
            Whether you need a ride early in the morning or late at night,
            we're ready to serve you.
          </p>
        </div>
      </div>

      <div class="col-12 col-md-4 border d-flex justify-content-between align-items-start gap-3 p-4 white-variant rounded-1">
        <i class="fa-solid fa-user-shield bg-primary p-3 rounded-5"></i>
        <div>
          <p class="mb-2 fw-bold">Safety First</p>
          <p>
            Your safety is our priority. Our drivers are well-trained,
            and our vehicles are regularly maintained to ensure a secure
            journey.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Customer Testimonials Section -->
  <div class="bg-primary text-center p-5">
    <h3 class="text-center fw-bold mb-4">What Our Customers Say</h3>
    <div class="container">
      <div class="row justify-content-center gap-5 mb-5">
        <div class="col-md-4">
          <p class="text-white">
            "CityTaxi made my commute so much easier. Always on time and
            the drivers are so friendly!"
            <br><strong>- John D.</strong>
          </p>
        </div>
        <div class="col-md-4">
          <p class="text-white">
            "I trust CityTaxi for all my transportation needs. Reliable,
            safe, and always available."
            <br><strong>- Sarah M.</strong>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Available Fleet Section -->
  <div class="container-fluid mt-5 mb-5 p-5">
    <h3 class="text-center mb-5 fw-bold">Our Fleet</h3>
    <div class="row justify-content-center gap-5 mb-5">
      <div class="col-12 col-md-4 text-center">
        <img src="./assets/img/camry.webp" class="img-fluid" alt="Sedan Car">
        <h5 class="fw-bold mt-3">Sedan</h5>
        <p>Comfortable, stylish, and perfect for solo or small group rides.</p>
      </div>
      <div class="col-12 col-md-4 text-center">
        <img src="./assets/img/v8.webp" class="img-fluid" alt="SUV Car">
        <h5 class="fw-bold mt-3">SUV</h5>
        <p>Spacious and powerful. Ideal for group travel or extra luggage.</p>
      </div>
    </div>
  </div>

<!-- Footer -->
<div class="bg-black text-light">
  <div class="container p-5">
    <div class="row">
      <!-- About Section -->
      <div class="col-12 col-md-4">
        <h4 class="fw-bold">About CityTaxi</h4>
        <p class="text-white-50">
          Meet City Taxi - your go-to for swift, safe rides! With a commitment to excellence, we offer seamless travel with a reliable fleet and professional drivers. Your journey, our priority. Choose us for a ride that exceeds expectations!
        </p>
      </div>
      
      <!-- Quick Links Section -->
      <div class="col-12 col-md-4">
        <h4 class="fw-bold">Quick Links</h4>
        <ul class="list-unstyled">
          <li><a href="#" class="text-decoration-none text-light">Home</a></li>
          <li><a href="#" class="text-decoration-none text-light">Services</a></li>
          <li><a href="#" class="text-decoration-none text-light">About Us</a></li>
          <li><a href="#" class="text-decoration-none text-light">Contact</a></li>
          <li><a href="#" class="text-decoration-none text-light">FAQ</a></li>
        </ul>
      </div>

      <!-- Contact Section -->
      <div class="col-12 col-md-4">
        <h4 class="fw-bold">Connect with Us</h4>
        <p class="text-white-50">Follow us on our social media platforms:</p>
        <div class="d-flex justify-content-start">
          <a href="#" target="_blank" class="text-decoration-none text-light mx-2"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" target="_blank" class="text-decoration-none text-light mx-2"><i class="fa-brands fa-whatsapp"></i></a>
          <a href="#" target="_blank" class="text-decoration-none text-light mx-2"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" target="_blank" class="text-decoration-none text-light mx-2"><i class="fa-brands fa-x-twitter"></i></a>
        </div>
      </div>
    </div>

    <!-- Footer Bottom -->
    <div class="row text-center mt-4">
      <div class="col-12">
        <p class="text-white-50">&copy; 2024 CityTaxi. All Rights Reserved.</p>
      </div>
    </div>
  </div>
</div>

  <!-- Boostrap JS Files -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
</body>

</html>
