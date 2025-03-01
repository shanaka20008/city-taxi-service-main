<?php
include('../includes/connect.php');
@session_start();

$loggedInUsername = $_SESSION['username'];

$getLoggedInUsernameDetails = mysqli_query($con, "SELECT * FROM `table_driver` WHERE driver_username = '$loggedInUsername'");
$isLoggedInUsernameExist = mysqli_num_rows($getLoggedInUsernameDetails);

if ($isLoggedInUsernameExist > 0 && $isLoggedInUsernameExist == 1) {
    $arrayOfLoggedInUserDataFromDB = mysqli_fetch_assoc($getLoggedInUsernameDetails);

    $driverId = $arrayOfLoggedInUserDataFromDB['driver_id'];
    $driverName = $arrayOfLoggedInUserDataFromDB['driver_name'];
    $driverAddressLine = $arrayOfLoggedInUserDataFromDB['driver_address_line'];
    $driverCity = $arrayOfLoggedInUserDataFromDB['driver_city'];
    $driverCountry = $arrayOfLoggedInUserDataFromDB['driver_country'];
    $driverUsername = $arrayOfLoggedInUserDataFromDB['driver_username'];
    $driverEmail = $arrayOfLoggedInUserDataFromDB['driver_email'];
    $driverPhoneNo = $arrayOfLoggedInUserDataFromDB['driver_phone_no'];
    $driverIdCardNo = $arrayOfLoggedInUserDataFromDB['driver_id_card_no'];
    $availabilityStatus = $arrayOfLoggedInUserDataFromDB['availability_status'];
    $driverImage = $arrayOfLoggedInUserDataFromDB['driver_image'];
}
?>

<div class="container my-5">
    <div class="card mx-auto border rounded-3" style="max-width: 900px;">
        <div class="row g-0">
            <!-- Driver Image Column -->
            <div class="col-md-4 text-center p-4 d-flex align-items-center justify-content-center">
                <img src="../sign-up/driver-profile-picture/<?php echo $driverImage; ?>" 
                     class="rounded-circle border border-3 img-fluid" 
                     style="max-width: 200px; height: auto; object-fit: cover;" 
                     alt="<?php echo $driverName; ?>'s Picture" />
            </div>
            
            <!-- Driver Info Column -->
            <div class="col-md-8">
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Name:</span>
                            <span><?php echo $driverName; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Username:</span>
                            <span><?php echo $driverUsername; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Email:</span>
                            <a href="mailto:<?php echo $driverEmail; ?>" class="text-decoration-none">
                                <i class="fas fa-envelope"></i> <?php echo $driverEmail; ?>
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Contact No:</span>
                            <a href="tel:<?php echo $driverPhoneNo; ?>" class="text-decoration-none">
                                <i class="fas fa-phone"></i> <?php echo $driverPhoneNo; ?>
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Address:</span>
                            <span><?php echo $driverAddressLine; ?>, <?php echo $driverCity; ?>, <?php echo $driverCountry; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">ID Card No:</span>
                            <span><?php echo $driverIdCardNo; ?></span>
                        </li>
                        <!-- Availability Status -->
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Availability:</span>
                            <span class="<?php echo $availabilityStatus == 'available' ? 'text-success' : 'text-danger'; ?>">
                                <?php echo ucfirst($availabilityStatus); ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
