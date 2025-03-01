<?php
include('../includes/connect.php');

if (isset($_GET['operator_id'])) {
    $parsedOperatorId = intval($_GET['operator_id']);

    $getOperatorReservedTrip = mysqli_query($con, "SELECT * FROM `table_reservation` WHERE operator_id = $parsedOperatorId AND driver_id = 0");
    $isDetailExist = mysqli_num_rows($getOperatorReservedTrip);

    $pickupLocation = null;
    $reservationId = null;
    ?>

    <div class="position-relative">
        <a href="reservation-delete-process.php?operator_id=<?php echo $parsedOperatorId; ?>" class="btn btn-primary my-5 mx-5">Cancel</a>

        <div class="row row-cols-1 row-cols-md-1">

            <?php
            if ($isDetailExist == 1) {
                $getReserveDetail = mysqli_fetch_assoc($getOperatorReservedTrip);
                $pickupLocation = $getReserveDetail['pickup_location'];
                $reservationId = $getReserveDetail['reservation_id'];
            } else {
                echo "<p>No reservation found.</p>";
                exit;
            }

            $getDriverListBasedOnLocation = mysqli_query($con, "SELECT * FROM `table_driver` WHERE driver_city = '$pickupLocation' AND availability_status = 'available'");
            $isDriverExist = mysqli_num_rows($getDriverListBasedOnLocation);
            
            if ($isDriverExist > 0) {
                while ($arrayOfDriversList = mysqli_fetch_assoc($getDriverListBasedOnLocation)) {
                    $driverId = $arrayOfDriversList['driver_id'];
                    $driverName = $arrayOfDriversList['driver_name'];
                    $driverAddressLine = $arrayOfDriversList['driver_address_line'];
                    $driverImage = $arrayOfDriversList['driver_image'];
                    $driverPhoneNo = $arrayOfDriversList['driver_phone_no'];
                    $taxiNo = $arrayOfDriversList['taxi_number'];
                    $locationLatitude = $arrayOfDriversList['location_latitude'];
                    $locationLongitude = $arrayOfDriversList['location_longitude'];
                    ?>
                    <div class="container mb-5">
                        <div class="card mx-auto border rounded-3" style="max-width: 900px;">
                            <div class="row g-0">
                                <div class="col-md-4 text-center d-flex align-items-center justify-content-center">
                                    <img src="../sign-up/driver-profile-picture/<?php echo htmlspecialchars($driverImage); ?>" 
                                         class="rounded-circle border border-3 img-fluid" 
                                         style="max-width: 200px; height: auto; object-fit: cover;" 
                                         alt="Driver Profile Picture" />
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span class="fw-bold">Name:</span>
                                                <span><?php echo htmlspecialchars($driverName); ?></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span class="fw-bold">Address:</span>
                                                <span><?php echo htmlspecialchars($driverAddressLine); ?></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span class="fw-bold">Taxi No:</span>
                                                <span><?php echo htmlspecialchars($taxiNo); ?></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span class="fw-bold">Contact No:</span>
                                                <a href="tel:<?php echo htmlspecialchars($driverPhoneNo); ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($driverPhoneNo); ?>
                                                </a>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span class="fw-bold">Distance:</span>
                                                <span id="distance-<?php echo $driverId; ?>">Calculating...</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span class="fw-bold">Closest:</span>
                                                <span id="closest-<?php echo $driverId; ?>" class="text-success fw-bold"></span>
                                            </li>
                                        </ul>
                                        <div class="mt-3">
                                            <a href="confirmation-page.php?driver_id=<?php echo $driverId; ?>&reservation_id=<?php echo $reservationId; ?>" class="btn btn-primary w-100">Select Driver</a>
                                            <a href="https://www.google.com/maps?q=<?php echo $locationLatitude; ?>,<?php echo $locationLongitude; ?>" target="_blank" class="btn btn-primary w-100 mt-2">Show Location</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>No drivers available in your location.</p>";
            }
        }
        ?>
        </div>
    </div>

    <script>
        let distances = []; // Array to store distances

        async function getRoadDistance(start, end, driverId) {
            const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${start.lon},${start.lat};${end.lon},${end.lat}?overview=false`;
            console.log(`Fetching distance for Driver ID ${driverId}: ${osrmUrl}`);
            try {
                const response = await fetch(osrmUrl);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                if (data.routes && data.routes.length > 0) {
                    const distance = data.routes[0].distance / 1000; // Convert from meters to kilometers
                    distances.push({ driverId, distance });
                    return distance;
                } else {
                    console.error(`No routes found for Driver ID ${driverId}`);
                }
            } catch (error) {
                console.error('Error fetching road distance:', error);
            }
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var userLatitude = position.coords.latitude;
                var userLongitude = position.coords.longitude;

                const location1 = { lat: userLatitude, lon: userLongitude };

                <?php
                mysqli_data_seek($getDriverListBasedOnLocation, 0); // Reset the pointer to the start of the result set
                while ($arrayOfDriversList = mysqli_fetch_assoc($getDriverListBasedOnLocation)) {
                    $driverId = $arrayOfDriversList['driver_id'];
                    $locationLatitude = $arrayOfDriversList['location_latitude'];
                    $locationLongitude = $arrayOfDriversList['location_longitude'];
                ?>
                    (function(driverId, lat, lon) {
                        const location2 = { lat: lat, lon: lon }; 
                        getRoadDistance(location1, location2, driverId).then(distance => {
                            if (distance) {
                                console.log(`Distance to driver ${driverId}: ${distance.toFixed(2)} km`);
                                document.getElementById(`distance-<?php echo $driverId; ?>`).innerText = distance.toFixed(2) + ' km';

                                // Check for closest driver after all distances are calculated
                                if (distances.length === <?php echo $isDriverExist; ?>) {
                                    let closestDriver = distances.reduce((prev, curr) => (prev.distance < curr.distance) ? prev : curr);
                                    document.getElementById(`closest-${closestDriver.driverId}`).innerText = "Closest Driver!";
                                }
                            }
                        });
                    })(<?php echo $driverId; ?>, <?php echo $locationLatitude; ?>, <?php echo $locationLongitude; ?>);
                <?php
                }
                ?>
            });
        } else {
            console.error("Geolocation is not supported by this browser.");
        }
    </script>

<?php
?>
