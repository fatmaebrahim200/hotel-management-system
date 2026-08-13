<?php

session_start();

include "./config/db.php";

$error = "";


// =====================================================
// FORM VALUES
// =====================================================

$name = "";
$email = "";
$phone = "";
$nationality = "";
$date_of_birth = "";
$gender = "";


// =====================================================
// ONLY POST CAN REGISTER
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    // =================================================
    // GET DATA FROM POST ONLY
    // =================================================

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $nationality = trim($_POST["nationality"] ?? "");

    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    $date_of_birth = trim($_POST["date_of_birth"] ?? "");
    $gender = trim($_POST["gender"] ?? "");

    $agree = isset($_POST["agree"]);


    // =================================================
    // REQUIRED FIELDS
    // =================================================

    if (
        $name === "" ||
        $email === "" ||
        $phone === "" ||
        $nationality === "" ||
        $password === "" ||
        $confirm_password === "" ||
        $date_of_birth === "" ||
        $gender === ""
    ) {

        $error = "Please fill in all fields.";

    }


    // =================================================
    // TERMS & CONDITIONS
    // =================================================

    elseif (!$agree) {

        $error =
            "You must agree to the Terms & Conditions.";

    }


    // =================================================
    // NAME VALIDATION
    // =================================================

    elseif (!preg_match("/^[a-zA-Z ]{3,50}$/", $name)) {

        $error =
            "Name must contain only letters and spaces.";

    }


    // =================================================
    // EMAIL VALIDATION
    // =================================================

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error =
            "Please enter a valid email address.";

    }


    // =================================================
    // PHONE VALIDATION
    // =================================================

    elseif (!preg_match("/^01[0125][0-9]{8}$/", $phone)) {

        $error =
            "Please enter a valid Egyptian phone number.";

    }


    // =================================================
    // NATIONALITY VALIDATION
    // =================================================

    elseif (
        !in_array(
            $nationality,
            [
                "Egypt",
                "Saudi Arabia",
                "UAE",
                "Kuwait",
                "Jordan",
                "Qatar"
            ],
            true
        )
    ) {

        $error =
            "Please select a valid nationality.";

    }


    // =================================================
    // GENDER VALIDATION
    // =================================================

    elseif (
        !in_array(
            $gender,
            ["Male", "Female"],
            true
        )
    ) {

        $error =
            "Please select a valid gender.";

    }


    // =================================================
    // DATE FORMAT VALIDATION
    // =================================================

    elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date_of_birth)) {

        $error =
            "Please enter a valid date of birth.";

    }


    // =================================================
    // DATE + AGE VALIDATION
    // =================================================

    else {

        $birthDate = DateTime::createFromFormat(
            "Y-m-d",
            $date_of_birth
        );

        $today = new DateTime();


        if (
            !$birthDate ||
            $birthDate->format("Y-m-d") !== $date_of_birth
        ) {

            $error =
                "Invalid date of birth.";

        }

        elseif ($birthDate > $today) {

            $error =
                "Date of birth cannot be in the future.";

        }

        else {

            $age = $today->diff($birthDate)->y;


            // if ($age < 18) {

            //     $error =
            //         "You must be at least 18 years old.";

            // }

        }

    }


    // =================================================
    // VERY STRONG PASSWORD
    // =================================================

    if ($error === "") {


        // At least 12 characters
        if (strlen($password) < 12) {

            $error =
                "Password must be at least 12 characters.";

        }


        // Maximum 72 characters
        elseif (strlen($password) > 72) {

            $error =
                "Password must not exceed 72 characters.";

        }


        // No spaces
        elseif (preg_match("/\s/", $password)) {

            $error =
                "Password must not contain spaces.";

        }


        // At least one uppercase letter
        elseif (!preg_match("/[A-Z]/", $password)) {

            $error =
                "Password must contain at least one uppercase letter.";

        }


        // At least one lowercase letter
        elseif (!preg_match("/[a-z]/", $password)) {

            $error =
                "Password must contain at least one lowercase letter.";

        }


        // At least one number
        elseif (!preg_match("/[0-9]/", $password)) {

            $error =
                "Password must contain at least one number.";

        }


        // At least one special character
        elseif (!preg_match("/[^a-zA-Z0-9]/", $password)) {

            $error =
                "Password must contain at least one special character.";

        }


        // Password confirmation
        elseif ($password !== $confirm_password) {

            $error =
                "Passwords do not match.";

        }

    }


    // =================================================
    // CHECK EMAIL IN DATABASE
    // =================================================

    if ($error === "") {


        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email = ? LIMIT 1"
        );


        if (!$check) {

            $error =
                "Database error.";

        }

        else {


            mysqli_stmt_bind_param(
                $check,
                "s",
                $email
            );


            mysqli_stmt_execute($check);


            mysqli_stmt_store_result($check);


            if (mysqli_stmt_num_rows($check) > 0) {

                $error =
                    "This email is already registered.";

            }


            mysqli_stmt_close($check);

        }

    }


    // =================================================
    // INSERT USER
    // =================================================

    if ($error === "") {


        // =================================================
        // HASH PASSWORD
        // =================================================

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );


        // =================================================
        // PREPARED INSERT
        // =================================================

        $insert = mysqli_prepare(
            $conn,
            "INSERT INTO users
            (
                name,
                email,
                password,
                phone,
                nationality,
                date_of_birth,
                gender
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );


        if (!$insert) {

            $error =
                "Database error.";

        }

        else {


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


            // =================================================
            // EXECUTE INSERT
            // =================================================

            if (mysqli_stmt_execute($insert)) {


                $_SESSION["success"] =
                    "Account created successfully.";


                mysqli_stmt_close($insert);


                // =================================================
                // REDIRECT TO LOGIN
                // =================================================

                header("Location: login.php");

                exit();

            }

            else {

                $error =
                    "Registration failed. Please try again.";

                mysqli_stmt_close($insert);

            }

        }

    }

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Register | Luxury Hotels</title>


    <!-- CSS -->

    <link
        rel="stylesheet"
        href="css/register.css"
    >


    <!-- Google Font -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >

