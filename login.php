<?php

session_start();

include "./config/db.php";

$error = "";
$email = "";


/* =========================================
   Remember Me
========================================= */

if (isset($_COOKIE["remember_email"])) {

    $email = $_COOKIE["remember_email"];

}


/* =========================================
   LOGIN
========================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";


    /* =========================================
       VALIDATION
    ========================================= */

    if ($email === "" || $password === "") {

        $error = "Please fill in all fields.";

    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    }

    else {


        /* =========================================
           ADMIN LOGIN
        ========================================= */

        if (
            $email === "admin@luxuryhotels.com" &&
            $password === "admin123"
        ) {

            $_SESSION["admin"] = true;

            $_SESSION["admin_email"] = $email;


            /* Remember Me */

            if (isset($_POST["remember"])) {

                setcookie(
                    "remember_email",
                    $email,
                    time() + (30 * 24 * 60 * 60),
                    "/"
                );

            }

            else {

                setcookie(
                    "remember_email",
                    "",
                    time() - 3600,
                    "/"
                );

            }


            /* Go to Admin Dashboard */

            header("Location: ./admin/dashboard.php");

            exit();

        }


        /* =========================================
           NORMAL USER LOGIN
        ========================================= */

        $sql = "SELECT
                    id,
                    name,
                    email,
                    PASSWORD,
                    phone,
                    nationality,
                    date_of_birth,
                    gender
                FROM users
                WHERE email = ?";


        $stmt = mysqli_prepare($conn, $sql);


        if (!$stmt) {

            $error = "Database error.";

        }

        else {

            mysqli_stmt_bind_param(
                $stmt,
                "s",
                $email
            );


            mysqli_stmt_execute($stmt);


            $result = mysqli_stmt_get_result($stmt);


            /* =========================================
               CHECK EMAIL
            ========================================= */

            if (mysqli_num_rows($result) === 1) {

                $user = mysqli_fetch_assoc($result);


                /* =========================================
                   CHECK PASSWORD
                ========================================= */

                if (
                    password_verify(
                        $password,
                        $user["PASSWORD"]
                    )
                ) {


                    /* =========================================
                       SAVE USER DATA IN SESSION
                    ========================================= */

                    $_SESSION["user_id"] = $user["id"];

                    $_SESSION["user_name"] = $user["name"];

                    $_SESSION["user_email"] = $user["email"];

                    $_SESSION["user_phone"] = $user["phone"];

                    $_SESSION["user_nationality"] =
                        $user["nationality"];

                    $_SESSION["user_date_of_birth"] =
                        $user["date_of_birth"];

                    $_SESSION["user_gender"] =
                        $user["gender"];


                    /* =========================================
                       REMEMBER ME
                    ========================================= */

                    if (isset($_POST["remember"])) {

                        setcookie(
                            "remember_email",
                            $email,
                            time() + (30 * 24 * 60 * 60),
                            "/"
                        );

                    }

                    else {

                        setcookie(
                            "remember_email",
                            "",
                            time() - 3600,
                            "/"
                        );

                    }


                    /* =========================================
                       LOGIN SUCCESS
                    ========================================= */

                    header("Location: home.php");

                    exit();

                }

                else {

                    $error = "Incorrect password.";

                }

            }

            else {

                $error = "Email not found.";

            }


            mysqli_stmt_close($stmt);

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

    <title>Login - Luxury Hotels</title>


    <!-- CSS -->

    <link
        rel="stylesheet"
        href="./css/login.css"
    >


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <!-- Google Fonts -->

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet"
    >

</head>


<body>


<!-- =========================================
     NAVBAR
========================================= -->

<header class="navbar">


    <div class="logo">

        <h2>LUXURY</h2>

        <span>HOTELS</span>

    </div>


    <nav>

        <a href="./home.php">
            Home
        </a>

        <a href="./facilities.html">
            Facilities
        </a>

        <a href="./room.html">
            Rooms
        </a>

        <a href="./contact.html">
            Contact-us
        </a>

    </nav>


</header>



<!-- =========================================
     LOGIN SECTION
========================================= -->

<section class="login-container">


    <!-- =====================================
         LEFT SIDE
    ====================================== -->

    <div class="login-image">


        <div class="image-content">


            <p>
                WELCOME BACK
            </p>


            <h1>

                Your Luxury
                <br>
                Stay Awaits

            </h1>


            <span></span>


            <p class="description">

                Sign in to manage your bookings
                and enjoy a seamless hotel experience.

            </p>


        </div>


    </div>



    <!-- =====================================
         RIGHT SIDE
    ====================================== -->

    <div class="login-box">


        <!-- =================================
             TITLE
        ================================== -->

        <div class="login-title">


            <p>
                WELCOME BACK
            </p>


            <h2>
                Login
            </h2>


            <span>
                Please enter your details to continue
            </span>


        </div>



        <!-- =================================
             ERROR MESSAGE
        ================================== -->

        <?php if ($error !== "") { ?>

            <div class="error-message">

                <i class="fa-solid fa-circle-exclamation"></i>

                <span>
                    <?= htmlspecialchars($error) ?>
                </span>

            </div>

        <?php } ?>



        <!-- =================================
             LOGIN FORM
        ================================== -->

        <form
            action=""
            method="POST"
        >


            <!-- EMAIL -->

            <div class="input-group">


                <label>
                    Email Address
                </label>


                <div class="input-box">


                    <i class="fa-regular fa-envelope"></i>


                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        value="<?= htmlspecialchars($email) ?>"
                        required
                    >


                </div>


            </div>



            <!-- PASSWORD -->

            <div class="input-group">


                <div class="password-label">


                    <label>
                        Password
                    </label>


                    <a href="#">
                        Forgot Password?
                    </a>


                </div>



                <div class="input-box">


                    <i class="fa-solid fa-lock"></i>


                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Enter your password"
                        required
                    >


                    <i
                        class="fa-solid fa-eye eye"
                        id="togglePassword"
                    ></i>


                </div>


            </div>



            <!-- REMEMBER ME -->

            <div class="remember">


                <label>


                    <input
                        type="checkbox"
                        name="remember"
                        <?php
                        if (isset($_COOKIE["remember_email"])) {
                            echo "checked";
                        }
                        ?>
                    >


                    Remember me


                </label>


            </div>



            <!-- LOGIN BUTTON -->

            <button
                type="submit"
                class="login-submit"
            >

                Login

                <i class="fa-solid fa-arrow-right"></i>

            </button>


        </form>



        <!-- =================================
             REGISTER
        ================================== -->

        <div class="register-text">


            Don't have an account?


            <a href="./register.php">

                Create an account

            </a>


        </div>


    </div>


</section>



<!-- =========================================
     FOOTER
========================================= -->

<footer>


    <div class="footer-content">


        <div class="footer-logo">


            LUXURY


            <span>
                HOTELS
            </span>


        </div>



        <p>

            © 2026 Luxury Hotels.
            All Rights Reserved.

        </p>



        <div class="social">


            <i class="fa-brands fa-facebook-f"></i>


            <i class="fa-brands fa-twitter"></i>


            <i class="fa-brands fa-instagram"></i>


        </div>


    </div>


</footer>



<!-- =========================================
     JAVASCRIPT
========================================= -->

<script>

const eye = document.getElementById("togglePassword");

const password = document.getElementById("password");


if (eye && password) {


    eye.addEventListener("click", function () {


        if (password.type === "password") {


            password.type = "text";


            eye.classList.remove("fa-eye");

            eye.classList.add("fa-eye-slash");


        }

        else {


            password.type = "password";


            eye.classList.remove("fa-eye-slash");

            eye.classList.add("fa-eye");


        }


    });


}

</script>


</body>

</html>