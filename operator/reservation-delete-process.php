<?php
include('../includes/connect.php');
session_start();

if (isset($_GET['operator_id'])) {
    $parsedOperatorId = $_GET['operator_id'];

    $deletePendingReservation = mysqli_query($con, "DELETE FROM `table_reservation` WHERE operator_id = $parsedOperatorId AND driver_id = 0");
    if ($deletePendingReservation) {
        // Redirect to the operator homepage without alert
        echo "<script>window.open('operator-homepage.php?reserve','_self')</script>";
    }
}
?>