</head>


<body>


<section class="register-section">


    <div class="container">


        <!-- =========================================
             REGISTER FORM
        ========================================== -->

        <div class="form-box">


            <h2>
                Register
            </h2>


            <!-- ERROR MESSAGE -->

            <?php if (!empty($error)): ?>

                <div class="error-message">

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>


            <!-- =====================================
                 FORM
            ====================================== -->

            <form
                action="register.php"
                method="POST"
                autocomplete="off"
            >


                <!-- =================================
                     ROW 1
                ================================== -->

                <div class="row">


                    <!-- FULL NAME -->

                    <div class="input-box">

                        <label>
                            Full Name
                        </label>


                        <div class="input">

                            <i class="fa-solid fa-user"></i>


                            <input
                                type="text"
                                name="name"
                                placeholder="Enter your full name"
                                value="<?= htmlspecialchars($name) ?>"
                                minlength="3"
                                maxlength="50"
                                required
                            >

                        </div>

                    </div>



                    <!-- EMAIL -->

                    <div class="input-box">

                        <label>
                            Email Address
                        </label>


                        <div class="input">

                            <i class="fa-solid fa-envelope"></i>


                            <input
                                type="email"
                                name="email"
                                placeholder="Enter your email"
                                value="<?= htmlspecialchars($email) ?>"
                                required
                            >

                        </div>

                    </div>


                </div>



                <!-- =================================
                     ROW 2
                ================================== -->

                <div class="row">


                    <!-- PHONE -->

                    <div class="input-box">

                        <label>
                            Phone Number
                        </label>


                        <div class="input">

                            <i class="fa-solid fa-phone"></i>


                            <input
                                type="text"
                                name="phone"
                                placeholder="Enter your phone"
                                value="<?= htmlspecialchars($phone) ?>"
                                maxlength="11"
                                pattern="01[0125][0-9]{8}"
                                required
                            >

                        </div>

                    </div>



                    <!-- NATIONALITY -->

                    <div class="input-box">

                        <label>
                            Nationality
                        </label>


                        <div class="input">

                            <i class="fa-solid fa-globe"></i>


                            <select
                                name="nationality"
                                required
                            >

                                <option value="">
                                    Select your nationality
                                </option>


                                <option
                                    value="Egypt"
                                    <?= $nationality === "Egypt" ? "selected" : "" ?>
                                >
                                    Egypt
                                </option>


                                <option
                                    value="Saudi Arabia"
                                    <?= $nationality === "Saudi Arabia" ? "selected" : "" ?>
                                >
                                    Saudi Arabia
                                </option>


                                <option
                                    value="UAE"
                                    <?= $nationality === "UAE" ? "selected" : "" ?>
                                >
                                    UAE
                                </option>


                                <option
                                    value="Kuwait"
                                    <?= $nationality === "Kuwait" ? "selected" : "" ?>
                                >
                                    Kuwait
                                </option>


                                <option
                                    value="Jordan"
                                    <?= $nationality === "Jordan" ? "selected" : "" ?>
                                >
                                    Jordan
                                </option>


                                <option
                                    value="Qatar"
                                    <?= $nationality === "Qatar" ? "selected" : "" ?>
                                >
                                    Qatar
                                </option>


                            </select>

                        </div>

                    </div>


                </div>



                <!-- =================================
                     ROW 3
                ================================== -->

                <div class="row">


                    <!-- PASSWORD -->

                    <div class="input-box">

                        <label>
                            Password
                        </label>


                        <div class="input">

                            <i class="fa-solid fa-lock"></i>


                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Enter strong password"
                                minlength="12"
                                maxlength="72"
                                required
                            >


                            <i
                                class="fa-solid fa-eye eye"
                                onclick="togglePassword('password', this)"
                            ></i>


                        </div>


                        <small class="password-hint">

                            12+ characters, uppercase,
                            lowercase, number & special character

                        </small>


                    </div>



                    <!-- CONFIRM PASSWORD -->

                    <div class="input-box">

                        <label>
                            Confirm Password
                        </label>


                        <div class="input">

                            <i class="fa-solid fa-lock"></i>


                            <input
                                type="password"
                                name="confirm_password"
                                id="confirm_password"
                                placeholder="Confirm password"
                                minlength="12"
                                maxlength="72"
                                required
                            >


                            <i
                                class="fa-solid fa-eye eye"
                                onclick="togglePassword('confirm_password', this)"
                            ></i>


                        </div>

                    </div>


                </div>



                <!-- =================================
                     ROW 4
                ================================== -->

                <div class="row">


                    <!-- DATE OF BIRTH -->

                    <div class="input-box">

                        <label>
                            Date of Birth
                        </label>


                        <div class="input">

                            <i class="fa-solid fa-calendar"></i>


                            <input
                                type="date"
                                name="date_of_birth"
                                value="<?= htmlspecialchars($date_of_birth) ?>"
                                required
                            >

                        </div>

                    </div>



                    <!-- GENDER -->

                    <div class="input-box">

                        <label>
                            Gender
                        </label>


                        <div class="input">

                            <i class="fa-solid fa-person"></i>


                            <select
                                name="gender"
                                required
                            >

                                <option value="">
                                    Select Gender
                                </option>


                                <option
                                    value="Male"
                                    <?= $gender === "Male" ? "selected" : "" ?>
                                >
                                    Male
                                </option>


                                <option
                                    value="Female"
                                    <?= $gender === "Female" ? "selected" : "" ?>
                                >
                                    Female
                                </option>


                            </select>

                        </div>

                    </div>


                </div>



                <!-- =================================
                     TERMS
                ================================== -->

                <div class="check">


                    <input
                        type="checkbox"
                        name="agree"
                        id="agree"
                        required
                    >


                    <label for="agree">

                        I agree to the

                        <a href="#">
                            Terms & Conditions
                        </a>

                        and

                        <a href="#">
                            Privacy Policy
                        </a>

                    </label>


                </div>



                <!-- =================================
                     SUBMIT
                ================================== -->

                <button type="submit">

                    CREATE ACCOUNT

                </button>



                <!-- =================================
                     LOGIN
                ================================== -->

                <p class="login">

                    Already have an account?

                    <a href="login.php">
                        Login
                    </a>

                </p>


            </form>


        </div>



        <!-- =========================================
             RIGHT SIDE
        ========================================== -->

        <div class="info-box">


            <img
                src="./iamges/profile.png"
                alt="Register"
            >


            <div class="content">


                <h2>
                    Why Join Luxury?
                </h2>


                <ul>

                    <li>
                        ✔ Faster booking experience
                    </li>


                    <li>
                        ✔ Save your favorite hotels & rooms
                    </li>


                    <li>
                        ✔ Exclusive offers & discounts
                    </li>


                    <li>
                        ✔ Track bookings and history
                    </li>


                    <li>
                        ✔ 24/7 customer support
                    </li>

                </ul>


            </div>


        </div>


    </div>


