<?php
include('../includes/connect.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Fav Icon -->
    <link rel="shortcut icon" href="../assets/img/taxi-img.png" type="image/x-icon" class="object-fit-cover" />
    <title>All Drivers - City Taxi</title>

    <!-- Google Font (Sen) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Sen:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Just Validate Dev CDN -->
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Bootsrap CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />

    <!-- External CSS -->
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="../assets/css/style2.css">
</head>

<body class="bg-warning container-fluid">
    <div class="mt-5 mx-5">
        <table class="text-center table">
            <thead>
                <tr>
                    <th class="background-black-color font-white">No</th>
                    <th class="background-black-color font-white">Name</th>
                    <th class="background-black-color font-white">Email</th>
                    <th class="background-black-color font-white">Username</th>
                    <th class="background-black-color font-white">Phone Number</th>
                    <th class="background-black-color font-white">Availability</th>
                    <th class="background-black-color font-white">Starting Time</th>
                    <th class="background-black-color font-white">Ending Time</th>
                    <th class="background-black-color font-white">ID Card No</th>
                    <th class="background-black-color font-white">Address Line 1</th>
                    <th class="background-black-color font-white">City</th>
                    <th class="background-black-color font-white">Country</th>
                    <th class="background-black-color font-white">Profile Picture</th>
                    <th class="background-black-color font-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $serialNumber = 1;
                $statusReadableFormat;
                $fetchDriverDetails = mysqli_query($con, "SELECT * FROM `table_driver`");

                while ($arrayOfDriverDetails = mysqli_fetch_assoc($fetchDriverDetails)) {
                    $driverId = $arrayOfDriverDetails['driver_id'];
                    $driverName = $arrayOfDriverDetails['driver_name'];
                    $driverEmail = $arrayOfDriverDetails['driver_email'];
                    $driverPhoneNo = $arrayOfDriverDetails['driver_phone_no'];
                    $driverIdCardNo = $arrayOfDriverDetails['driver_id_card_no'];
                    $driverUsername = $arrayOfDriverDetails['driver_username'];
                    $availabilityStatus = $arrayOfDriverDetails['availability_status'];
                    $locationLatitude = $arrayOfDriverDetails['location_latitude'];
                    $locationLongitude = $arrayOfDriverDetails['location_longitude'];
                    $startTime = $arrayOfDriverDetails['start_time'];
                    $endTime = $arrayOfDriverDetails['end_time'];
                    $driverAddressLine = $arrayOfDriverDetails['driver_address_line'];
                    $driverCity = $arrayOfDriverDetails['driver_city'];
                    $driverCountry = $arrayOfDriverDetails['driver_country'];
                    $driverImage = $arrayOfDriverDetails['driver_image']; // Fetch the driver image

                    $driverLocation = $locationLatitude . "," . $locationLongitude; // Merging Latitude & Longitude values

                    if ($availabilityStatus == "available") {
                        $statusReadableFormat = "Available";
                    } else {
                        $statusReadableFormat = "Busy";
                    }
                ?>
                    <tr class="text-center">
                        <td class="bg-light text-black">
                            <?php echo $serialNumber; ?>
                        </td>
                        <td class="bg-light text-black">
                            <?php echo $driverName; ?>
                        </td>
                        <td class="bg-light text-black">
                            <a href="mailto:<?php echo $driverEmail; ?>" class="text-decoration-none text-black"><?php echo $driverEmail; ?></a>
                        </td>
                        <td class="bg-light text-black">
                            <?php echo $driverUsername; ?>
                        </td>
                        <td class="bg-light text-black">
                            <a href="tel:<?php echo $driverPhoneNo; ?>" class="text-decoration-none text-black"><?php echo $driverPhoneNo; ?></a>
                        </td>
                        <td class="bg-light text-black">
                            <?php echo $statusReadableFormat; ?>
                        </td>
                        <td class="bg-light text-black">
                            <?php echo $startTime; ?>
                        </td>
                        <td class="bg-light text-black">
                            <?php echo $endTime; ?>
                        </td>
                        <td class="bg-light text-black">
                            <?php echo $driverIdCardNo; ?>
                        </td>
                        <td class="bg-light text-black">
                            No. <?php echo $driverAddressLine; ?>
                        </td>
                        <td class="bg-light text-capitalize text-black">
                            <?php echo $driverCity; ?>
                        </td>
                        <td class="bg-light text-black">
                            <?php echo $driverCountry; ?>
                        </td>
                        <!-- Add this section to display the driver image -->
                        <td class="bg-light text-black">
                            <img src='../sign-up/driver-profile-picture/<?php echo $driverImage; ?>' style='width: 100%; height: auto;' alt='<?php echo $driverName; ?>'>
                        </td>
                        <td class="bg-light text-black">
                            <a href='admin-panel.php?edit_driver&driver_id=<?php echo $driverId; ?>' class='text-decoration-none text-black me-2'><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href='delete-driver.php?driver_id=<?php echo $driverId; ?>' class='text-decoration-none text-black'><i class='fa-solid fa-trash-can'></i></a>
                        </td>
                    </tr>
                <?php
                    $serialNumber++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Boostrap JavaScript Files -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

    <!-- JavaScript function to open Google Maps -->
    <script>
        function showLocationOnMap(location) {
            // Open Google Maps with the specified location
            window.open('https://www.google.com/maps?q=' + location, '_blank');
        }
    </script>
</body>

</html>
