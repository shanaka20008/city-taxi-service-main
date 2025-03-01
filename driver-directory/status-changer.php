<?php
include('../includes/connect.php');

if (isset($_GET['driverId'])) {
    $parsedDriverId = $_GET['driverId'];

    $fetchParsedDriverIdData = mysqli_query($con, "SELECT * FROM `table_driver` WHERE driver_id = $parsedDriverId");
    $isParsedDriverIdExist = mysqli_num_rows($fetchParsedDriverIdData);

    if ($isParsedDriverIdExist > 0) {
        $arrayOfParsedDriverIdData = mysqli_fetch_assoc($fetchParsedDriverIdData);

        $driverAvailabilityStatus = $arrayOfParsedDriverIdData['availability_status'];

        if ($driverAvailabilityStatus == "available") {

            $updateStatusAsBusy = mysqli_query($con, "UPDATE `table_driver` SET availability_status = 'busy' WHERE driver_id = $parsedDriverId");
            $isRowsAffected = mysqli_affected_rows($con);

            if ($isRowsAffected == 1) {
                $driverName = $arrayOfParsedDriverIdData['driver_name'];
                echo "<script>window.open('homepage.php?profile','_self')</script>";
            }
        } else {


            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Check if latitude and longitude are set
                if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
                    $driverId = intval($_POST['driverId']);
                    $latitude = floatval($_POST['latitude']);
                    $longitude = floatval($_POST['longitude']);
    
                    // Update the location in the database
                    $updateLocationQuery = "UPDATE `table_driver` SET location_latitude = $latitude, location_longitude = $longitude WHERE driver_id = $driverId";
                    if (mysqli_query($con, $updateLocationQuery)) {
                        $updateStatusAsAvailable = mysqli_query($con, "UPDATE `table_driver` SET availability_status = 'available' WHERE driver_id = $parsedDriverId");
                        echo "<script>window.open('homepage.php?profile','_self')</script>";
                        exit; // Exit after handling the form submission
                    } else {
                        echo "Error updating location: " . mysqli_error($con);
                    }
                }
            } else {
                // HTML form to submit latitude and longitude
                echo '
                <form id="locationForm" method="POST" action="">
                    <input type="hidden" name="driverId" value="' . htmlspecialchars($parsedDriverId) . '">
                    <input type="hidden" id="latitude" name="latitude" value="">
                    <input type="hidden" id="longitude" name="longitude" value="">
                </form>
                ';
    
                // JavaScript to get user's location
                echo "
                <script>
                function updateLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var latitude = position.coords.latitude;
                            var longitude = position.coords.longitude;
                            document.getElementById('latitude').value = latitude;
                            document.getElementById('longitude').value = longitude;
                            document.getElementById('locationForm').submit();
                        }, function() {
                            alert('Unable to retrieve your location.');
                        });
                    } else {
                        alert('Geolocation is not supported by this browser.');
                    }
                }
                updateLocation();
                </script>
                ";
            }

                // Redirect after updating
              

        }
    }
}
