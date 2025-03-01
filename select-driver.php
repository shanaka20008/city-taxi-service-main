<?php
include('./includes/connect.php');
session_start();

$selectedDriverId = isset($_GET['selectedDriverID']) ? $_GET['selectedDriverID'] : null;
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


<style>
  .reviews-card{
  z-index: 2; /* Ensure this is on top of the background */
  width: 100%;
  height: 100%;
  backdrop-filter: blur(3px);
  position: fixed; /* Positioning it absolutely within the container */
  visibility: hidden;

}

.records{
    position: absolute; /* Positioning it absolutely within the container */
    top: 20%; /* Adjust to position the overlay */
    left: 20%; /* Adjust to position the overlay */
    width: 800px;
    height: 325px;
    background-color: rgb(225, 225, 225);
    border-radius: 30px;
    color: black;
    z-index: 2; /* Ensure this is on top of the background */
    box-shadow: 0 -10px 15px rgba(0, 0, 0, 0.25), 
    0 10px 15px rgba(0, 0, 0, 0.25), 
    10px 0 15px rgba(0, 0, 0, 0.25), 
    -10px 0 15px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    
}

.table-container{
    position: absolute; 
    top: 17%;
    width:100%;
    
}

.closeButton{
  position:absolute;
  z-index: 3;
  height: 35px;
  width: 35px;
  right: 15px;
  top: 15px;
  color: #000000;
  font-size: 25px;
  text-align: center;
  border-radius: 30px;
  cursor: pointer;
}

.closeButton:hover{
  background-color: rgb(220, 0, 0);
  color: #ffffff;
  transition: .5s;
}

</style>

<body class="overflow-x-hidden bg-external-white">


<div class="reviews-card" id="reviews-card">
      <div class="records" >
        <div class="closeButton" onclick="closeReviews()" id="closeButton">X</div>
        <div class="table-container">
        <div class="table-responsive">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Feedback</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($selectedDriverId) {
                        $getReservations = mysqli_query($con, "SELECT * FROM `table_reservation` WHERE driver_id = $selectedDriverId");
                        if (mysqli_num_rows($getReservations) > 0) {
                          while ($arrayOfReservations = mysqli_fetch_assoc($getReservations)) {
                            $reservationID = $arrayOfReservations['reservation_id'];

                            $getReviews = mysqli_query($con, "SELECT * FROM `table_driver_feedback` WHERE reservation_id = $reservationID");

                            if (mysqli_num_rows($getReviews) > 0) {
                                while ($arrayOfReviews = mysqli_fetch_assoc($getReviews)) {
                                    $reviewDate = $arrayOfReviews['date'];
                                    $reviewBody = $arrayOfReviews['content_body'];
                                    $reviewRating = $arrayOfReviews['rating'];
                        ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($reviewDate); ?></td>
                                        <td><?php echo htmlspecialchars($reviewBody); ?></td>
                                        <td><?php echo htmlspecialchars($reviewRating); ?></td>
                                    </tr>
                        <?php
                                }
                            } 
                          }
                        } 
                        
                        else {
                            echo "<tr><td colspan='7' class='bg-light'>No reviews</td></tr>";
                        }
                      }
                        ?>
                </tbody>
            </table>
        </div>
    </div>
        </div>
    </div>
    </div>

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
                       style="width: 200px; height: 200px; object-fit: cover;" 
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
                        <span class="fw-bold">Distance:</span>
                        <span id="distance-<?php echo $driverId; ?>">Calculating...</span>
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
                      <a href="<?php echo "select-driver.php?selectedDriverID=" . $driverId; ?>" class="btn btn-primary w-100 mt-2"> View Reviews </a>
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
    const selectedDriverId = <?php echo json_encode($selectedDriverId); ?>;

    if (selectedDriverId) {
      showReviews();
    }

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
                                document.getElementById(`distance-${driverId}`).innerText = distance.toFixed(2) + ' km (Closest Driver!)';
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

    function showReviews(){
      document.getElementById('reviews-card').style.visibility = 'visible';
     
    }
    
    function closeReviews(){
      document.getElementById('reviews-card').style.visibility = 'hidden';
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"></script>
</body>

</html>
