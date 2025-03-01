<?php
include('../../includes/connect.php');
session_start();

$reservation_id = $_GET['reservation_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Fav Icon -->
    <link rel="shortcut icon" href="../../assets/img/taxi-img.png" type="image/x-icon" class="object-fit-cover" />
    <title>Passenger Feedback Page - CityTaxi</title>

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
            <form method="post" class="background-grey p-3 p-sm-3 p-md-5 rounded-2" id="passenger-feedback-form">
                <h3 class="pb-2 fw-bold text-center text-md-start">
                    How was your trip?
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
                    <button type="submit" class="btn btn-primary w-100 mb-3" onclick="redirectToDashboard()">
                        Submit
                    </button>

                    <!-- Skip Button -->
                    <a href="../passenger-homepage.php?history" id="btn-skip" class="btn btn-primary w-100 mb-3">
                        Skip
                    </a>
                    <!-- <button type="button" id="btn-skip" class="btn bg-secondary text-light w-100 mb-3">
                        Skip
                    </button> -->
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
        const passengerFeedbackFormEl = document.querySelector(
            "#passenger-feedback-form"
        );
        const validator = new window.JustValidate(passengerFeedbackFormEl);

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
            passengerFeedbackFormEl.submit();


            //alert("Thank you for the feedback!");


            //window.location.href = '../passenger-homepage.php?history';
        });
    </script>

</body>
</html>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    date_default_timezone_set('Asia/Kolkata');
    $subject = $_POST['subject'];
    $feedback = $_POST['feedback'];
    $rating = $_POST['rating'];
    $date = date('Y-m-d');
    $time = date('H:i:s');

    $addFeedback = mysqli_query($con,
        "INSERT INTO `table_driver_feedback` 
        (
          short_subject,
          content_body,
          rating,
          date,
          time,
          reservation_id
        ) 
        VALUES 
        (
          '$subject',
          '$feedback',
          '$rating',
          '$date',
          '$time',
          '$reservation_id'
        )"
    );

    if ($addFeedback) {
        echo "<script>alert('Thank you for the feedback!')</script>";

        echo "<script>window.open('../passenger-homepage.php?history','_self')</script>";
    }
}
?>