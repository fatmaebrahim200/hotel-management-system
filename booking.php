<?php

session_start();

include "config/db.php";

/* =====================================================
   DATABASE CONNECTION
===================================================== */

if (!$conn) {
    die("Database connection failed.");
}

/* =====================================================
   CHECK LOGIN
===================================================== */

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION["user_id"];

/* =====================================================
   DEFAULT VALUES
===================================================== */

$error = "";
$success = "";

$roomName = "Deluxe Ocean View";
$roomImage = "./iamges/room1.png";
$roomPrice = 150;
$roomGuests = "2 Adults";
$roomSize = "35 m²";

/* =====================================================
   GET LOGGED USER INFORMATION
===================================================== */

$user_name = "";
$user_email = "";
$user_phone = "";

$userQuery = mysqli_prepare(
    $conn,
    "SELECT name, email, phone
     FROM users
     WHERE id = ?
     LIMIT 1"
);

if ($userQuery) {

    mysqli_stmt_bind_param(
        $userQuery,
        "i",
        $user_id
    );

    mysqli_stmt_execute($userQuery);

    mysqli_stmt_bind_result(
        $userQuery,
        $user_name,
        $user_email,
        $user_phone
    );

    mysqli_stmt_fetch($userQuery);

    mysqli_stmt_close($userQuery);
}

