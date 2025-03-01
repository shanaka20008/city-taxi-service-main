<?php
include('../includes/connect.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Fav Icon -->
    <link rel="shortcut icon" href="../assets/img/taxi-img.png" type="image/x-icon" class="object-fit-cover" />
    <title>Admin Login Page - CityTaxi</title>

    <!-- Google Font (Sen) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Sen:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Just Validate Dev CDN -->
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Bootsrap CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />

    <!-- External CSS -->
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>

<body class="overflow-x-hidden container bg-light">
    <!-- Element of Showing Alert Messages -->
    <div id="alert-message-container" class="container mt-5">

    </div>
    <div class="background-black-color container margin-top-of-admin-card p-4 rounded-4" id="admin-login-card">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="container" id="admin-login-form">
            <div class="mx-auto d-flex justify-content-center align-items-center">
                <h1 class="text-white">Welcome back</h1>
            </div>

            <div>
                <!-- Admin Username -->
                <div class="mb-3 w-100">
                    <label for="admin-name" class="form-label font-white">Username<span class="text-danger">*</span></label>
                    <div>
                        <input type="text" class="form-control outline-remove shadow-none bg-external-white w-100 " id="admin-name" name="admin-name" placeholder="Enter your Username" required="required" autocomplete="off" />
                    </div>
                </div>

                <!-- Admin Password -->
                <div class="mb-3 w-100">
                    <label for="password" class="form-label font-white">Password<span class="text-danger">*</span></label>
                    <div>
                        <input type="password" class="form-control shadow-none bg-external-white" id="password" name="password" placeholder="Enter your Password" required="required" />
                    </div>
                </div>
                <!-- <button type="button" class="bg-external-white border-0" id="eye-closed-icon"><i class="fa-regular fa-eye-slash"></i> </button> -->

                <!-- Show Password Checkbox -->
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="show-password" id="show-password" value="true" />
                    <label class="form-check-label text-light" for="show-password">
                        Show Password
                    </label>
                </div>

                <div class="mb-3 mt-4 w-100">
                    <button class="btn btn-primary w-100 fw-semibold">Login</button>
                </div>

                <div class="d-md-flex justify-content-center align-items-center">
                    <a href="../index.php" class="text-decoration-none font-white-secondary font-size-small fw-normal">Back to Home</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Boostrap JS Files -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

    <!-- End -->

    <!-- JavaScript Validation for Inputs -->
    <script>
        const adminLoginFormEl = document.querySelector("#admin-login-form");
        const validator = new window.JustValidate(adminLoginFormEl);

        validator.addField(
            "#admin-name",
            [{
                rule: "required",
            }, ], {
                errorLabelCssClass: ["error-msg-secondary-red-variant"],
            }
        );

        validator.addField(
            "#password",
            [{
                rule: "required",
            }, ], {
                errorLabelCssClass: ["error-msg-secondary-red-variant"],
            }
        );

        validator.onSuccess(() => {
            adminLoginFormEl.submit();

        })
        adminLoginFormEl.reset();
    </script>

    < <script src="../assets/js/showPassword.js">
        </script>
</body>

</html>


<!-- PHP Code to Login Admin -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $adminNameEl = $_POST['admin-name'];
    $adminPasswordEl = $_POST['password'];

    $fetchAdminDetail = mysqli_query($con, "SELECT * FROM `table_admin_panel` WHERE username = '$adminNameEl'");
    $isAdminNameAvailable = mysqli_num_rows($fetchAdminDetail);
    $adminDetail = mysqli_fetch_assoc($fetchAdminDetail);

    if ($isAdminNameAvailable > 0) {
        $_SESSION['username'] = $adminNameEl;

        // Verify the password
        if ($adminPasswordEl == $adminDetail['password']) {
            if ($isAdminNameAvailable == 1) {
                $_SESSION['username'] = $adminNameEl;

                // Success alert
                echo "<script>alert('You have logged in successfully.');</script>";
                echo "<script>window.open('admin-panel.php?dashboard', '_self');</script>";
            }
        } else {
            // Incorrect password alert
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        }
    } else {
        // Invalid username alert
        echo "<script>alert('Username not found. Please reverify your username.');</script>";
    }
}
?>
