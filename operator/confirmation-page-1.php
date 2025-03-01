<?php
include('../includes/connect.php');
include('../includes/function.php');  // Include the functions file to access the sendSMS function
session_start();

if (isset($_GET['reservation_id'])) {
    $parsedReservationId = $_GET['reservation_id'];

    // Get reservation details to fetch the passenger and reservation data
    $getReservationDetail = mysqli_query($con, "SELECT * FROM `table_reservation` WHERE reservation_id = $parsedReservationId");
    $isDataExist = mysqli_num_rows($getReservationDetail);

    if ($isDataExist == 1) {
        $reservationData = mysqli_fetch_assoc($getReservationDetail);
        $driverId = $_GET['driver_id'];

        // Fetch passenger details directly from the reservation table
        $passengerName = $reservationData['passenger_name'];
        $passengerPhone = $reservationData['passenger_contact_no'];

        // Fetch driver details
        $getDriverDetail = mysqli_query($con, "SELECT * FROM `table_driver` WHERE driver_id = $driverId");
        $driverData = mysqli_fetch_assoc($getDriverDetail);
        $driverName = $driverData['name'];
        $driverPhone = $driverData['phone'];
        $taxiNumber = $driverData['taxi_number'];

        // Assign driver to the reservation and update the reservation status
        $assignDriver = mysqli_query($con, "UPDATE `table_reservation` SET driver_id = $driverId, reservation_status = 'confirmed' WHERE reservation_id = $parsedReservationId");
        
        if ($assignDriver) {
            echo "<script>alert('The reservation has been confirmed successfully.')</script>";
            
            // Send SMS to the passenger with driver and taxi details
            sendSMS($passengerPhone, $passengerName, $driverName, $taxiNumber, $driverPhone);
            
            // Redirect the operator back to the homepage after confirmation
            echo "<script>window.open('operator-homepage.php?check_queries','_self')</script>";
        }
    }
}
