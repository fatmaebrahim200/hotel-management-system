<?php
session_start();
include "./config/db.php";
$error = "";
$success = "";

$name = "";
$email = "";
$phone = "";
$nationality = "";
$date_of_birth = "";
$gender = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $nationality = trim($_POST["nationality"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";
    $date_of_birth = $_POST["date_of_birth"] ?? "";
    $gender = trim($_POST["gender"] ?? "");
    $agree = isset($_POST["agree"]);

    // ===========================
    // Validation
    // ===========================

    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($nationality) ||
        empty($password) ||
        empty($confirm_password) ||
        empty($date_of_birth) ||
        empty($gender)
    ) {

        $error = "Please fill in all fields.";

    }

    elseif (!$agree) {

        $error = "You must agree to the Terms & Conditions.";

    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    }

    elseif (!preg_match('/^[0-9]{11}$/', $phone)) {

        $error = "Phone number must contain 11 digits.";

    }

    elseif (strlen($password) < 6) {

        $error = "Password must be at least 6 characters.";

    }

    elseif ($password != $confirm_password) {

        $error = "Passwords do not match.";

    }

    else {

        // ===========================
        // Check Email
        // ===========================

        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email = ?"
        );

        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {

            $error = "This email is already registered.";

        } else {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $insert = mysqli_prepare(
                $conn,
                "INSERT INTO users
                (name,email,password,phone,nationality,date_of_birth,gender)
                VALUES (?,?,?,?,?,?,?)"
            );

            mysqli_stmt_bind_param(
                $insert,
                "sssssss",
                $name,
                $email,
                $hashedPassword,
                $phone,
                $nationality,
                $date_of_birth,
                $gender
            );

            if (mysqli_stmt_execute($insert)) {

                $_SESSION["success"] =
                    "Account created successfully.";

                header("Location: login.php");
                exit();

            } else {

                $error = "Registration failed.";

            }

            mysqli_stmt_close($insert);

        }

        mysqli_stmt_close($check);

    }

}
?>
<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | Luxury Hotels</title>

    <link rel="stylesheet" href="css/register.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

<section class="register-section">

<div class="container">

<div class="form-box">

<h2>Register</h2>

<?php if($error!=""){ ?>

<div class="error-message">
    <?php echo $error; ?>
</div>

<?php } ?>

<?php if($success!=""){ ?>

<div class="success-message">
    <?php echo $success; ?>
</div>

<?php } ?>

<form action="" method="POST">

<div class="row">

<div class="input-box">

<label>Full Name</label>

<div class="input">

<i class="fa-solid fa-user"></i>

<input
type="text"
name="name"
placeholder="Enter your full name"
value="<?= htmlspecialchars($name) ?>"
required>

</div>

</div>

<div class="input-box">

<label>Email Address</label>

<div class="input">

<i class="fa-solid fa-envelope"></i>

<input
type="email"
name="email"
placeholder="Enter your email"
value="<?= htmlspecialchars($email) ?>"
required>

</div>

</div>

</div>

<div class="row">

<div class="input-box">

<label>Phone Number</label>

<div class="input">

<i class="fa-solid fa-phone"></i>

<input
type="text"
name="phone"
placeholder="Enter your phone"
value="<?= htmlspecialchars($phone) ?>"
required>

</div>

</div>

<div class="input-box">

<label>Nationality</label>

<div class="input">

<i class="fa-solid fa-globe"></i>

<select name="nationality" required>

<option value="">Select your nationality</option>

<option value="Egypt" <?=($nationality=="Egypt")?"selected":""?>>Egypt</option>

<option value="Saudi Arabia" <?=($nationality=="Saudi Arabia")?"selected":""?>>Saudi Arabia</option>

<option value="UAE" <?=($nationality=="UAE")?"selected":""?>>UAE</option>

<option value="Kuwait" <?=($nationality=="Kuwait")?"selected":""?>>Kuwait</option>

<option value="Jordan" <?=($nationality=="Jordan")?"selected":""?>>Jordan</option>

<option value="Qatar" <?=($nationality=="Qatar")?"selected":""?>>Qatar</option>

</select>

</div>

</div>

</div>

<div class="row">

<div class="input-box">

<label>Password</label>

<div class="input">

<i class="fa-solid fa-lock"></i>

<input
type="password"
name="password"
placeholder="Enter password"
required>

<i class="fa-solid fa-eye eye"></i>

</div>

</div>

<div class="input-box">

<label>Confirm Password</label>

<div class="input">

<i class="fa-solid fa-lock"></i>

<input
type="password"
name="confirm_password"
placeholder="Confirm password"
required>

<i class="fa-solid fa-eye eye"></i>

</div>

</div>

</div>

<div class="row">

<div class="input-box">

<label>Date of Birth</label>

<div class="input">

<i class="fa-solid fa-calendar"></i>

<input
type="date"
name="date_of_birth"
value="<?= htmlspecialchars($date_of_birth) ?>"
required>

</div>

</div>

<div class="input-box">

<label>Gender</label>

<div class="input">

<i class="fa-solid fa-person"></i>

<select name="gender" required>

<option value="">Select Gender</option>

<option value="Male" <?=($gender=="Male")?"selected":""?>>Male</option>

<option value="Female" <?=($gender=="Female")?"selected":""?>>Female</option>

</select>

</div>

</div>

</div>
<div class="check">

    <input type="checkbox" name="agree" id="agree" required>

    <label for="agree">
        I agree to the
        <a href="#">Terms & Conditions</a>
        and
        <a href="#">Privacy Policy</a>
    </label>

</div>

<button type="submit">
    CREATE ACCOUNT
</button>

<p class="login">
    Already have an account?
    <a href="login.php">Login</a>
</p>

</form>

</div>

<!-- Right Side -->

<div class="info-box">

    <img src="./iamges/profile.png" alt="Register">

    <div class="content">

        <h2>Why Join Luxury?</h2>

        <ul>


            <li>✔ Faster booking experience</li>

            <li>✔ Save your favorite hotels & rooms</li>

            <li>✔ Exclusive offers & discounts</li>

            <li>✔ Track bookings and history</li>

            <li>✔ 24/7 customer support</li>

        </ul>

    </div>

</div>

</div>

</section>

<!-- Testimonials -->

<section class="testimonial">

    <h2>Testimonials</h2>

    <p id="testimonialText">

        "Calm, Serene, Retro - What a way to relax and enjoy."

    </p>

    <h4 id="testimonialName">

        Mr. and Mrs. Baxter, UK

    </h4>

    <div class="buttons">

        <button id="prev">
            <i class="fa-solid fa-angle-left"></i>
        </button>

        <button id="next">
            <i class="fa-solid fa-angle-right"></i>
        </button>

    </div>

</section>

<!-- Footer -->

<footer>

    <div class="footer-container">

        <div>

            <h3>LUXURY HOTELS</h3>

            <p>447 Evergreen Rd.</p>

            <p>LuxuryHotels@gmail.com</p>

            <p>+44 345 678 903</p>

        </div>

        <div>

            <h4>Links</h4>

            <a href="#">About Us</a>

            <a href="#">Contact Us</a>

            <a href="#">Terms</a>

        </div>

        <div>

            <h4>Follow Us</h4>

            <a href="#">Facebook</a>

            <a href="#">Twitter</a>

            <a href="#">Instagram</a>

        </div>

        <div>

            <h4>Subscribe</h4>

            <div class="subscribe">

                <input type="email" placeholder="Email Address">

                <button>OK</button>

            </div>

        </div>

    </div>

</footer>

<script src="js/register.js"></script>

</body>

</html>
