<?php

session_start();
include "./config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION["user_id"];
$success = "";
$error = "";

/* =========================
   PAY FOR BOOKING
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["pay_booking_id"])) {

    $booking_id = (int) ($_POST["pay_booking_id"] ?? 0);

    if ($booking_id <= 0) {
        $error = "Invalid booking.";
    } else {

        /* Make sure this booking belongs to the logged-in user */
        $check = mysqli_prepare(
            $conn,
            "SELECT id, STATUS, payment_status
             FROM booking
             WHERE id = ? AND user_id = ?
             LIMIT 1"
        );

        if (!$check) {
            $error = "Database error.";
        } else {

            mysqli_stmt_bind_param($check, "ii", $booking_id, $user_id);
            mysqli_stmt_execute($check);
            $checkResult = mysqli_stmt_get_result($check);
            $bookingToPay = mysqli_fetch_assoc($checkResult);
            mysqli_stmt_close($check);

            if (!$bookingToPay) {
                $error = "Booking not found.";
            } elseif (strtolower($bookingToPay["STATUS"] ?? "") === "cancelled") {
                $error = "You cannot pay for a cancelled booking.";
            } elseif (strtolower($bookingToPay["payment_status"] ?? "unpaid") === "paid") {
                $success = "Payment was already completed successfully.";
            } else {

                /*
                 * This is a demo payment flow for the project.
                 * It marks the booking as Paid after the user clicks Pay Now.
                 * A real card/PayPal gateway can be connected later.
                 */
                $paymentMethod = "card";
                $paymentStatus = "Paid";

                $payStmt = mysqli_prepare(
                    $conn,
                    "UPDATE booking
                     SET payment_status = ?,
                         payment_method = ?,
                         paid_at = NOW()
                     WHERE id = ? AND user_id = ?"
                );

                if (!$payStmt) {
                    $error = "Unable to process payment.";
                } else {

                    mysqli_stmt_bind_param(
                        $payStmt,
                        "ssii",
                        $paymentStatus,
                        $paymentMethod,
                        $booking_id,
                        $user_id
                    );

                    if (mysqli_stmt_execute($payStmt) && mysqli_stmt_affected_rows($payStmt) > 0) {
                        $success = "Payment completed successfully! Your booking has been marked as Paid.";
                    } else {
                        $error = "Payment could not be completed. Please try again.";
                    }

                    mysqli_stmt_close($payStmt);
                }
            }
        }
    }
}

/* =========================
   GET USER BOOKINGS
========================= */
$sql = "SELECT *
        FROM booking
        WHERE user_id = ?
        ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Query preparation failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Bookings - Luxury Hotels</title>

    <link rel="stylesheet" href="./css/mybooking.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet"
    >
</head>

<body>

<header class="navbar">

    <div class="logo">
        <h2>LUXURY</h2>
        <span>HOTELS</span>
    </div>

    <nav>
        <a href="./home.php">Home</a>
        <a href="./facilities.html">Facilities</a>
        <a href="./room.html">Rooms</a>
        <a href="./contact.html">Contact-us</a>
    </nav>

    <div class="nav-right">
        <div class="profile">

            <img src="./iamges/user.png" alt="Profile">

            <div class="profile-menu">

                <a href="./profile.php">
                    <i class="fa-regular fa-user"></i>
                    My Profile
                </a>

                <a href="./mybooking.php">
                    <i class="fa-solid fa-calendar-check"></i>
                    My Bookings
                </a>

                <a href="./logout.php" class="logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </a>

            </div>
        </div>
    </div>
</header>

<section class="page-header">
    <div>
        <p>YOUR RESERVATIONS</p>
        <h1>My Bookings</h1>

        <div class="breadcrumb">
            <a href="./home.php">Home</a>
            <span>/</span>
            <span>My Bookings</span>
        </div>
    </div>
</section>

