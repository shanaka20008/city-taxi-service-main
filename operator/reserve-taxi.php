<?php
@include('../includes/connect.php');
@session_start();

$operatorName = $_SESSION['operatorUsername'];
$getOperatorName = mysqli_query($con, "SELECT * FROM `table_operator` WHERE operator_name = '$operatorName'");
$isNameExist = mysqli_num_rows($getOperatorName);

if ($isNameExist > 0 && $isNameExist == 1) {
    $arrayOfOperatorDetail = mysqli_fetch_assoc($getOperatorName);
    $operatorId = $arrayOfOperatorDetail['operator_id'];
}
?>

<div class="container-fluid p-3">
    <div class="mt-5 mb-5 mx-5">
        <form method="post" class="bg-blue p-4 rounded background-grey" id="passenger-reservation-form">
                <!-- Passenger Name -->
                <div class="col-12 mb-4">
                    <label for="passenger-name" class="form-label text-dark">Passenger Name</label>
                    <input type="text" class="form-control" id="passenger-name" name="passenger-name" placeholder="Enter the passenger name" required />
                </div>

                <!-- Passenger Contact Number -->
                <div class="col-12 mb-4">
                    <label for="passenger-contact-number" class="form-label text-dark">Passenger Contact Number</label>
                    <input type="text" class="form-control" id="passenger-contact-number" name="passenger-contact-number" placeholder="Enter the passenger contact number" required />
                </div>

                <!-- Pickup Location -->
                <div class="col-12 mb-4">
                    <label for="passenger-pickup-location" class="form-label text-dark">Pickup Location</label>
                    <input type="text" class="form-control" id="passenger-pickup-location" name="passenger-pickup-location" placeholder="Enter Pickup Location" required />
                </div>

                <!-- Drop Location -->
                <div class="col-12 mb-4">
                    <label for="passenger-drop-location" class="form-label text-dark">Drop Location</label>
                    <input type="text" class="form-control" id="passenger-drop-location" name="passenger-drop-location" placeholder="Enter Drop Location" required />
                </div>

                <!-- Date and Time of Reservation -->
                <div class="col-12 mb-3">
                    <label for="date-and-time-of-reservation" class="form-label text-dark">Date</label>
                    <input type="datetime-local" class="form-control" id="date-and-time-of-reservation" name="date-and-time-of-reservation" required />
                </div>

                <!-- Submit Button -->
                <div class="col-12 mb-3 d-flex align-items-end">
                    <input type="submit" class="btn btn-primary mt-3 w-100" value="Select driver" />
                </div>
        </form>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $passengerNameEl = $_POST['passenger-name'];
    $passengerContactNumberEl = $_POST['passenger-contact-number'];
    $passengerPickupLocationEl = $_POST['passenger-pickup-location'];
    $passengerDropLocationEl = $_POST['passenger-drop-location'];
    $dateAndTimeOfReservationEl = $_POST['date-and-time-of-reservation'];

    $status = "on process";
    $passenger_id = 0;
    $driver_id = 0;

    $temporaryReserve = "INSERT INTO `table_reservation` 
    (
        passenger_name,
        passenger_contact_no,
        pickup_location, 
        drop_location,
        reservation_status,
        driver_id,
        passenger_id,
        ride_start_time,
        operator_id
    ) VALUES 
    (
        '$passengerNameEl',
        '$passengerContactNumberEl',
        '$passengerPickupLocationEl',
        '$passengerDropLocationEl',
        '$status',
        $driver_id,
        $passenger_id,
        '$dateAndTimeOfReservationEl',
        $operatorId
    )";

    $executeQuery = mysqli_query($con, $temporaryReserve);
    if ($executeQuery) {
        echo "<script>window.open('operator-homepage.php?filter&operator_id=$operatorId','_self')</script>";
    }
}
?>
