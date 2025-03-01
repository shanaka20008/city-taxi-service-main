<?php
include('../includes/connect.php');
@session_start();

$sessionPassengerUsername = $_SESSION['passengerUsername'];

$fetchAllDetailsOfPassengerUsername = mysqli_query($con, "SELECT * FROM `table_passenger` WHERE passenger_username = '$sessionPassengerUsername'");
$arrayOfPassengerDetail = mysqli_fetch_assoc($fetchAllDetailsOfPassengerUsername);

$isPassengerUsernameExist = mysqli_num_rows($fetchAllDetailsOfPassengerUsername);

if ($isPassengerUsernameExist > 0 && $isPassengerUsernameExist == 1) {
    $passengerId = $arrayOfPassengerDetail['id'];
    $passengerName = $arrayOfPassengerDetail['passenger_name'];
    $passengerEmail = $arrayOfPassengerDetail['passenger_email'];
    $passengerPhoneNo = $arrayOfPassengerDetail['passenger_phone_no'];
    $passengerUsername = $arrayOfPassengerDetail['passenger_username'];
    $passengerIdCardNo = $arrayOfPassengerDetail['passenger_id_card_number'];
    $passengerAddressLine = $arrayOfPassengerDetail['passenger_address_line'];
    $passengerCity = $arrayOfPassengerDetail['passenger_city'];
    $passengerCountry = $arrayOfPassengerDetail['passenger_country'];
    $passengerImage = $arrayOfPassengerDetail['passenger_image'];
}
?>

<div class="container my-5">
    <div class="card mx-auto border rounded-3" style="max-width: 900px;">
        <div class="row g-0">
            <!-- Passenger Image Column -->
            <div class="col-md-4 text-center p-4 d-flex align-items-center justify-content-center">
                <img src="../sign-up/passenger-profile-picture/<?php echo $passengerImage; ?>" 
                     class="rounded-circle border border-3 img-fluid" 
                     style="max-width: 200px; height: auto; object-fit: cover;" 
                     alt="<?php echo $passengerName; ?>'s Picture" />
            </div>
            
            <!-- Passenger Info Column -->
            <div class="col-md-8">
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Name:</span>
                            <span><?php echo $passengerName; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Username:</span>
                            <span><?php echo $passengerUsername; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Email:</span>
                            <a href="mailto:<?php echo $passengerEmail; ?>" class="text-decoration-none">
                                <i class="fas fa-envelope"></i> <?php echo $passengerEmail; ?>
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Contact No:</span>
                            <a href="tel:<?php echo $passengerPhoneNo; ?>" class="text-decoration-none">
                                <i class="fas fa-phone"></i> <?php echo $passengerPhoneNo; ?>
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Address:</span>
                            <span><?php echo $passengerAddressLine; ?>, <?php echo $passengerCity; ?>, <?php echo $passengerCountry; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">ID Card No:</span>
                            <span><?php echo $passengerIdCardNo; ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
