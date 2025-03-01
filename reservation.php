<?php
include('./includes/connect.php');
include('./includes/function.php');
session_start();

$sessionPassengerUsername = $_SESSION['passengerUsername'];

$getUsernameFromDB = mysqli_query($con, "SELECT passenger_name FROM `table_passenger` WHERE passenger_username = '$sessionPassengerUsername'");
$arrayOfPassengerUsername = mysqli_fetch_assoc($getUsernameFromDB);

$isUsernameExist = mysqli_num_rows($getUsernameFromDB);
if ($isUsernameExist == 1) {
  $passengerName = $arrayOfPassengerUsername['passenger_name'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./assets/img/city-taxi-favicon.png" type="image/x-icon" class="object-fit-cover" />
    <title>All Drivers - CityTaxi</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/css/style.css" />
</head>

<body class="overflow-x-hidden bg-light">
<a href="javascript:history.back()" class="btn btn-secondary mt-5 mx-5">Back</a>
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8">
                <div id="map" class="rounded" style="height: 400px;"></div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                <form method="post" id="passenger-reservation-form">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col">
                                <input type="text" id="search-pickup" name="search-pickup" class="form-control" placeholder="Pickup location" />
                            </div>
                            <div class="col-auto">
                                <button id="search-pickup-button" class="btn btn-primary">Add</button>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col">
                                <input type="text" id="search-drop" name="search-drop" class="form-control" placeholder="Drop location" />
                            </div>
                            <div class="col-auto">
                                <button id="search-drop-button" class="btn btn-primary">Add</button>
                            </div>
                        </div>
                    </div>
                    
                        <div class="card-body">                            
                            <div style="display:none">
                                <div class="mb-1">
                                    <input id="pickup-lat" type="text" class="form-control" placeholder="Pickup Latitude" name="pickup-location-latitude" readonly/>
                                </div>
                                <div class="mb-1">
                                    <input id="pickup-lng" type="text" class="form-control" placeholder="Pickup Longitude" name="pickup-location-longitude" readonly/>
                                </div>
                                <div class="mb-1">
                                    <input id="dropoff-lat" type="text" class="form-control" placeholder="Drop-off Latitude" name="drop-location-latitude" readonly/>
                                </div>
                                <div class="mb-1">
                                    <input id="dropoff-lng" type="text" class="form-control" placeholder="Drop-off Longitude" name="drop-location-longitude" readonly/>
                                </div>
                            </div>
                            <div class="mb-1">
                                <span><b>Distance (KM)</b></span>
                                <input type="text" id="distance" class="form-control" readonly/>
                            </div>
                            <div class="mb-2">
                                <span><b>Approx. Amount (RS.)</b></span>
                                <input type="text" id="amount" class="form-control" readonly/>
                            </div>
                            <div class="mb-3">
                                <span><b>Date & Time</b></span>
                                <input type="datetime-local" class="form-control" id="date-and-time-of-reservation" name="date-and-time-of-reservation" required="required" />
                            </div>
                            <input type="submit" id="confirm" class="btn btn-primary w-100" value="Confirm">
                        </div>    
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
let map;
let pickupMarker;
let dropoffMarker;

function initMap() {
 // Try to get the user's location
 if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            map = L.map('map').setView([userLat, userLng], 13); // Set map to user's location

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Create a marker for the user's location
            const currentMarker = L.marker([userLat, userLng]).addTo(map).bindPopup("Your Location").openPopup();

        }, (error) => {
            console.error("Geolocation error:", error);
            handleLocationError(true);
        }, {
            enableHighAccuracy: true // Request high accuracy if available
        });
    } else {
        handleLocationError(false);
    }
}

function searchLocation(query, isPickup) {
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                const lat = data[0].lat;
                const lon = data[0].lon;
                map.setView([lat, lon], 13);
                
                // Remove existing markers
                if (isPickup && pickupMarker) {
                    map.removeLayer(pickupMarker);
                } else if (!isPickup && dropoffMarker) {
                    map.removeLayer(dropoffMarker);
                }

                const marker = L.marker([lat, lon]).addTo(map);
                if (isPickup) {
                    pickupMarker = marker;
                    document.getElementById("pickup-lat").value = lat;
                    document.getElementById("pickup-lng").value = lon;

                    // Calculate distance if drop-off is already set
                    if (dropoffMarker) {
                        calculateDistance();
                    }
                } else {
                    dropoffMarker = marker;
                    document.getElementById("dropoff-lat").value = lat;
                    document.getElementById("dropoff-lng").value = lon;

                    // Calculate distance if pickup is already set
                    if (pickupMarker) {
                        calculateDistance();
                    }
                }
            } else {
                alert("Location not found");
            }
        });
}

