<?php
include('../includes/connect.php');
@session_start();
?>

    <div class="table-responsive mt-5 mx-5">
        <table class="table text-center">
            <thead>
                <tr>
                    <th class="background-black-color text-white">No</th>
                    <th class="background-black-color text-white">Reservation ID</th>
                    <th class="background-black-color text-white">Driver ID</th>
                    <th class="background-black-color text-white">Pickup Location</th>
                    <th class="background-black-color text-white">Drop Location</th>
                    <th class="background-black-color text-white">Status</th>
                    <th class="background-black-color text-white">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $serialNo = 1;
                $sessionUserName = $_SESSION['operatorUsername'];
                $getOperatorId = mysqli_query($con, "SELECT * FROM `table_operator` WHERE operator_name = '$sessionUserName'");

                if (mysqli_num_rows($getOperatorId) > 0 && mysqli_num_rows($getOperatorId) == 1) {
                    $arrayOfOperatorDet = mysqli_fetch_assoc($getOperatorId);
                    $operatorId = $arrayOfOperatorDet['operator_id'];

                    $getDetailsFromReservation = mysqli_query($con, "SELECT * FROM `table_reservation` WHERE operator_id = $operatorId");

                    if (mysqli_num_rows($getDetailsFromReservation) > 0) {
                        while ($arrayOfReservationDet = mysqli_fetch_assoc($getDetailsFromReservation)) {
                            $reservationId = $arrayOfReservationDet['reservation_id'];
                            $driverID = $arrayOfReservationDet['driver_id'];
                            $pickupLocation = $arrayOfReservationDet['pickup_location'];
                            $dropLocation = $arrayOfReservationDet['drop_location'];
                            $status = $arrayOfReservationDet['reservation_status'];
                            $date = $arrayOfReservationDet['ride_start_time'];
                ?>
                            <tr>
                                <td class="bg-light">#<?php echo htmlspecialchars($serialNo); ?></td>
                                <td class="bg-light"><?php echo htmlspecialchars($reservationId); ?></td>
                                <td class="bg-light"><?php echo htmlspecialchars($driverID); ?></td>
                                <td class="bg-light text-capitalize"><?php echo htmlspecialchars($pickupLocation); ?></td>
                                <td class="bg-light text-capitalize"><?php echo htmlspecialchars($dropLocation); ?></td>
                                <td class="bg-light text-capitalize"><?php echo htmlspecialchars($status); ?></td>
                                <td class="bg-light"><?php echo htmlspecialchars($date); ?></td>
                            </tr>
                <?php
                            $serialNo++;
                        }
                    } else {
                        echo "<tr><td colspan='7' class='bg-light'>No reservations</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='bg-light'>Invalid operator</td></tr>";
                }
                ?>
            </tbody>
        </table>
</div>