</section>



<!-- =========================================
     TESTIMONIALS
========================================== -->

<section class="testimonial">


    <h2>
        Testimonials
    </h2>


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



<!-- =========================================
     FOOTER
========================================== -->

<footer>


    <div class="footer-container">


        <!-- CONTACT -->

        <div>

            <h3>
                LUXURY HOTELS
            </h3>


            <p>
                447 Evergreen Rd.
            </p>


            <p>
                LuxuryHotels@gmail.com
            </p>


            <p>
                +44 345 678 903
            </p>

        </div>



        <!-- LINKS -->

        <div>

            <h4>
                Links
            </h4>


            <a href="#">
                About Us
            </a>


            <a href="#">
                Contact Us
            </a>


            <a href="#">
                Terms
            </a>

        </div>



        <!-- SOCIAL -->

        <div>

            <h4>
                Follow Us
            </h4>


            <a href="#">
                Facebook
            </a>


            <a href="#">
                Twitter
            </a>


            <a href="#">
                Instagram
            </a>

        </div>



        <!-- SUBSCRIBE -->

        <div>

            <h4>
                Subscribe
            </h4>


            <div class="subscribe">


                <input
                    type="email"
                    placeholder="Email Address"
                >


                <button>
                    OK
                </button>


            </div>

        </div>


    </div>