document.getElementById("search-pickup-button").addEventListener("click", () => {
    const query = document.getElementById("search-pickup").value;
    searchLocation(query, true);
});

document.getElementById("search-drop-button").addEventListener("click", () => {
    const query = document.getElementById("search-drop").value;
    searchLocation(query, false);
});

function calculateDistance() {
    const lat1 = parseFloat(document.getElementById("pickup-lat").value);
    const lng1 = parseFloat(document.getElementById("pickup-lng").value);
    const lat2 = parseFloat(document.getElementById("dropoff-lat").value);
    const lng2 = parseFloat(document.getElementById("dropoff-lng").value);

    if (!isNaN(lat1) && !isNaN(lng1) && !isNaN(lat2) && !isNaN(lng2)) {
        const osrmUrl = `http://router.project-osrm.org/route/v1/driving/${lng1},${lat1};${lng2},${lat2}?overview=false`;
        
        fetch(osrmUrl)
            .then(response => response.json())
            .then(data => {
                if (data.routes && data.routes.length > 0) {
                    const distance = data.routes[0].distance / 1000; // Convert from meters to kilometers
                    document.getElementById("distance").value = distance.toFixed(2); // Set distance in input
                    let amount = distance.toFixed(2) * 100;
                    let roundedAmount = Math.round(amount);
                    document.getElementById("amount").value = roundedAmount;
                } else {
                    alert("Route not found");
                }
            })
            .catch(error => console.error('Error:', error));
    }
}

window.onload = initMap;

    </script>
</body>
</html>

<?php
if (isset($_GET['driverId'])) {
    if (isset($_GET['passengerId'])) {
        $parsedDriverId = $_GET['driverId'];
        $parsedPassengerId = $_GET['passengerId'];

        $getPassengerDet = mysqli_query($con, "SELECT passenger_name, passenger_phone_no FROM `table_passenger` WHERE id = $parsedPassengerId");
        $isPassengerDetailExist = mysqli_num_rows($getPassengerDet);

        if ($isPassengerDetailExist > 0) {
            $arrayOfPassengerDetails = mysqli_fetch_assoc($getPassengerDet);
            $passengerName = $arrayOfPassengerDetails['passenger_name'];
            $passengerContactNo = $arrayOfPassengerDetails['passenger_phone_no'];
        }

        $getDriverDet = mysqli_query($con, "SELECT driver_name, taxi_number, driver_phone_no FROM `table_driver` WHERE driver_id = $parsedDriverId");
        if (mysqli_num_rows($getDriverDet) > 0) {
            $arrayOfDriverDet = mysqli_fetch_assoc($getDriverDet);
            $driverName = $arrayOfDriverDet['driver_name'];
            $taxiNumber = $arrayOfDriverDet['taxi_number'];
            $driverContactNum = $arrayOfDriverDet['driver_phone_no'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pickupLocation = $_POST['search-pickup'];
    $dropLocation = $_POST['search-drop'];
    $pickupLocationLatitude = $_POST['pickup-location-latitude'];
    $pickupLocationLongitude = $_POST['pickup-location-longitude'];
    $dropLocationLatitude = $_POST['drop-location-latitude'];
    $dropLocationLongitude = $_POST['drop-location-longitude'];
    $dateAndTimeOfReservationEl = $_POST['date-and-time-of-reservation'];
    $reservationStatus = "on process";
    $operatorId = 0;     

    $makeReserveTaxi = mysqli_query($con,
        "INSERT INTO `table_reservation` 
        (
          passenger_name,
          passenger_contact_no,
          pickup_location,
          drop_location,
          pickup_location_latitude_value,
          pickup_location_longitude_value,
          drop_location_latitude_value,
          drop_location_longitude_value,
          reservation_status,
          driver_id,
          passenger_id,
          ride_start_time,
          operator_id
        ) 
        VALUES 
        (
          '$passengerName',
          '$passengerContactNo',
          '$pickupLocation',
          '$dropLocation',
          '$pickupLocationLatitude',
          '$pickupLocationLongitude',
          '$dropLocationLatitude',
          '$dropLocationLongitude',
          '$reservationStatus',
          $parsedDriverId,
          $parsedPassengerId,
          '$dateAndTimeOfReservationEl',
          $operatorId
        )"
    );

    if ($makeReserveTaxi) {
        echo "<script>alert('Hi $passengerName! Your Reservation is confirmed. You will receive a confirmation message shortly.')</script>";

        // Call sendSMS function after successful reservation
        sendSMS($passengerContactNo, $passengerName, $driverName, $taxiNumber, $driverContactNum, $infobipBaseUrl, $infobipApiKey); 

        // Redirect to passenger homepage
        echo "<script>window.open('./passenger-directory/passenger-homepage.php?history','_self')</script>";
    }
}
?>