/* =====================================================
   HANDLE BOOKING
===================================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /* =================================================
       GET FORM DATA
    ================================================= */

    $roomNamePost = trim($_POST["room_name"] ?? "");

    $checkIn = trim($_POST["check_in"] ?? "");

    $checkOut = trim($_POST["check_out"] ?? "");

    $guestsText = trim($_POST["guests"] ?? "2 Adults");

    $fullName = trim($_POST["full_name"] ?? "");

    $email = trim($_POST["email"] ?? "");

    $phone = trim($_POST["phone"] ?? "");

    $payment = trim($_POST["payment"] ?? "hotel");

    /* =================================================
       ROOM NAME
    ================================================= */

    if ($roomNamePost !== "") {
        $roomName = $roomNamePost;
    }

    /* =================================================
       CONVERT GUESTS
    ================================================= */

    preg_match("/\d+/", $guestsText, $matches);

    if (!empty($matches)) {
        $guests = (int)$matches[0];
    } else {
        $guests = 2;
    }

    /* =================================================
       VALIDATION
    ================================================= */

    if (
        $roomName === "" ||
        $checkIn === "" ||
        $checkOut === "" ||
        $fullName === "" ||
        $email === "" ||
        $phone === ""
    ) {

        $error = "Please fill all required fields.";

    }

    /* =================================================
       EMAIL VALIDATION
    ================================================= */

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    }

    /* =================================================
       DATE VALIDATION
    ================================================= */

    elseif ($checkOut <= $checkIn) {

        $error = "Check-out date must be after check-in date.";

    }

    /* =================================================
       GUEST VALIDATION
    ================================================= */

    elseif ($guests < 1 || $guests > 4) {

        $error = "Invalid number of guests.";

    }

    /* =================================================
       CHECK ROOM AVAILABILITY
    ================================================= */

    else {

        $checkBooking = mysqli_prepare(
            $conn,

            "SELECT id
             FROM booking
             WHERE room_name = ?
             AND STATUS = 'Confirmed'
             AND CHECK_in < ?
             AND CHECK_out > ?
             LIMIT 1"
        );

        if ($checkBooking) {

            mysqli_stmt_bind_param(
                $checkBooking,
                "sss",
                $roomName,
                $checkOut,
                $checkIn
            );

            mysqli_stmt_execute($checkBooking);

            mysqli_stmt_store_result($checkBooking);

            if (mysqli_stmt_num_rows($checkBooking) > 0) {

                $error =
                    "Sorry, this room is already booked for these dates.";
            }

            mysqli_stmt_close($checkBooking);
        }
    }

    /* =================================================
       INSERT BOOKING
    ================================================= */

    if ($error === "") {

        $status = "Pending";

        $stmt = mysqli_prepare(
            $conn,

            "INSERT INTO booking
            (
                user_id,
                room_name,
                CHECK_in,
                CHECK_out,
                guests,
                STATUS
            )
            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
            )"
        );

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "isssis",
                $user_id,
                $roomName,
                $checkIn,
                $checkOut,
                $guests,
                $status
            );

            if (mysqli_stmt_execute($stmt)) {

                $success =
                    "Booking Confirmed Successfully! Your booking is Pending.";

                /* Clear POST after successful booking */
                $_POST = [];

            } else {

                $error =
                    "Booking could not be saved. Please try again.";
            }

            mysqli_stmt_close($stmt);

        } else {

            $error =
                "Database error while preparing booking.";
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

    <title>Book Your Stay - Luxury Hotels</title>

    <!-- Booking CSS -->
    <link
        rel="stylesheet"
        href="./css/booking.css"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >

</head>

<body>


<!-- =====================================================
     HERO
===================================================== -->

<section class="hero">

<header class="navbar">

    <!-- LOGO -->
    <a href="./home.php" class="logo">
        <h2>LUXURY</h2>
        <span>HOTELS</span>
    </a>

    <!-- NAV LINKS -->
    <nav class="nav-links">
        <a href="./home.php">Home</a>
        <a href="./facilities.html">Facilities</a>
        <a href="./room.html">Rooms</a>
        <a href="./contact.html">Contact-us</a>
    </nav>

    <!-- RIGHT SIDE -->
    <div class="nav-right">

        <div class="profile">

            <img
                src="./iamges/user.png"
                alt="Profile"
            >

            <div class="profile-menu">

                <a href="./profile.php">
                    <i class="fa-regular fa-user"></i>
                    <span>My Profile</span>
                </a>

                <a href="./mybooking.php">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>My Bookings</span>
                </a>

                <a href="./logout.php" class="logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>

            </div>

        </div>

    </div>

</header>

    <!-- =================================================
         HERO CONTENT
    ================================================= -->

    <div class="overlay">

        <h1>
            BOOK YOUR STAY
        </h1>

        <p>

            Home

            <i class="fa-solid fa-angle-right"></i>

            Booking

        </p>

    </div>

</section>


<!-- =====================================================
     SUCCESS MESSAGE
===================================================== -->

<?php if ($success !== ""): ?>

    <div
        class="booking-message success-message"
        id="successMessage"
    >

        <i class="fa-solid fa-circle-check"></i>

        <span>
            <?php echo htmlspecialchars($success); ?>
        </span>

    </div>

<?php endif; ?>


<!-- =====================================================
     ERROR MESSAGE
===================================================== -->

<?php if ($error !== ""): ?>

    <div
        class="booking-message error-message"
        id="errorMessage"
    >

        <i class="fa-solid fa-circle-exclamation"></i>

        <span>
            <?php echo htmlspecialchars($error); ?>
        </span>

    </div>

<?php endif; ?>


<!-- =====================================================
     BOOKING CONTAINER
===================================================== -->

<section class="booking-container">


    <!-- =================================================
         ROOM CARD
    ================================================= -->

    <div class="room-card">


        <!-- ROOM IMAGE -->

        <div class="room-image">

            <img
                id="roomImage"
                src="<?php echo htmlspecialchars($roomImage); ?>"
                alt="Room"
            >

        </div>


        <!-- ROOM INFORMATION -->

        <div class="room-info">

            <h2 id="roomName">

                <?php
                echo htmlspecialchars($roomName);
                ?>

            </h2>


            <div class="details">

                <span>

                    <i class="fa-solid fa-user"></i>

                    <span id="roomGuests">

                        <?php
                        echo htmlspecialchars($roomGuests);
                        ?>

                    </span>

                </span>


                <span>

                    <i class="fa-solid fa-bed"></i>

                    1 Room

                </span>


                <span>

                    <i class="fa-solid fa-expand"></i>

                    <span id="roomSize">

                        <?php
                        echo htmlspecialchars($roomSize);
                        ?>

                    </span>

                </span>

            </div>


            <p>

                Spacious room with stunning ocean view,
                king bed and modern amenities.

            </p>

        </div>


        <!-- PRICE -->

        <div class="price">

            <h2>

                $

                <span id="roomPrice">

                    <?php
                    echo htmlspecialchars($roomPrice);
                    ?>

                </span>

            </h2>

            <p>
                / night
            </p>

            <small>

                <i class="fa-solid fa-circle-check"></i>

                Includes complimentary breakfast

            </small>

        </div>

    </div>


    <!-- =================================================
         BOOKING FORM
    ================================================= -->

    <form
        action="booking.php"
        method="POST"
        id="bookingForm"
    >


        <!-- =================================================
             HIDDEN ROOM NAME
        ================================================= -->

        <input
            type="hidden"
            name="room_name"
            id="roomNameInput"
            value="<?php echo htmlspecialchars($roomName); ?>"
        >


        <div class="booking-grid">


            <!-- =================================================
                 LEFT SIDE
            ================================================= -->

            <div class="left">

                <h2>
                    Booking Details
                </h2>


                <!-- CHECK IN -->

                <label>
                    Check In
                </label>

                <input
                    type="date"
                    name="check_in"
                    id="checkIn"
                    required
                >


                <!-- CHECK OUT -->

                <label>
                    Check Out
                </label>

                <input
                    type="date"
                    name="check_out"
                    id="checkOut"
                    required
                >


                <!-- GUESTS -->

                <label>
                    Guests
                </label>

                <select
                    name="guests"
                    id="guests"
                    required
                >

                    <option value="1 Adult">
                        1 Adult
                    </option>

                    <option
                        value="2 Adults"
                        selected
                    >
                        2 Adults
                    </option>

                    <option value="3 Adults">
                        3 Adults
                    </option>

                    <option value="4 Adults">
                        4 Adults
                    </option>

                </select>


                <!-- ROOMS -->

                <label>
                    Rooms
                </label>

                <select name="rooms">

                    <option value="1">
                        1 Room
                    </option>

                    <option value="2">
                        2 Rooms
                    </option>

                    <option value="3">
                        3 Rooms
                    </option>

                </select>

            </div>


            <!-- =================================================
                 MIDDLE
            ================================================= -->

            <div class="middle">

                <h2>
                    Your Information
                </h2>


                <!-- NAME -->

                <label>
                    Full Name
                </label>

                <input
                    type="text"
                    name="full_name"
                    value="<?php echo htmlspecialchars($user_name); ?>"
                    required
                >


                <!-- EMAIL -->

                <label>
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    value="<?php echo htmlspecialchars($user_email); ?>"
                    required
                >


                <!-- PHONE -->

                <label>
                    Phone Number
                </label>

                <input
                    type="tel"
                    name="phone"
                    value="<?php echo htmlspecialchars($user_phone); ?>"
                    required
                >


                <!-- SPECIAL REQUESTS -->

                <label>
                    Special Requests
                </label>

                <textarea
                    name="special_requests"
                    placeholder="Write your request here..."
                ></textarea>

            </div>


            <!-- =================================================
                 RIGHT SIDE
            ================================================= -->

            <div class="right">

                <h2>
                    Booking Summary
                </h2>


                <div class="summary">


                    <!-- ROOM COST -->

                    <div>

                        <span>
                            Room Cost
                        </span>

                        <span id="roomCost">
                            $0
                        </span>

                    </div>


                    <!-- SERVICE FEE -->

                    <div>

                        <span>
                            Service Fee
                        </span>

                        <span>
                            $20
                        </span>

                    </div>


                    <!-- TAX -->

                    <div>

                        <span>
                            Tax & Fees
                        </span>

                        <span>
                            $30
                        </span>

                    </div>


                    <hr>


                    <!-- TOTAL -->

                    <div class="total">

                        <strong>
                            Total
                        </strong>

                        <strong id="total">
                            $0
                        </strong>

                    </div>

                </div>


                <!-- =================================================
                     PAYMENT
                ================================================= -->

                <h2>
                    Payment Method
                </h2>


                <div class="payment">


                    <label>

                        <input
                            type="radio"
                            name="payment"
                            value="hotel"
                            checked
                        >

                        Pay at Hotel

                    </label>


                    <label>

                        <input
                            type="radio"
                            name="payment"
                            value="card"
                        >

                        Credit Card

                    </label>


                    <label>

                        <input
                            type="radio"
                            name="payment"
                            value="paypal"
                        >

                        PayPal

                    </label>

                </div>


                <!-- =================================================
                     CONFIRM BUTTON
                ================================================= -->

                <button
                    type="submit"
                    class="confirm"
                    id="confirmBooking"
                >

                    <i class="fa-solid fa-check"></i>

                    CONFIRM BOOKING

                </button>

            </div>

        </div>

    </form>

</section>


<!-- =====================================================
     BOOKING JS
===================================================== -->

<script src="./js/booking.js"></script>


<!-- =====================================================
     SUCCESS ALERT
===================================================== -->

<?php if ($success !== ""): ?>

<script>

document.addEventListener("DOMContentLoaded", function () {

    alert("Booking Confirmed Successfully!");

});

</script>

<?php endif; ?>


</body>

</html>