</footer>



<script>


// =====================================================
// SHOW / HIDE PASSWORD
// =====================================================

function togglePassword(inputId, icon) {

    const input =
        document.getElementById(inputId);


    if (input.type === "password") {

        input.type = "text";

        icon.classList.remove("fa-eye");

        icon.classList.add("fa-eye-slash");

    }

    else {

        input.type = "password";

        icon.classList.remove("fa-eye-slash");

        icon.classList.add("fa-eye");

    }

}



// =====================================================
// TESTIMONIALS
// =====================================================

const testimonials = [

    {
        text:
            '"Calm, Serene, Retro - What a way to relax and enjoy."',

        name:
            "Mr. and Mrs. Baxter, UK"
    },


    {
        text:
            '"Amazing hotel, beautiful rooms and excellent service."',

        name:
            "Sarah Johnson, USA"
    },


    {
        text:
            '"A wonderful experience from booking to checkout."',

        name:
            "Ahmed Hassan, Egypt"
    },


    {
        text:
            '"Luxury, comfort and great hospitality in one place."',

        name:
            "James Wilson, UK"
    }

];


let current = 0;


const testimonialText =
    document.getElementById("testimonialText");


const testimonialName =
    document.getElementById("testimonialName");


const next =
    document.getElementById("next");


const prev =
    document.getElementById("prev");



function showTestimonial(index) {

    testimonialText.textContent =
        testimonials[index].text;

    testimonialName.textContent =
        testimonials[index].name;

}



next.addEventListener(
    "click",
    function () {

        current++;


        if (current >= testimonials.length) {

            current = 0;

        }


        showTestimonial(current);

    }
);



prev.addEventListener(
    "click",
    function () {

        current--;


        if (current < 0) {

            current =
                testimonials.length - 1;

        }


        showTestimonial(current);

    }
);


</script>


</body>

</html>