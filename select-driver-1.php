<?php
include('./includes/connect.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Fav Icon -->
  <link rel="shortcut icon" href="./assets/img/city-taxi-favicon.png" type="image/x-icon" />
  <title>Available Drivers - CityTaxi</title>

  <!-- Google Font (Sen) -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Sen:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="./assets/css/style.css" />

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>

<body class="overflow-x-hidden bg-external-white">
    <a href="javascript:history.back()" class="btn btn-secondary mt-5 mx-5">Back</a>

      <?php
      $fetchAvailableDriversData = mysqli_query($con, "SELECT * FROM `table_driver` WHERE availability_status = 'available'");
      $isAvailableDriversExist = mysqli_num_rows($fetchAvailableDriversData);

      if ($isAvailableDriversExist > 0) {
        $driversData = [];

        while ($arrayOfAvailableDrivers = mysqli_fetch_assoc($fetchAvailableDriversData)) {
          $driverId = $arrayOfAvailableDrivers['driver_id'];
          $driverName = $arrayOfAvailableDrivers['driver_name'];
          $driverAddressLine = $arrayOfAvailableDrivers['driver_address_line'];
          $driverCity = $arrayOfAvailableDrivers['driver_city'];
          $driverCountry = $arrayOfAvailableDrivers['driver_country'];
          $driver_image = $arrayOfAvailableDrivers['driver_image'];
          $driverPhoneNo = $arrayOfAvailableDrivers['driver_phone_no'];
          $locationLatitude = $arrayOfAvailableDrivers['location_latitude'];
          $locationLongitude = $arrayOfAvailableDrivers['location_longitude'];
          $startTime = $arrayOfAvailableDrivers['start_time'];
          $endTime = $arrayOfAvailableDrivers['end_time'];

          $driversData[] = [
            'id' => $driverId,
            'lat' => $locationLatitude,
            'lng' => $locationLongitude
          ];
      ?>
          <div class="container my-5">
            <div class="card mx-auto border rounded-3" style="max-width: 900px;">
              <div class="row g-0">
                <div class="col-md-4 text-center p-4 d-flex align-items-center justify-content-center">
                  <img src="./sign-up/driver-profile-picture/<?php echo $driver_image; ?>" 
                       class="rounded-circle border border-3 img-fluid" 
                       style="max-width: 200px; height: auto; object-fit: cover;" 
                       alt="<?php echo $driverName; ?>'s Picture" />
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Name:</span>
                        <span><?php echo $driverName; ?></span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Address:</span>
                        <span><?php echo $driverAddressLine . ", " . $driverCity . ", " . $driverCountry; ?></span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Contact No:</span>
                        <a href="tel:<?php echo $driverPhoneNo; ?>" class="text-decoration-none">
                          <?php echo $driverPhoneNo; ?>
                        </a>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Start Time:</span>
                        <span><?php echo $startTime; ?></span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">End Time:</span>
                        <span><?php echo $endTime; ?></span>
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
                      <?php
                      if (!isset($_SESSION['passengerUsername'])) {
                        echo "<a href='./passenger-directory/passenger-login.php' class='btn btn-primary w-100'>Reserve for Ride</a>";
                      } else {
                        $sessionPassengerUsername = $_SESSION['passengerUsername'];
                        $fetchPassengersAllDetailFromDB = mysqli_query($con, "SELECT * FROM `table_passenger` WHERE passenger_username = '$sessionPassengerUsername'");
                        $arrayOfSessionPassenger = mysqli_fetch_assoc($fetchPassengersAllDetailFromDB);
                        $isSessionPassengerExist = mysqli_num_rows($fetchPassengersAllDetailFromDB);

                        if ($isSessionPassengerExist > 0) {
                          $passengerId = $arrayOfSessionPassenger['id'];
                          echo "<a href='./reservation.php?driverId=$driverId&passengerId=$passengerId' class='btn btn-primary w-100'>Reserve for Ride</a>";
                        }
                      }
                      ?>
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
        echo "<h4 class='text-center mx-auto text-danger mt-5'>Sorry! We can't find any available drivers at this moment... <box-icon name='sad' color='#dc3545' animation='flashing'></box-icon></h4>";
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
            mysqli_data_seek($fetchAvailableDriversData, 0); // Reset the pointer to the start of the result set
            while ($arrayOfAvailableDrivers = mysqli_fetch_assoc($fetchAvailableDriversData)) {
                $driverId = $arrayOfAvailableDrivers['driver_id'];
                $locationLatitude = $arrayOfAvailableDrivers['location_latitude'];
                $locationLongitude = $arrayOfAvailableDrivers['location_longitude'];
            ?>
                (function(driverId, lat, lon) {
                    const location2 = { lat: lat, lon: lon }; 
                    getRoadDistance(location1, location2, driverId).then(distance => {
                        if (distance) {
                            console.log(`Distance to driver ${driverId}: ${distance.toFixed(2)} km`);
                            document.getElementById(`distance-${driverId}`).innerText = distance.toFixed(2) + ' km';

                            // Check for closest driver after all distances are calculated
                            if (distances.length === <?php echo $isAvailableDriversExist; ?>) {
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

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"></script>
</body>

</html>
