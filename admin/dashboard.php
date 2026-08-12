```php
<?php

session_start();

include "../config/db.php";

/* =====================================================
   CHECK ADMIN LOGIN
===================================================== */

if (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== true) {
    header("Location: ../login.php");
    exit();
}


/* =====================================================
   STATISTICS
===================================================== */


/* =========================
   TOTAL USERS
========================= */

$totalUsers = 0;

$usersQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM users"
);

if ($usersQuery) {
    $usersData = mysqli_fetch_assoc($usersQuery);
    $totalUsers = (int)$usersData["total"];
}


/* =========================
   TOTAL BOOKINGS
========================= */

$totalBookings = 0;

$bookingsQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM booking"
);

if ($bookingsQuery) {
    $bookingsData = mysqli_fetch_assoc($bookingsQuery);
    $totalBookings = (int)$bookingsData["total"];
}


/* =========================
   PENDING BOOKINGS
========================= */

$pendingBookings = 0;

$pendingQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM booking
     WHERE status = 'Pending'"
);

if ($pendingQuery) {
    $pendingData = mysqli_fetch_assoc($pendingQuery);
    $pendingBookings = (int)$pendingData["total"];
}


/* =========================
   PAID BOOKINGS
========================= */

$paidBookings = 0;

$paidQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM booking
     WHERE payment_status = 'Paid'"
);

if ($paidQuery) {
    $paidData = mysqli_fetch_assoc($paidQuery);
    $paidBookings = (int)$paidData["total"];
}


/* =========================
   TOTAL ROOMS
   ROOMS ARE STATIC
========================= */

$totalRooms = 15;


/* =====================================================
   RECENT BOOKINGS
===================================================== */

$recentQuery = "

    SELECT

        booking.id,

        booking.room_name,

        booking.CHECK_in AS check_in,

        booking.CHECK_out AS check_out,

        booking.guests,

        booking.status AS status,

        booking.payment_status,

        users.name AS user_name,

        users.email AS user_email

    FROM booking

    LEFT JOIN users
        ON booking.user_id = users.id

    ORDER BY booking.id DESC

    LIMIT 5

";


$recentBookings = mysqli_query(
    $conn,
    $recentQuery
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard - Luxury Hotels</title>


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >


    <!-- Google Font -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <style>

        /* =====================================================
           RESET
        ===================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }


        /* =====================================================
           BODY
        ===================================================== */

        body {
            background: #f7f5f1;
            color: #333;
        }


        /* =====================================================
           SIDEBAR
        ===================================================== */

        .sidebar {
            width: 240px;
            height: 100vh;
            background: #1f2a38;
            position: fixed;
            left: 0;
            top: 0;
            padding: 28px 16px;
            z-index: 1000;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.08);
        }


        /* =====================================================
           LOGO
        ===================================================== */

        .logo {
            text-align: center;
            margin-bottom: 45px;
            padding-bottom: 25px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }


        .logo h2 {
            color: #d6b77c;
            font-size: 25px;
            letter-spacing: 3px;
            font-weight: 700;
        }


        .logo span {
            color: #ffffff;
            font-size: 11px;
            letter-spacing: 5px;
            opacity: 0.85;
        }


        /* =====================================================
           MENU
        ===================================================== */

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }


        .menu a {
            display: flex;
            align-items: center;
            gap: 14px;

            text-decoration: none;

            color: #ffffff;

            padding: 14px 16px;

            border-radius: 8px;

            transition: all 0.3s ease;

            font-size: 14px;

            font-weight: 500;
        }


        .menu a i {
            width: 22px;
            text-align: center;
            font-size: 16px;
        }


        .menu a:hover {
            background: rgba(214, 183, 124, 0.15);
            color: #d6b77c;
            transform: translateX(3px);
        }


        .menu a.active {
            background: #d6b77c;
            color: #1f2a38;
            font-weight: 600;
        }


        .menu a.active:hover {
            background: #d6b77c;
            color: #1f2a38;
            transform: none;
        }


        /* =====================================================
           MAIN
        ===================================================== */

        .main {
            margin-left: 240px;
            padding: 30px;
            min-height: 100vh;
        }


        /* =====================================================
           TOP BAR
        ===================================================== */

        .topbar {
            background: #ffffff;

            padding: 22px 28px;

            border-radius: 12px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-bottom: 28px;

            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        }


        .topbar h1 {
            color: #1f2a38;
            font-size: 27px;
            font-weight: 700;
        }


        .topbar p {
            color: #888;
            margin-top: 5px;
            font-size: 13px;
        }


        /* =====================================================
           ADMIN
        ===================================================== */

        .admin {
            display: flex;
            align-items: center;
            gap: 10px;

            background: #f7f5f1;

            padding: 9px 15px;

            border-radius: 30px;
        }


        .admin i {
            color: #b18b4d;
            font-size: 25px;
        }


        .admin span {
            font-weight: 600;
            color: #1f2a38;
            font-size: 14px;
        }


        /* =====================================================
           CARDS
        ===================================================== */

        .cards {
            display: grid;

            grid-template-columns: repeat(5, 1fr);

            gap: 18px;

            margin-bottom: 28px;
        }


        .card {
            background: #ffffff;

            padding: 22px;

            border-radius: 12px;

            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);

            transition: all 0.3s ease;

            border: 1px solid rgba(0,0,0,0.02);
        }


        .card:hover {
            transform: translateY(-5px);

            box-shadow:
                0 8px 25px rgba(0, 0, 0, 0.08);
        }


        .card-icon {
            width: 48px;
            height: 48px;

            border-radius: 12px;

            background: #f1e6d2;

            display: flex;

            justify-content: center;

            align-items: center;

            color: #b18b4d;

            font-size: 19px;

            margin-bottom: 16px;
        }


        .card h3 {
            font-size: 13px;

            color: #777;

            margin-bottom: 7px;

            font-weight: 500;
        }


        .card h2 {
            color: #1f2a38;

            font-size: 27px;

            font-weight: 700;
        }


        /* =====================================================
           RECENT BOOKINGS BOX
        ===================================================== */

        .table-box {
            background: #ffffff;

            padding: 25px;

            border-radius: 12px;

            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        }


        /* =====================================================
           TABLE TITLE
        ===================================================== */

        .table-title {
            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 20px;
        }


        .table-title h2 {
            color: #1f2a38;

            font-size: 20px;

            font-weight: 600;
        }


        .view-all {
            text-decoration: none;

            color: #b18b4d;

            font-weight: 600;

            font-size: 13px;

            padding: 8px 14px;

            border-radius: 6px;

            transition: 0.3s;
        }


        .view-all:hover {
            background: #f1e6d2;
        }


        /* =====================================================
           TABLE
        ===================================================== */

        .table-container {
            overflow-x: auto;
        }


        table {
            width: 100%;

            border-collapse: collapse;

            min-width: 850px;
        }


        th {
            text-align: left;

            background: #f7f5f1;

            padding: 14px;

            color: #555;

            font-size: 12px;

            font-weight: 600;

            white-space: nowrap;
        }


        td {
            padding: 14px;

            border-bottom: 1px solid #eeeeee;

            font-size: 12px;

            color: #555;

            white-space: nowrap;
        }


        tbody tr {
            transition: 0.2s;
        }


        tbody tr:hover {
            background: #fcfbf9;
        }


        /* =====================================================
           STATUS
        ===================================================== */

        .status {
            padding: 6px 11px;

            border-radius: 20px;

            font-size: 10px;

            font-weight: 600;

            display: inline-block;

            text-transform: capitalize;
        }


        .pending {
            background: #fff3cd;

            color: #856404;
        }


        .confirmed {
            background: #d4edda;

            color: #155724;
        }


        .cancelled {
            background: #f8d7da;

            color: #721c24;
        }


        .paid {
            background: #dff5e5;

            color: #176b35;
        }


        .unpaid {
            background: #fff3cd;

            color: #856404;
        }


        /* =====================================================
           NO BOOKINGS
        ===================================================== */

        .no-bookings {
            text-align: center;

            padding: 45px;

            color: #777;
        }


        .no-bookings i {
            font-size: 42px;

            color: #d6b77c;

            margin-bottom: 15px;
        }


        .no-bookings p {
            margin-top: 8px;

            font-size: 14px;
        }


        /* =====================================================
           RESPONSIVE - 1200
        ===================================================== */

        @media (max-width: 1250px) {

            .cards {
                grid-template-columns: repeat(3, 1fr);
            }

        }


        /* =====================================================
           RESPONSIVE - 950
        ===================================================== */

        @media (max-width: 950px) {

            .sidebar {
                width: 200px;
            }


            .main {
                margin-left: 200px;
            }


            .cards {
                grid-template-columns: repeat(2, 1fr);
            }

        }


        /* =====================================================
           RESPONSIVE - 700
        ===================================================== */

        @media (max-width: 700px) {

            .sidebar {
                width: 70px;

                padding: 20px 10px;
            }


            .logo h2,
            .logo span,
            .menu a span {
                display: none;
            }


            .logo {
                margin-bottom: 30px;

                padding-bottom: 20px;
            }


            .menu a {
                justify-content: center;

                padding: 14px 8px;
            }


            .menu a i {
                width: auto;
            }


            .main {
                margin-left: 70px;

                padding: 15px;
            }


            .topbar {
                padding: 18px;

                flex-direction: column;

                align-items: flex-start;

                gap: 15px;
            }


            .topbar h1 {
                font-size: 23px;
            }


            .cards {
                grid-template-columns: 1fr;

                gap: 14px;
            }


            .card {
                padding: 20px;
            }


            .table-box {
                padding: 18px;
            }


            .table-title h2 {
                font-size: 18px;
            }

        }


        /* =====================================================
           VERY SMALL SCREENS
        ===================================================== */

        @media (max-width: 450px) {

            .main {
                padding: 10px;
            }


            .topbar {
                border-radius: 8px;
            }


            .table-box {
                border-radius: 8px;

                padding: 12px;
            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     SIDEBAR
===================================================== -->

<div class="sidebar">


    <!-- LOGO -->

    <div class="logo">

        <h2>LUXURY</h2>

        <span>HOTELS</span>

    </div>


    <!-- MENU -->

    <div class="menu">


        <!-- DASHBOARD -->

        <a
            href="dashboard.php"
            class="active"
        >

            <i class="fa-solid fa-chart-line"></i>

            <span>Dashboard</span>

        </a>


        <!-- USERS -->

        <a href="users.php">

            <i class="fa-solid fa-users"></i>

            <span>Users</span>

        </a>


        <!-- BOOKINGS -->

        <a href="bookings.php">

            <i class="fa-solid fa-calendar-check"></i>

            <span>Bookings</span>

        </a>


        <!-- WEBSITE -->

        <a href="../home.php">

            <i class="fa-solid fa-globe"></i>

            <span>Website</span>

        </a>


        <!-- LOGOUT -->

        <a href="../logout.php">

            <i class="fa-solid fa-right-from-bracket"></i>

            <span>Logout</span>

        </a>


    </div>

</div>


<!-- =====================================================
     MAIN
===================================================== -->

<div class="main">


    <!-- =================================================
         TOP BAR
    ================================================= -->

    <div class="topbar">


        <div>

            <h1>Dashboard</h1>

            <p>
                Welcome to Luxury Hotels Admin Panel
            </p>

        </div>


        <div class="admin">

            <i class="fa-solid fa-circle-user"></i>

            <span>Admin</span>

        </div>


    </div>


    <!-- =================================================
         STATISTICS
    ================================================= -->

    <div class="cards">


        <!-- TOTAL USERS -->

        <div class="card">

            <div class="card-icon">

                <i class="fa-solid fa-users"></i>

            </div>

            <h3>Total Users</h3>

            <h2>
                <?php echo htmlspecialchars($totalUsers); ?>
            </h2>

        </div>


        <!-- TOTAL BOOKINGS -->

        <div class="card">

            <div class="card-icon">

                <i class="fa-solid fa-calendar-check"></i>

            </div>

            <h3>Total Bookings</h3>

            <h2>
                <?php echo htmlspecialchars($totalBookings); ?>
            </h2>

        </div>


        <!-- PAID BOOKINGS -->

        <div class="card">

            <div class="card-icon">

                <i class="fa-solid fa-credit-card"></i>

            </div>

            <h3>Paid Bookings</h3>

            <h2>
                <?php echo htmlspecialchars($paidBookings); ?>
            </h2>

        </div>


        <!-- PENDING BOOKINGS -->

        <div class="card">

            <div class="card-icon">

                <i class="fa-solid fa-clock"></i>

            </div>

            <h3>Pending Bookings</h3>

            <h2>
                <?php echo htmlspecialchars($pendingBookings); ?>
            </h2>

        </div>


        <!-- TOTAL ROOMS -->

        <div class="card">

            <div class="card-icon">

                <i class="fa-solid fa-bed"></i>

            </div>

            <h3>Total Rooms</h3>

            <h2>
                <?php echo $totalRooms; ?>
            </h2>

        </div>


    </div>


    <!-- =================================================
         RECENT BOOKINGS
    ================================================= -->

    <div class="table-box">


        <div class="table-title">

            <h2>
                Recent Bookings
            </h2>


            <a
                href="bookings.php"
                class="view-all"
            >
                View All
            </a>

        </div>


        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Customer</th>

                        <th>Room</th>

                        <th>Check In</th>

                        <th>Check Out</th>

                        <th>Guests</th>

                        <th>Status</th>

                        <th>Payment</th>

                    </tr>

                </thead>


                <tbody>


                <?php

                if (
                    $recentBookings &&
                    mysqli_num_rows($recentBookings) > 0
                ) {

                    while (
                        $booking = mysqli_fetch_assoc(
                            $recentBookings
                        )
                    ) {

                        $status = $booking["status"] ?? "Pending";

                        $statusClass = strtolower(
                            trim($status)
                        );


                        $paymentStatus =
                            $booking["payment_status"]
                            ?? "Unpaid";

                        $paymentClass = strtolower(
                            trim($paymentStatus)
                        );

                ?>


                    <tr>


                        <!-- ID -->

                        <td>

                            #

                            <?php

                            echo htmlspecialchars(
                                $booking["id"]
                            );

                            ?>

                        </td>


                        <!-- CUSTOMER -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $booking["user_name"]
                                ?? "Unknown"
                            );

                            ?>

                        </td>


                        <!-- ROOM -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $booking["room_name"]
                                ?? "-"
                            );

                            ?>

                        </td>


                        <!-- CHECK IN -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $booking["check_in"]
                                ?? "-"
                            );

                            ?>

                        </td>


                        <!-- CHECK OUT -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $booking["check_out"]
                                ?? "-"
                            );

                            ?>

                        </td>


                        <!-- GUESTS -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $booking["guests"]
                                ?? "0"
                            );

                            ?>

                        </td>


                        <!-- STATUS -->

                        <td>

                            <span
                                class="status <?php echo htmlspecialchars($statusClass); ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    $status
                                );

                                ?>

                            </span>

                        </td>


                        <!-- PAYMENT -->

                        <td>

                            <span
                                class="status <?php echo htmlspecialchars($paymentClass); ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    $paymentStatus
                                );

                                ?>

                            </span>

                        </td>


                    </tr>


                <?php

                    }

                } else {

                ?>


                    <tr>

                        <td colspan="8">

                            <div class="no-bookings">

                                <i
                                    class="fa-solid fa-calendar-xmark"
                                ></i>

                                <p>
                                    No bookings found.
                                </p>

                            </div>

                        </td>

                    </tr>


                <?php

                }

                ?>


                </tbody>

            </table>

        </div>

    </div>


</div>


</body>

</html>
```
