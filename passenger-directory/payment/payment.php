<?php
include('../../includes/connect.php');
session_start();

// echo $_SESSION['passengerUsername'];
// !Here Assume that Rs. 100 will charge per KM in taxi trip.
// Todo: When the page loads, It need to show all the details in this form to Payment Process.
// * Storyline:
// * 1. Need to get Reservation id from 'table_reservation' and get Driver & Passenger Name.
if (isset($_GET['reservation_id'])) {
    $parsedReservationId = $_GET['reservation_id'];

    // Construct a SQL query using JOIN with aliases for table names
    $query = "SELECT tr.*, tp.passenger_name, td.driver_name FROM `table_reservation` AS tr 
    LEFT JOIN `table_passenger` AS tp ON tr.passenger_id = tp.id
    LEFT JOIN `table_driver` AS td ON tr.driver_id = td.driver_id
    WHERE tr.reservation_id = $parsedReservationId";

    $getReservationData = mysqli_query($con, $query);
    $arrayOfReservationData = mysqli_fetch_assoc($getReservationData);

    $isDataExist = mysqli_num_rows($getReservationData);
    if ($isDataExist == 1) {
        $pickupLocation = $arrayOfReservationData['pickup_location'];
        $dropLocation = $arrayOfReservationData['drop_location'];
        $tripStartTime = $arrayOfReservationData['ride_start_time'];
        $passengerName = $arrayOfReservationData['passenger_name'];
        $driverName = $arrayOfReservationData['driver_name'];
        $driverId = $arrayOfReservationData['driver_id'];
    }
}

// Initialize default values
$tripDistanceInKM = 0;
$totalAmoutOfTrip = 0;

$API_URL = "https://maps.googleapis.com/maps/api/distancematrix/json?departure_time=now";
$apiKey = "AIzaSyBsJI5yvcBVkMmi4-jP0CqjZzHm4tgzrnE";  // Make sure to replace this with your actual API key

if (!empty($pickupLocation) && !empty($dropLocation)) {
    $requestURL = $API_URL . "&destinations=" . urlencode($dropLocation) . "&origins=" . urlencode($pickupLocation) . "&key=" . $apiKey;
    $responseOfAPI = file_get_contents($requestURL);

    if ($responseOfAPI === false) {
        echo "<script>alert('Unable to get the distance data.')</script>";
    } else {
        $distanceObj = json_decode($responseOfAPI);

        // Check if the response is valid and contains distance information
        if ($distanceObj->status == "OK" && isset($distanceObj->rows[0]->elements[0]->distance->value)) {
            $tripDistanceInMeter = $distanceObj->rows[0]->elements[0]->distance->value;
            $tripDistanceInKM = $tripDistanceInMeter / 1000; // Convert meters to kilometers
            $totalAmoutOfTrip = $tripDistanceInKM * 100; // Calculate amount (100 Rs per KM)
        } else {
            echo "<script>alert('Unable to calculate trip distance.')</script>";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "<script>window.open('checkout.php?reservation_id={$parsedReservationId}&distance={$tripDistanceInKM}&amount={$totalAmoutOfTrip}','_self')</script>";
}

?>
<!-- HTML Blocks -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Fav Icon -->
    <link rel="shortcut icon" href="../../assets/img/taxi-img.png" type="image/x-icon" class="object-fit-cover" />
    <title>Payment Page - CityTaxi</title>

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
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" />

    <!-- External CSS -->
    <link rel="stylesheet" href="../../assets/css/style.css" />
    <link rel="stylesheet" href="../../assets/css/style2.css" />
    <!-- <link rel="stylesheet" href="./assets/css/style3.css" /> -->
</head>

<body class="overflow-x-hidden bg-external-white">
        <a href="javascript:history.back()" class="btn btn-secondary mt-5 mx-5">
            Back
        </a>

    <!-- Body -->
    <main class="px-2 px-sm-3 px-md-5 pb-5 pt-5">
        <!-- Sign Up Form -->
            <form method="post" class="background-grey p-2 p-sm-3 p-md-5 rounded-2" id="passenger-reservation-form">
                <!-- Driver Name-->
                <div class="mb-3 w-100">
                    <label for="driver-name" class="form-label">Driver Name:
                    </label>
                    <div>
                        <label for="driver-name" class="form-control"><?php echo $driverName; ?> </label>
                    </div>
                </div>

                <div class="mb-3 w-100">
                    <label for="driver-id" class="form-label">Driver ID:
                    </label>
                    <div>
                        <label for="driver-id" class="form-control"> <?php echo $driverId; ?></label>
                    </div>
                </div>

                <div class="mb-3 w-100">
                    <label for="passenger-pickup-location" class="form-label">Pickup Location:
                    </label>
                    <div>
                        <label for="passenger-pickup-location" class="form-control text-capitalize"><?php echo $pickupLocation; ?>
                        </label>
                    </div>
                </div>

                <!-- Drop Location -->
                <div class="mb-3 w-100">
                    <label for="passenger-pickup-location" class="form-label">Drop Location:
                    </label>
                    <div>
                        <label for="passenger-pickup-location" class="form-control text-capitalize "><?php echo $dropLocation; ?>
                        </label>
                    </div>
                </div>

                <!-- Date and Time of Reservation -->
                <div class="mb-3 w-100">
                    <label for="passenger-pickup-location" class="form-label">Date:
                    </label>
                    <div>
                        <label for="passenger-pickup-location" class="form-control"><?php echo $tripStartTime; ?>
                        </label>
                    </div>
                </div>

                <!-- Amount -->
                <div class="mb-3 w-100">
                    <label for="passenger-pickup-location" class="form-label">Your total payment:
                    </label>
                    <div>
                        <label for="passenger-pickup-location" class="form-control">Rs. <?php echo $totalAmoutOfTrip; ?>.00
                        </label>
                    </div>
                </div>

                <div class="mb-2 w-100">
                    <input type="submit" value="Confirm Payment" class="btn btn-primary w-100 mt-3" />
                </div>
            </form>
        </div>
    </main>

    <!-- Boostrap JS Files -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    <!-- End -->
</body>

</html>