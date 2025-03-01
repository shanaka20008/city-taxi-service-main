<?php
// Add PHPMailer dependencies
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

include('../includes/connect.php');
include('../includes/function.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ip = get_IP_address();
    $locationAPI = file_get_contents("http://ip-api.com/json/$ip");
    $locationObj = json_decode($locationAPI);
    $latitudeValue = $locationObj->lat;
    $longitudeValue = $locationObj->lon;
    $countryName = $locationObj->country;

    $driverNameEl = $_POST['driver-name'];
    $driverEmailEl = $_POST['driver-email'];
    $driverPhoneNoEl = $_POST['driver-phone-no'];
    $driverUsernameEl = $_POST['driver-username'];
    $passwordEl = $_POST['password'];
    $encryptedPassword = password_hash($passwordEl, PASSWORD_DEFAULT);
    $confirmPasswordEl = $_POST['confirm-password'];
    $driverTaxiNumberEl = $_POST['taxi-number'];
    $driverAddressLineEl = $_POST['driver-address-line-1'];
    $driverCityNameEl = $_POST['driver-city-name'];
    $driverCountryNameEl = $_POST['driver-country-name'];
    $driverIdCardNoEl = $_POST['driver-id-card-no'];
    $locationLatitudeEl = $latitudeValue;
    $locationLongitudeEl = $longitudeValue;
    $driverWorkStartTimeEl = $_POST['work-start-time'];
    $driverWorkEndingTimeEl = $_POST['work-ending-time'];

    // Driver Image upload
    $driverImageEl = $_FILES['driver-image']['name'];
    $tempDriverImage = $_FILES['driver-image']['tmp_name'];
    $availabilityStatus = "available";

    $filterByPhoneNumber = mysqli_query($con, "SELECT * FROM `table_driver` WHERE driver_phone_no = $driverPhoneNoEl");
    $isPhoneNumberExist = mysqli_num_rows($filterByPhoneNumber);

    $filterByEmail = mysqli_query($con, "SELECT * FROM `table_driver` WHERE driver_email = '$driverEmailEl'");
    $isEmailExist = mysqli_num_rows($filterByEmail);

    $filterByUsername = mysqli_query($con, "SELECT * FROM `table_driver` WHERE driver_username = '$driverUsernameEl'");
    $isUsernameExist = mysqli_num_rows($filterByUsername);

    if ($isPhoneNumberExist > 0) {
        echo "<script>alert('The phone number already exists! Try with another phone number.')</script>";
    } elseif ($isEmailExist > 0) {
        echo "<script>alert('The Email already exists!')</script>";
    } elseif ($isUsernameExist > 0) {
        echo "<script>alert('The Username already exists!')</script>";
    } else {
        move_uploaded_file($tempDriverImage, "./driver-profile-picture/$driverImageEl");

        $query = "INSERT INTO `table_driver` (
          driver_name, driver_email, driver_phone_no, driver_id_card_no, driver_username, driver_password, taxi_number,
          availability_status, location_latitude, location_longitude, start_time, end_time, driver_address_line,
          driver_city, driver_country, driver_image)
          VALUES (
            '$driverNameEl', '$driverEmailEl', '$driverPhoneNoEl', '$driverIdCardNoEl', '$driverUsernameEl',
            '$encryptedPassword', '$driverTaxiNumberEl', '$availabilityStatus', '$locationLatitudeEl', '$locationLongitudeEl',
            '$driverWorkStartTimeEl', '$driverWorkEndingTimeEl', '$driverAddressLineEl', '$driverCityNameEl', '$driverCountryNameEl', '$driverImageEl')";

        $insertDriverData = mysqli_query($con, $query);
        if ($insertDriverData) {
            echo "<script>alert('Account Created Successfully.')</script>";

            // PHPMailer - Send email with username and password
            $mail = new PHPMailer(true);

            $userMail = "janithbandara001@gmail.com";  // Admin mail id
            $passKey = "dhpcycmnlkcqwysm";  // SMTP password

            //$mail->SMTPDebug = 3;                               // Enable verbose debug output

            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $userMail;                          // SMTP username
            $mail->Password = $passKey;                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom($userMail);
            $mail->addAddress($driverEmailEl, $driverNameEl); // Add recipient

            $mail->isHTML(true);                                // Set email format to HTML
            $mail->Subject = "Credentials for {$driverNameEl}";
            $mail->Body    = "Username: {$driverUsernameEl} <br> Password: {$passwordEl}";

            $mail->send();
            echo "<script>alert('Please check your email.')</script>";
            echo "<script>window.open('../driver-directory/login.php','_self')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Fav Icon -->
  <link rel="shortcut icon" href="../assets/img/taxi-img.png" type="image/x-icon" class="object-fit-cover" />
  <title>Driver's Sign-Up Page - CityTaxi</title>

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
</head>
<!-- <a href=""></a> -->

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
              <a class="nav-link active text-white rounded hover-effect me-4 px-3 fw-semibold" aria-current="page" href="../index.php">Home</a>
            </li>
          </ul>
          <!-- <a href=""></a> -->

          <!-- Sign-Up Button -->
          <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle fw-bold" data-bs-toggle="dropdown" aria-expanded="false">
              Sign Up
            </button>
            <ul class="dropdown-menu background-black-color mt-2 p-2">
              <li class="">
                <a class="dropdown-item text-light hover-effect" href="./customer.php">Passenger</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <!-- End -->

  <!-- Body -->
  <main class="px-2 px-sm-3 px-md-5 pb-5">
    <h3 class="text-center fw-semibold mt-5 font-black p-3 rounded-3">
      Create an account - Driver
    </h3>

    <!-- Sign Up Form -->
    <div class="mt-md-5">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="background-grey p-2 p-sm-3 p-md-5 rounded-2" id="driver-signup-form" enctype="multipart/form-data">
          <!-- Name -->
          <div class="mb-3 w-100">
            <label for="driver-name" class="form-label font-bold-weight">Name<span class="text-danger">*</span></label>
            <div>
              <input type="text" name="driver-name" class="form-control shadow-none text-capitalize" id="driver-name" required="required" />
            </div>
          </div>

          <!-- Email -->
          <div class="mb-3 w-100">
            <label for="driver-email" class="form-label font-bold-weight">Email<span class="text-danger">*</span></label>
            <div>
              <input type="email" name="driver-email" class="form-control shadow-none" id="driver-email" required="required" />
            </div>
          </div>

          <!-- Phone Number -->
          <div class="mb-3 w-100">
            <label for="driver-phone-no" class="form-label font-bold-weight">Phone Number<span class="text-danger">*</span></label>
            <div>
              <input type="text" name="driver-phone-no" class="form-control shadow-none" id="driver-phone-no" required="required" />
            </div>
          </div>

        <!-- Username, Password, Confirm Password and Taxi-Number -->
          <!-- Username -->
          <div class="mb-3 w-100">
            <label for="driver-username" class="form-label font-bold-weight">Username<span class="text-danger">*</span></label>
            <input type="text" name="driver-username" class="form-control shadow-none" id="driver-username" required="required" autocomplete="off" />
          </div>

          <!-- Password -->
          <div class="mb-3 w-100">
            <label for="password" class="form-label font-bold-weight">Password<span class="text-danger">*</span></label>
            <div class="d-flex bg-light align-items-center justify-content-center rounded">
              <input name="password" class="form-control shadow-none" id="password" required="required" />
            </div>
          </div>

          <!-- Confirm Password -->
          <div class="mb-3 w-100">
            <label for="confirm-password" class="form-label font-bold-weight">Confirm Password<span class="text-danger">*</span></label>
            <div class="d-flex bg-light align-items-center justify-content-center rounded">
              <input name="confirm-password" class="form-control shadow-none" id="confirm-password" required="required" />
            </div>
          </div>

          <!-- Taxi Number -->
          <div class="mb-3 w-100">
            <label for="taxi-number" class="form-label font-bold-weight">Taxi Number<span class="text-danger">*</span></label>
            <div>
              <input type="text" name="taxi-number" class="form-control shadow-none" id="taxi-number" required="required" autocomplete="off" />
            </div>
          </div>

        <!-- Address -->
          <!-- Address Line 1 -->
          <div class="mb-3 w-100">
            <label for="driver-address-line-1" class="form-label font-bold-weight">Address Line 1<span class="text-danger">*</span></label>
            <div>
              <input type="text" name="driver-address-line-1" class="form-control shadow-none text-capitalize" id="driver-address-line-1" required="required" autocomplete="off" />
            </div>
          </div>

          <!-- City -->
          <div class="mb-3 w-100">
            <label for="driver-city-name" class="form-label font-bold-weight">City<span class="text-danger">*</span></label>
            <div>
              <input type="text" name="driver-city-name" class="form-control shadow-none text-capitalize" id="driver-city-name" value="" required="required" autocomplete="off" />
            </div>
          </div>

          <!-- Country -->
          <div class="mb-3 w-100">
            <label for="driver-country-name" class="form-label font-bold-weight">Country<span class="text-danger">*</span></label>
            <div>
              <input type="text" name="driver-country-name" class="form-control shadow-none text-capitalize" id="driver-country-name" required="required" autocomplete="off" />
            </div>
          </div>

        <!-- ID Card, Profile Image, Available Time Period -->
          <!-- Driver Image -->
          <div class="mb-3 w-100 ">
            <label for="driver-image" class="form-label">Profile Image<span class="text-danger">*</span></label>
            <div>
              <input class="form-control" type="file" id="driver-image" name="driver-image">
            </div>
          </div>

          <!-- ID Card -->
          <div class="mb-3 w-100">
            <label for="driver-id-card-no" class="form-label font-bold-weight">ID Card No.<span class="text-danger">*</span></label>
            <div>
              <input type="text" name="driver-id-card-no" class="form-control shadow-none" id="driver-id-card-no" required="required" autocomplete="off" />
            </div>
          </div>

          <!-- Available Time Period -->
          <div class="mb-3 w-100">
            <label for="work-start-time" class="form-label font-bold-weight">Available Time Period<span class="text-danger">*</span></label>

            <!-- From & To -->
            <div class="d-md-flex align-items-center gap-2">
              <div class="d-md-flex gap-2 w-100">
                <label for="work-starting-time" class="form-label">From: </label>
                <div class="w-100">
                  <input type="time" name="work-start-time" class="form-control shadow-none" id="work-start-time" required="required" />
                </div>
              </div>

              <div class="d-md-flex gap-2 w-100">
                <label for="work-ending-time" class="form-label">To: </label>
                <div class="w-100">
                  <input type="time" name="work-ending-time" class="form-control shadow-none" id="work-ending-time" required="required" />
                </div>
              </div>
            </div>




        </div>
        <!-- Create Account Button -->
        <input type="submit" name="btnCreateDriverAccount" class="btn btn-primary mt-3 mb-3 mt-md-4 w-100 fs-5 fw-bold p-2" value="Sign Up">
      </form>
    </div>
  </main>

  <!-- Boostrap JS Files -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
  <!-- End -->

  <!-- JavaScript Validation for Inputs -->
  <script>
    const driverSignupFormEl = document.querySelector("#driver-signup-form");
    const validator = new window.JustValidate(driverSignupFormEl);

    // console.log("hi");
    // console.log(validator.addField);

    validator.addField(
      "#driver-name",
      [{
          rule: "required",
        },
        {
          rule: "minLength",
          value: 3,
        },
        {
          rule: "maxLength",
          value: 20,
        },
      ], {
        errorLabelCssClass: ["error-msg-margin"],
      }
    );

    validator.addField(
      "#taxi-number",
      [{
          rule: "required",
        },
        {
          rule: "minLength",
          value: 10,
        },
        {
          rule: "maxLength",
          value: 15,
        },
      ], {
        errorLabelCssClass: ["error-msg-margin"],
      }
    );

    validator.addField(
      "#driver-email",
      [{
          rule: "required",
        },
        {
          rule: "email",
        },
      ], {
        errorLabelCssClass: ["error-msg-margin"],
      }
    );

    validator.addField(
      "#driver-phone-no",
      [{
          rule: "required",
        },
        {
          rule: "minLength",
          value: 12,
        },
        {
          rule: "maxLength",
          value: 15,
        },
      ], {
        errorLabelCssClass: ["error-msg-margin"],
      }
    );

    validator.addField(
      "#driver-username",
      [{
          rule: "required",
        },
        {
          rule: "minLength",
          value: 3,
        },
        {
          rule: "maxLength",
          value: 10,
        },
      ], {
        errorLabelCssClass: ["error-msg-margin"],
      }
    );

    validator.addField(
      "#password",
      [{
          rule: "required",
        },
        {
          rule: "minLength",
          value: 8,
        },
        {
          rule: "maxLength",
          value: 12,
        },
      ], {
        errorLabelCssClass: ["error-msg-margin"],
      }
    );

    validator.addField(
      "#confirm-password",
      [{
          rule: "required",
        },
        {
          validator: (value, fields) => {
            if (fields["#password"] && fields["#password"].elem) {
              const repeatPasswordValue = fields["#password"].elem.value;

              return value === repeatPasswordValue;
            }

            return true;
          },
          errorMessage: "Passwords should be the same",
        },
      ], {
        errorLabelCssClass: ["error-msg-margin"],
      }
    );

    validator.addField(
      "#driver-address-line-1",
      [{
        rule: "required",
      }, ], {
        errorLabelCssClass: ["error-msg-margin"],
      }
    );

    validator.addField(
      "#driver-city-name",
      [{
        rule: "required",
      }, ], {
        errorLabelCssClass: ["error-msg-margin"],
      }
    );

    validator.addField(
      "#driver-country-name",
      [{
        rule: "required",
      }, ], {
        errorLabelCssClass: ["error-msg-margin"],
      }
    );

    validator.addField(
      "#driver-id-card-no",
      [{
          rule: "required",
        },
        {
          rule: "minLength",
          value: 12,
        },
        {
          rule: "maxLength",
          value: 12,
        },
      ], {
        errorLabelCssClass: ["error-msg-margin"],
      }
    );

    validator.addField("#driver-image", [{
        rule: 'minFilesCount',
        value: 1,
      },
      {
        rule: 'maxFilesCount',
        value: 1,
      },

    ], {
      errorLabelCssClass: ["error-msg-margin"],
    });


    validator.addField("#work-start-time", [{
      rule: 'required',
    }], {
      errorLabelCssClass: ["error-msg-margin"],
    });

    validator.addField("#work-ending-time", [{
      rule: 'required'
    }], {
      errorLabelCssClass: ["error-msg-margin"],
    });

    validator.onSuccess(() => {
      const signUpFormEl = document.getElementById("driver-signup-form");
      signUpFormEl.submit();
    });
  </script>

  <!-- JavaScript Code to Show / Hide Password -->
  <script>
    const eyeClosedIconEl = document.getElementById("eye-closed-icon");
    const eyeClosedIconForConfirmPasswordEl = document.getElementById("eye-closed-icon-conf");
    const adminPasswordEl = document.querySelector("#password");
    const confirmPasswordEl = document.querySelector("#confirm-password");

    eyeClosedIconEl.onclick = function() {
      if (adminPasswordEl.type === "password") {
        adminPasswordEl.type = "text";
        eyeClosedIconEl.innerHTML = `<i class="fa-regular fa-eye"></i>`;
      } else {
        adminPasswordEl.type = "password";

        eyeClosedIconEl.innerHTML = `<i class="fa-regular fa-eye-slash"></i>`;
      }
    };

    eyeClosedIconForConfirmPasswordEl.onclick = function() {
      if (confirmPasswordEl.type === "password") {
        confirmPasswordEl.type = "text";
        eyeClosedIconForConfirmPasswordEl.innerHTML = `<i class="fa-regular fa-eye"></i>`;
      } else {
        confirmPasswordEl.type = "password";

        eyeClosedIconForConfirmPasswordEl.innerHTML = `<i class="fa-regular fa-eye-slash"></i>`;
      }
    }
  </script>
</body>

</html>


<?php

// echo var_dump($userDestinationLatitude);
?>