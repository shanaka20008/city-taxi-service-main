<?php
include('../../includes/connect.php');

if (isset($_GET['reservation_id'])) {

    $passedReservationId = $_GET['reservation_id'];
    $getDriverIdFromReservationTable = mysqli_query($con, "SELECT * FROM `table_reservation` WHERE driver_id = $passedReservationId");

    if (mysqli_num_rows($getDriverIdFromReservationTable) > 0 && mysqli_num_rows($getDriverIdFromReservationTable) == 1) {
        $arrayOfReservationDetail = mysqli_fetch_assoc($getDriverIdFromReservationTable);

        $driverId = $arrayOfReservationDetail['driver_id'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Fav Icon -->
    <link rel="shortcut icon" href="../../assets/img/taxi-img.png" type="image/x-icon" class="object-fit-cover" />
    <title>Driver Feedback Page - CityTaxi</title>

    <!-- Google Font (Sen) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Sen:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Just Validate Dev CDN -->
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Bootsrap CSS -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" />

    <!-- External CSS -->
    <link rel="stylesheet" href="../../assets/css/style.css" />
    <link rel="stylesheet" href="../../assets/css/style2.css" />
    <link rel="stylesheet" href="../../assets/css/style3.css" />
</head>

<body class="overflow-x-hidden bg-external-white">
    <!-- Body -->
    <main class="px-2 px-sm-3 px-md-5 pb-5 mt-5">
        <!-- Feedback Form -->
        <div class="mt-4 feedback-responsive mx-auto">
            <form method="post" class="background-grey p-3 p-sm-3 p-md-5 rounded-2" id="driver-feedback-form">
                <h3 class="pb-2 fw-bold text-center text-md-start">
                    How was the Passenger?
                </h3>
                <div class="align-items-center gap-5">
                    <!-- Subject -->
                    <div class="mb-3 w-100">
                        <label for="subject" class="form-label">Subject<span class="text-danger">*</span></label>
                        <div>
                            <input type="text" name="subject" class="form-control shadow-none" id="subject" placeholder="Ex: Nice, Ordinary, Best" required="required" />
                        </div>
                    </div>

                    <!-- Feedback -->
                    <div class="mb-3 w-100">
                        <label for="feedback" class="form-label">Feedback<span class="text-danger">*</span></label>
                        <div>
                            <textarea name="feedback" id="feedback" class="form-control shadow-none" rows="10" placeholder="Describe your thoughts / opinions..."></textarea>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="mb-3 w-100">
                        <label for="rating" class="form-label">Rating (1 - 5)<span class="text-danger">*</span></label>
                        <div>
                            <input type="text" name="rating" class="form-control shadow-none" id="rating" placeholder="Ex: 4.9" required="required" />
                        </div>
                    </div>
                </div>

                <!-- Create Account Button -->
                <div class="pt-3 w-100 d-md-flex align-items-center gap-2">
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        Submit
                    </button>

                    <!-- Skip Button -->
                    <a href="../homepage.php?history" id="btn-skip" class="btn btn-primary w-100 mb-3">
                        Skip
                    </a>
                </div>
            </form>
        </div>
    </main>

    <!-- Boostrap JS Files -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

    <!-- End -->

    <!-- JavaScript Validation for Inputs -->
    <script>
        const driverFeedbackFormEl = document.querySelector(
            "#driver-feedback-form"
        );
        const validator = new window.JustValidate(driverFeedbackFormEl);

        validator.addField(
            "#subject",
            [{
                    rule: "required",
                },
                {
                    rule: "minLength",
                    value: 4,
                },
                {
                    rule: "maxLength",
                    value: 20,
                },
            ], {
                errorLabelCssClass: ["error-msg-margin"],
            }
        );

        validator.addField(
            "#feedback",
            [{
                    rule: "required",
                },
                {
                    rule: "minLength",
                    value: 20,
                },
                {
                    rule: "maxLength",
                    value: 500,
                },
            ], {
                errorLabelCssClass: ["error-msg-margin"],
            }
        );

        validator.addField(
            "#rating",
            [{
                    rule: "required",
                },
                {
                    rule: "number",
                },
                {
                    rule: "minNumber",
                    value: 0,
                },
                {
                    rule: "maxNumber",
                    value: 5.0,
                },
            ], {
                errorLabelCssClass: ["error-msg-margin"],
            }
        );

        validator.onSuccess((event) => {
            event.preventDefault(); // Prevent form submission

            // Display alert and wait for user acknowledgment
            alert("Thank you for your feedback!");

            // Redirect after the alert is acknowledged
            window.location.href = '../homepage.php?history&driver_id=<?= $driverId ?>';
        });
    </script>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subjectContentEl = $_POST['subject'];
    $feedbackContentEl = $_POST['feedback'];
    $ratingEl = $_POST['rating'];

    $saveDriverFeedback = mysqli_query($con, "INSERT INTO `table_driver_feedback` (short_subject, content_body, rating, date, time, reservation_id) VALUES ('$subjectContentEl', '$feedbackContentEl', $ratingEl, NOW(), NOW(), $passedReservationId)");
    if ($saveDriverFeedback) {
        echo "<script>alert('Thank you for the feedback!')</script>";
        echo "<script>window.open('../homepage.php?history&driver_id={$driverId}','_self')</script>";
    }
}
?>
