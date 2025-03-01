<?php

if (isset($_GET['driver_id'])) {
    $passedDriverId = $_GET['driver_id'];

    $getDriverDetail = mysqli_query($con, "SELECT * FROM `table_driver` WHERE driver_id = $passedDriverId");
    if (mysqli_num_rows($getDriverDetail) == 1) {
        $arrayOfDriverDetail = mysqli_fetch_assoc($getDriverDetail);

        $driverName = $arrayOfDriverDetail['driver_name'];
        // echo $driverName;
    }
}
?>

<div class="table-responsive mt-5 mx-5">
    <table class="table text-center">
        <thead>
            <tr>
                <th class="background-black-color font-white">No</th>
                <th class="background-black-color font-white">Reservation ID</th>
                <th class="background-black-color font-white">Passenger ID</th>
                <th class="background-black-color font-white">Pickup Location</th>
                <th class="background-black-color font-white">Drop Location</th>
                <th class="background-black-color font-white">Status</th>
                <th class="background-black-color font-white">Date and Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $serialNo = 1;
            $status;
            // * Storyline:
            // * 1. Get the data from `table_reservation` based on driver id.
            $getServiceLogDetails = mysqli_query($con, "SELECT * FROM `table_reservation` WHERE driver_id = $passedDriverId");

            if (mysqli_num_rows($getServiceLogDetails) > 0) {
                while ($arrayOfServiceDetails = mysqli_fetch_assoc($getServiceLogDetails)) {
                    $reservationId = $arrayOfServiceDetails['reservation_id'];
                    $passengerId = $arrayOfServiceDetails['passenger_id'];
                    $pickupLocation = $arrayOfServiceDetails['pickup_location'];
                    $dropLocation = $arrayOfServiceDetails['drop_location'];
                    $reservationStatus = $arrayOfServiceDetails['reservation_status'];
                    $rideStartTime = $arrayOfServiceDetails['ride_start_time'];

                    if ($reservationStatus == "completed") {
                        $status = "Completed";
                    } else {
                        $status = "On Process";
                    }
                    // * 2. Show in UI

            ?>

                    <tr>
                        <td>#<?php echo $serialNo; ?></td>
                        <td><?php echo $reservationId; ?></td>
                        <td><?php echo $passengerId; ?></td>
                        <td class="text-capitalize"><?php echo $pickupLocation; ?></td>
                        <td class="text-capitalize"><?php echo $dropLocation; ?></td>
                        <td><?php echo $status; ?></td>
                        <td><?php echo $rideStartTime; ?></td>
                    </tr>
            <?php
                    $serialNo++;
                }
            }
            ?>
        </tbody>
    </table>
</div>