<main class="bookings-container">

    <div class="page-title">
        <div>
            <p>BOOKING HISTORY</p>
            <h2>Your Reservations</h2>
        </div>

        <a href="./room.html" class="book-room">
            <i class="fa-solid fa-plus"></i>
            Book a Room
        </a>
    </div>

    <?php if ($success !== ""): ?>
        <div class="payment-message success-payment" id="paymentMessage">
            <i class="fa-solid fa-circle-check"></i>
            <span><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <?php if ($error !== ""): ?>
        <div class="payment-message error-payment" id="paymentMessage">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($result) > 0): ?>

        <?php while ($booking = mysqli_fetch_assoc($result)): ?>

            <?php
                $status = $booking["STATUS"] ?? "Pending";
                $statusClass = strtolower($status);

                $paymentStatus = $booking["payment_status"] ?? "Unpaid";
                $paymentClass = strtolower($paymentStatus);

                $roomNumber = 1;
                if (preg_match('/room\s*(\d+)/i', $booking["room_name"] ?? "", $m)) {
                    $roomNumber = (int) $m[1];
                }

                if ($roomNumber < 1 || $roomNumber > 15) {
                    $roomNumber = 1;
                }

                $roomImage = "./iamges/room" . $roomNumber . ".png";
            ?>

            <div class="booking-card">

                <div class="room-image">
                    <img src="<?= htmlspecialchars($roomImage) ?>" alt="Room">
                </div>

                <div class="booking-info">

                    <span class="status <?= htmlspecialchars($statusClass) ?>">
                        <?= htmlspecialchars($status) ?>
                    </span>

                    <h3><?= htmlspecialchars($booking["room_name"] ?? "Room") ?></h3>

                    <p class="hotel-name">
                        <i class="fa-solid fa-location-dot"></i>
                        Luxury Hotels
                    </p>

                    <div class="booking-details">

                        <div>
                            <span>Booking ID</span>
                            <strong>#BK<?= htmlspecialchars($booking["id"]) ?></strong>
                        </div>

                        <div>
                            <span>Guests</span>
                            <strong>
                                <i class="fa-solid fa-user-group"></i>
                                <?= htmlspecialchars($booking["guests"] ?? "0") ?> Adults
                            </strong>
                        </div>

                        <div>
                            <span>Payment</span>
                            <strong class="payment-status <?= htmlspecialchars($paymentClass) ?>">
                                <?= htmlspecialchars($paymentStatus) ?>
                            </strong>
                        </div>

                    </div>
                </div>

                <div class="dates">

                    <div>
                        <span>CHECK-IN</span>
                        <strong>
                            <?= date("d M Y", strtotime($booking["CHECK_in"])) ?>
                        </strong>
                    </div>

                    <i class="fa-solid fa-arrow-right"></i>

                    <div>
                        <span>CHECK-OUT</span>
                        <strong>
                            <?= date("d M Y", strtotime($booking["CHECK_out"])) ?>
                        </strong>
                    </div>

                </div>

                <div class="booking-price">

                    <span>Booking Status</span>
                    <strong><?= htmlspecialchars($status) ?></strong>

                    <span class="payment-label">Payment Status</span>
                    <strong class="payment-status <?= htmlspecialchars($paymentClass) ?>">
                        <?= htmlspecialchars($paymentStatus) ?>
                    </strong>

                    <?php if (strtolower($status) !== "cancelled"): ?>

                        <?php if (strtolower($paymentStatus) !== "paid"): ?>

                            <form
                                action="mybooking.php"
                                method="POST"
                                class="pay-form"
                                onsubmit="return confirm('Continue with the payment?');"
                            >
                                <input
                                    type="hidden"
                                    name="pay_booking_id"
                                    value="<?= htmlspecialchars($booking["id"]) ?>"
                                >

                                <button type="submit" class="pay-btn">
                                    <i class="fa-solid fa-credit-card"></i>
                                    Pay Now
                                </button>
                            </form>

                        <?php else: ?>

                            <div class="paid-message">
                                <i class="fa-solid fa-circle-check"></i>
                                Payment Successful
                            </div>

                        <?php endif; ?>

                        <form action="cancel_booking.php" method="POST" class="cancel-form">
                            <input
                                type="hidden"
                                name="booking_id"
                                value="<?= htmlspecialchars($booking["id"]) ?>"
                            >

                            <button type="submit" class="details-btn cancel-booking-btn">
                                Cancel Booking
                            </button>
                        </form>

                    <?php endif; ?>

                </div>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <div class="empty-bookings">
            <i class="fa-solid fa-calendar-xmark"></i>

            <h3>No Bookings Yet</h3>

            <p>You haven't made any bookings yet.</p>

            <a href="./room.html">Explore Rooms</a>
        </div>

    <?php endif; ?>

</main>

<footer>

    <div class="footer-container">

        <div class="footer-about">
            <div class="footer-logo">
                LUXURY
                <span>HOTELS</span>
            </div>

            <p>497 Evergreen Rd. Roseville, CA 95673</p>
            <p>+44 345 678 903</p>
            <p>luxury.hotels@gmail.com</p>

            <div class="social">
                <i class="fa-brands fa-facebook-f"></i>
                <i class="fa-brands fa-twitter"></i>
                <i class="fa-brands fa-instagram"></i>
            </div>
        </div>

        <div class="footer-links">
            <h4>About</h4>
            <a href="./home.php">Home</a>
            <a href="./room.html">Rooms</a>
            <a href="./facilities.html">Facilities</a>
            <a href="./contact.html">Contact Us</a>
        </div>

        <div class="footer-links">
            <h4>Information</h4>
            <a href="#">Terms & Conditions</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Refund Policy</a>
            <a href="#">FAQ</a>
        </div>

        <div class="newsletter">
            <h4>Subscribe to our newsletter</h4>

            <div class="subscribe">
                <input type="email" placeholder="Email Address">
                <button>SUBSCRIBE</button>
            </div>
        </div>

    </div>

    <div class="copyright">
        © 2026 Luxury Hotels. All Rights Reserved.
    </div>

</footer>

<script src="./js/mybooking.js"></script>

</body>
</html>

<?php mysqli_stmt_close($stmt); ?>
