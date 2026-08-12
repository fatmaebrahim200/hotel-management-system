<?php

session_start();

/*
==================================================
DATABASE CONNECTION
==================================================
*/

require_once __DIR__ . "/config/db.php";


/*
==================================================
CHECK DATABASE CONNECTION
==================================================
*/

if (!isset($conn) || !($conn instanceof mysqli)) {

    die("Database connection failed.");

}


/*
==================================================
CHECK LOGIN
==================================================
*/

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}


$user_id = (int) $_SESSION["user_id"];

$error = "";
$success = "";


/*
==================================================
GET USER DATA
==================================================
*/

$sql = "SELECT
            id,
            name,
            email,
            phone,
            nationality,
            date_of_birth,
            gender
        FROM users
        WHERE id = ?";


$stmt = mysqli_prepare($conn, $sql);


if (!$stmt) {

    die("Database error: " . mysqli_error($conn));

}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);


mysqli_stmt_execute($stmt);


$result = mysqli_stmt_get_result($stmt);


$user = mysqli_fetch_assoc($result);


mysqli_stmt_close($stmt);


/*
==================================================
CHECK USER
==================================================
*/

if (!$user) {

    session_destroy();

    header("Location: login.php");
    exit();

}


/*
==================================================
UPDATE PROFILE
==================================================
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $nationality = trim($_POST["nationality"] ?? "");


    /*
    ==============================================
    VALIDATION
    ==============================================
    */

    if ($name === "") {

        $error = "Full name cannot be empty.";

    }

    elseif ($phone === "") {

        $error = "Phone number cannot be empty.";

    }

    elseif ($nationality === "") {

        $error = "Country cannot be empty.";

    }

    else {

        /*
        ==========================================
        UPDATE DATABASE
        ==========================================
        */

        $update_sql = "UPDATE users
                       SET name = ?,
                           phone = ?,
                           nationality = ?
                       WHERE id = ?";


        $update_stmt = mysqli_prepare(
            $conn,
            $update_sql
        );


        if (!$update_stmt) {

            $error = "Database error.";

        }

        else {

            mysqli_stmt_bind_param(
                $update_stmt,
                "sssi",
                $name,
                $phone,
                $nationality,
                $user_id
            );


            if (mysqli_stmt_execute($update_stmt)) {

                $success = "Profile updated successfully.";


                /*
                ==================================
                UPDATE CURRENT USER DATA
                ==================================
                */

                $user["name"] = $name;
                $user["phone"] = $phone;
                $user["nationality"] = $nationality;


                /*
                ==================================
                UPDATE SESSION
                ==================================
                */

                $_SESSION["user_name"] = $name;
                $_SESSION["user_phone"] = $phone;
                $_SESSION["user_nationality"] = $nationality;

            }

            else {

                $error = "Unable to update your profile.";

            }


            mysqli_stmt_close($update_stmt);

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

    <title>My Profile - Luxury Hotels</title>


    <!-- CSS -->

    <link
        rel="stylesheet"
        href="./css/profile.css"
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

        <h2>
            LUXURY
        </h2>

        <span>
            HOTELS
        </span>

    </div>


    <nav>

        <a
            href="./home.php"
            class="active"
        >
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


    <div class="nav-right">


        <div class="profile">


            <img
                src="./iamges/user.png"
                alt="Profile"
            >


            <div class="profile-menu">


                <a href="./profile.php">

                    <i class="fa-regular fa-user"></i>

                    My Profile

                </a>


                <a href="./mybooking.php">

                    <i class="fa-solid fa-calendar-check"></i>

                    My Bookings

                </a>


                <a
                    href="./logout.php"
                    class="logout"
                >

                    <i class="fa-solid fa-right-from-bracket"></i>

                    Logout

                </a>


            </div>


        </div>


    </div>


</header>



<!-- =========================================
     PAGE HEADER
========================================= -->

<section class="page-header">


    <div class="header-content">


        <p>
            WELCOME BACK
        </p>


        <h1>
            My Profile
        </h1>


        <div class="breadcrumb">


            <a href="./home.php">
                Home
            </a>


            <span>
                /
            </span>


            <span>
                My Profile
            </span>


        </div>


    </div>


</section>



<!-- =========================================
     PROFILE SECTION
========================================= -->

<section class="profile-container">


    <!-- =====================================
         PROFILE CARD
    ====================================== -->

    <div class="profile-card">


        <div class="profile-image">

            <img
                src="./iamges/user.png"
                alt="User"
            >

        </div>


        <h2>

            <?= htmlspecialchars($user["name"]) ?>

        </h2>


        <p class="email">

            <?= htmlspecialchars($user["email"]) ?>

        </p>


        <div class="gold-line"></div>


        <!-- MEMBER SINCE -->

        <div class="profile-item">


            <i class="fa-regular fa-calendar"></i>


            <div>

                <span>
                    Member Since
                </span>

                <strong>
                    2026
                </strong>

            </div>


        </div>


        <!-- MEMBERSHIP -->

        <div class="profile-item">


            <i class="fa-solid fa-crown"></i>


            <div>

                <span>
                    Membership
                </span>

                <strong>
                    Gold Member
                </strong>

            </div>


        </div>


        <!-- BOOKINGS -->

        <a
            href="./mybooking.php"
            class="booking-btn"
        >

            <i class="fa-solid fa-suitcase"></i>

            My Bookings

        </a>


    </div>



    <!-- =====================================
         PROFILE INFORMATION
    ====================================== -->

    <div class="profile-info">


        <!-- TITLE -->

        <div class="title">


            <div>

                <p>
                    ACCOUNT
                </p>


                <h2>
                    Personal Information
                </h2>

            </div>


            <button
                type="button"
                id="editBtn"
            >

                <i class="fa-solid fa-pen"></i>

                Edit Profile

            </button>


        </div>



        <!-- =================================
             SUCCESS MESSAGE
        ================================== -->

        <?php if ($success !== ""): ?>

            <div class="success-message">

                <i class="fa-solid fa-circle-check"></i>

                <?= htmlspecialchars($success) ?>

            </div>

        <?php endif; ?>



        <!-- =================================
             ERROR MESSAGE
        ================================== -->

        <?php if ($error !== ""): ?>

            <div class="error-message">

                <i class="fa-solid fa-circle-exclamation"></i>

                <?= htmlspecialchars($error) ?>

            </div>

        <?php endif; ?>



        <!-- =================================
             FORM
        ================================== -->

        <form
            id="profileForm"
            action=""
            method="POST"
        >


            <!-- NAME + EMAIL -->

            <div class="row">


                <!-- NAME -->

                <div class="input-group">


                    <label>
                        Full Name
                    </label>


                    <div class="input-box">


                        <i class="fa-regular fa-user"></i>


                        <input
                            type="text"
                            name="name"
                            value="<?= htmlspecialchars($user["name"] ?? "") ?>"
                            disabled
                            required
                        >


                    </div>


                </div>



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
                            value="<?= htmlspecialchars($user["email"] ?? "") ?>"
                            disabled
                        >


                    </div>


                </div>


            </div>



            <!-- PHONE + COUNTRY -->

            <div class="row">


                <!-- PHONE -->

                <div class="input-group">


                    <label>
                        Phone Number
                    </label>


                    <div class="input-box">


                        <i class="fa-solid fa-phone"></i>


                        <input
                            type="text"
                            name="phone"
                            value="<?= htmlspecialchars($user["phone"] ?? "") ?>"
                            disabled
                            required
                        >


                    </div>


                </div>



                <!-- COUNTRY -->

                <div class="input-group">


                    <label>
                        Country
                    </label>


                    <div class="input-box">


                        <i class="fa-solid fa-location-dot"></i>


                        <input
                            type="text"
                            name="nationality"
                            value="<?= htmlspecialchars($user["nationality"] ?? "") ?>"
                            disabled
                            required
                        >


                    </div>


                </div>


            </div>



            <!-- DATE + GENDER -->

            <div class="row">


                <!-- DATE OF BIRTH -->

                <div class="input-group">


                    <label>
                        Date of Birth
                    </label>


                    <div class="input-box">


                        <i class="fa-regular fa-calendar"></i>


                        <input
                            type="text"
                            value="<?= htmlspecialchars($user["date_of_birth"] ?? "") ?>"
                            disabled
                        >


                    </div>


                </div>



                <!-- GENDER -->

                <div class="input-group">


                    <label>
                        Gender
                    </label>


                    <div class="input-box">


                        <i class="fa-solid fa-user"></i>


                        <input
                            type="text"
                            value="<?= htmlspecialchars($user["gender"] ?? "") ?>"
                            disabled
                        >


                    </div>


                </div>


            </div>



            <!-- ADDRESS -->

            <div class="input-group">


                <label>
                    Address
                </label>


                <div class="input-box">


                    <i class="fa-solid fa-house"></i>


                    <input
                        type="text"
                        value="Cairo, Egypt"
                        disabled
                    >


                </div>


            </div>



            <!-- SAVE BUTTONS -->

            <div
                class="buttons"
                id="buttons"
                style="display: none;"
            >


                <button
                    type="button"
                    id="cancelBtn"
                    class="cancel"
                >

                    Cancel

                </button>


                <button
                    type="submit"
                    class="save"
                >

                    Save Changes

                </button>


            </div>


        </form>



        <!-- =====================================
             RECENT BOOKING
        ====================================== -->

        <div class="recent-title">


            <div>

                <p>
                    RESERVATIONS
                </p>


                <h2>
                    Recent Booking
                </h2>

            </div>


            <a href="./mybooking.php">

                View All

                <i class="fa-solid fa-arrow-right"></i>

            </a>


        </div>



        <!-- =====================================
             BOOKING CARD
        ====================================== -->

        <div class="booking-card">


            <img
                src="./iamges/room3.png"
                alt="Room"
            >


            <div class="booking-content">


                <span class="confirmed">
                    Confirmed
                </span>


                <h3>
                    Deluxe Ocean View
                </h3>


                <p>

                    <i class="fa-solid fa-location-dot"></i>

                    Luxury Hotels

                </p>


                <div class="dates">


                    <div>


                        <small>
                            Check-in
                        </small>


                        <strong>
                            15 Aug 2026
                        </strong>


                    </div>


                    <i class="fa-solid fa-arrow-right"></i>


                    <div>


                        <small>
                            Check-out
                        </small>


                        <strong>
                            18 Aug 2026
                        </strong>


                    </div>


                </div>


            </div>



            <div class="price">


                <small>
                    Total
                </small>


                <h3>
                    $450
                </h3>


                <button
                    type="button"
                >

                    View Details

                </button>


            </div>


        </div>


    </div>


</section>



<!-- =========================================
     FOOTER
========================================= -->

<footer>


    <div class="footer-container">


        <!-- ABOUT -->

        <div class="footer-about">


            <div class="footer-logo">

                LUXURY

                <span>
                    HOTELS
                </span>

            </div>


            <p>
                497 Evergreen Rd. Roseville, CA 95673
            </p>


            <p>
                +44 345 678 903
            </p>


            <p>
                luxury.hotels@gmail.com
            </p>


            <div class="social">


                <i class="fa-brands fa-facebook-f"></i>

                <i class="fa-brands fa-twitter"></i>

                <i class="fa-brands fa-instagram"></i>


            </div>


        </div>



        <!-- ABOUT LINKS -->

        <div class="footer-links">


            <h4>
                About
            </h4>


            <a href="#">
                About Us
            </a>


            <a href="./room.html">
                Rooms
            </a>


            <a href="./facilities.html">
                Facilities
            </a>


            <a href="./contact.html">
                Contact Us
            </a>


        </div>



        <!-- INFORMATION LINKS -->

        <div class="footer-links">


            <h4>
                Information
            </h4>


            <a href="#">
                Terms & Conditions
            </a>


            <a href="#">
                Privacy Policy
            </a>


            <a href="#">
                Refund Policy
            </a>


            <a href="#">
                FAQ
            </a>


        </div>



        <!-- NEWSLETTER -->

        <div class="newsletter">


            <h4>
                Subscribe to our newsletter
            </h4>


            <div class="subscribe">


                <input
                    type="email"
                    placeholder="Email Address"
                >


                <button
                    type="button"
                >

                    SUBSCRIBE

                </button>


            </div>


        </div>


    </div>



    <div class="copyright">

        © 2026 Luxury Hotels.
        All Rights Reserved.

    </div>


</footer>



<!-- =========================================
     JAVASCRIPT
========================================= -->

<script src="./js/profile.js"></script>


</body>

</html>

