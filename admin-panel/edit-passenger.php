<?php

if (isset($_GET['passenger_id'])) {
    $passedPassengerId = $_GET['passenger_id'];

    $getPassengerDetails = mysqli_query($con, "SELECT * FROM `table_passenger` WHERE id = $passedPassengerId");

    if (mysqli_num_rows($getPassengerDetails) > 0 && mysqli_num_rows($getPassengerDetails) == 1) {

        $arrayOfPassengerDetails = mysqli_fetch_assoc($getPassengerDetails);

        $passengerName = $arrayOfPassengerDetails['passenger_name'];
        $passengerEmail = $arrayOfPassengerDetails['passenger_email'];
        $passengerPhoneNo = $arrayOfPassengerDetails['passenger_phone_no'];
        $passengerAddressLine = $arrayOfPassengerDetails['passenger_address_line'];
        $passengerCity = $arrayOfPassengerDetails['passenger_city'];
        $passengerCountry = $arrayOfPassengerDetails['passenger_country'];
    }
}
?>

<form method="post" class="p-5 rounded mt-5 mb-5 mx-5 background-grey">
    <!-- Email -->
    <div class="mb-3 w-100">
        <label for="passenger-email" class="form-label">Email</label>
        <div>
            <input type="email" class="form-control" id="passenger-email" name="passenger-email" placeholder="Enter your Email" required="required" autocomplete="off" value="<?php echo $passengerEmail; ?>" />
        </div>
    </div>

    <!-- Phone Number -->
    <div class="mb-3 w-100">
        <label for="passenger-phone-no" class="form-label">Phone Number</label>
        <div>
            <input type="text" class="form-control" id="passenger-phone-no" name="passenger-phone-no" placeholder="Enter your Phone Number" required="required" autocomplete="off" value="<?php echo $passengerPhoneNo; ?>" />
        </div>
    </div>

    <!-- Address -->
    <div class="mb-3 w-100">
        <label for="passenger-address-line-1" class="form-label">Address Line 1</label>
        <div>
            <input type="text" class="form-control text-capitalize" id="passenger-address-line-1" name="passenger-address-line-1" placeholder="Ex: No.246/A, Meera Nagar Road" required="required" autocomplete="off" value="<?php echo $passengerAddressLine; ?>" />
        </div>
    </div>

    <!-- City -->
    <div class="mb-3 w-100">
        <label for="passenger-city-name" class="form-label">City<span class="text-danger">*</span></label>
        <div>
            <input type="text" class="form-control text-capitalize" id="passenger-city-name" name="passenger-city-name" placeholder="Ex: Nintavur" required="required" autocomplete="off" value="<?php echo $passengerCity; ?>" />
        </div>
    </div>

    <!-- Country -->
    <div class="mb-3 w-100">
        <label for="passenger-country-name" class="form-label">Country<span class="text-danger">*</span></label>
        <div>
            <input type="text" class="form-control text-capitalize" id="passenger-country-name" name="passenger-country-name" placeholder="Ex: Sri Lanka" required="required" autocomplete="off" value="<?php echo $passengerCountry; ?>" />
        </div>
    </div>

    <input type="submit" class="btn btn-primary text-light mt-4 w-100" value="Update" name="update-btn">
</form>

<!-- PHP code to update -->
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $passengerEmailEl = $_POST['passenger-email'];
    $passengerPhoneNoEl = $_POST['passenger-phone-no'];
    $passengerAddressLineEl = $_POST['passenger-address-line-1'];
    $passengerCityNameEl = $_POST['passenger-city-name'];
    $passengerCountryNameEl = $_POST['passenger-country-name'];

    $updatePassengerDetails = mysqli_query($con, "UPDATE `table_passenger` SET passenger_email = '$passengerEmailEl', passenger_phone_no = '$passengerPhoneNoEl', passenger_address_line = '$passengerAddressLineEl', passenger_city = '$passengerCityNameEl', passenger_country = '$passengerCountryNameEl' WHERE id = $passedPassengerId");
    if ($updatePassengerDetails) {
        echo "<script>alert('Dear Admin! $passengerName\'s details has been successfully updated...')</script>";
        echo "<script>window.open('admin-panel.php?passengers','_self')</script>";
    } else {
        echo "<script>alert('Dear Admin! $passengerName\'s details can\'t be updated at this moment. Please try again later...')</script>";
        echo "<script>window.open('admin-panel.php?passengers','_self')</script>";
    }
}

?>
