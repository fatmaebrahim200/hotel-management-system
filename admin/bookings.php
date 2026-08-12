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
   UPDATE / DELETE BOOKING
===================================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $booking_id = isset($_POST["booking_id"])
        ? (int) $_POST["booking_id"]
        : 0;

    $action = $_POST["action"] ?? "";


    /* =================================================
       CHECK BOOKING ID
    ================================================= */

    if ($booking_id > 0) {


        /* =============================================
           CONFIRM BOOKING
        ============================================= */

        if ($action === "confirm") {

            $status = "Confirmed";


            $stmt = mysqli_prepare(
                $conn,
                "UPDATE booking
                 SET STATUS = ?
                 WHERE id = ?"
            );


            if ($stmt) {

                mysqli_stmt_bind_param(
                    $stmt,
                    "si",
                    $status,
                    $booking_id
                );

                mysqli_stmt_execute($stmt);

                mysqli_stmt_close($stmt);

            }

        }


        /* =============================================
           CANCEL BOOKING
        ============================================= */

        elseif ($action === "cancel") {

            $status = "Cancelled";


            $stmt = mysqli_prepare(
                $conn,
                "UPDATE booking
                 SET STATUS = ?
                 WHERE id = ?"
            );


            if ($stmt) {

                mysqli_stmt_bind_param(
                    $stmt,
                    "si",
                    $status,
                    $booking_id
                );

                mysqli_stmt_execute($stmt);

                mysqli_stmt_close($stmt);

            }

        }


        /* =============================================
           DELETE BOOKING
        ============================================= */

        elseif ($action === "delete") {


            $stmt = mysqli_prepare(
                $conn,
                "DELETE FROM booking
                 WHERE id = ?"
            );


            if ($stmt) {

                mysqli_stmt_bind_param(
                    $stmt,
                    "i",
                    $booking_id
                );

                mysqli_stmt_execute($stmt);

                mysqli_stmt_close($stmt);

            }

        }

    }


    /* =============================================
       REFRESH PAGE
    ============================================= */

    header("Location: bookings.php");

    exit();

}


/* =====================================================
   GET STATISTICS
===================================================== */


/* =====================================================
   TOTAL BOOKINGS
===================================================== */

$totalQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM booking"
);

$totalBookings = 0;


if ($totalQuery) {

    $totalData = mysqli_fetch_assoc($totalQuery);

    $totalBookings = $totalData["total"];

}


/* =====================================================
   PENDING BOOKINGS
===================================================== */

$pendingQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM booking
     WHERE STATUS = 'Pending'"
);

$pendingBookings = 0;


if ($pendingQuery) {

    $pendingData = mysqli_fetch_assoc($pendingQuery);

    $pendingBookings = $pendingData["total"];

}


/* =====================================================
   CONFIRMED BOOKINGS
===================================================== */

$confirmedQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM booking
     WHERE STATUS = 'Confirmed'"
);

$confirmedBookings = 0;


if ($confirmedQuery) {

    $confirmedData = mysqli_fetch_assoc($confirmedQuery);

    $confirmedBookings = $confirmedData["total"];

}


/* =====================================================
   CANCELLED BOOKINGS
===================================================== */

$cancelledQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM booking
     WHERE STATUS = 'Cancelled'"
);

$cancelledBookings = 0;


if ($cancelledQuery) {

    $cancelledData = mysqli_fetch_assoc($cancelledQuery);

    $cancelledBookings = $cancelledData["total"];

}


/* =====================================================
   GET ALL BOOKINGS
===================================================== */

$query = "

    SELECT

        booking.id,

        booking.room_name,

        booking.CHECK_in,

        booking.CHECK_out,

        booking.guests,

        booking.STATUS,

        booking.created_at,

        users.name,

        users.email

    FROM booking

    LEFT JOIN users

        ON booking.user_id = users.id

    ORDER BY booking.id DESC

";


$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Bookings - Luxury Hotels Admin</title>


    <!-- =================================================
         FONT AWESOME
    ================================================= -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >


    <!-- =================================================
         GOOGLE FONT
    ================================================= -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <style>

        /* =================================================
           RESET
        ================================================= */

        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

            font-family: "Poppins", sans-serif;

        }


        /* =================================================
           BODY
        ================================================= */

        body {

            background: #f7f5f1;

            color: #333;

        }


        /* =================================================
           SIDEBAR
        ================================================= */

        .sidebar {

            width: 230px;

            height: 100vh;

            background: #1f2a38;

            position: fixed;

            left: 0;

            top: 0;

            padding: 25px 15px;

        }


        /* =================================================
           LOGO
        ================================================= */

        .logo {

            text-align: center;

            margin-bottom: 40px;

        }


        .logo h2 {

            color: #d6b77c;

            font-size: 24px;

            letter-spacing: 2px;

        }


        .logo span {

            color: white;

            font-size: 12px;

            letter-spacing: 4px;

        }


        /* =================================================
           MENU
        ================================================= */

        .menu a {

            display: block;

            text-decoration: none;

            color: white;

            padding: 14px 15px;

            margin-bottom: 8px;

            border-radius: 6px;

            transition: 0.3s;

        }


        .menu a i {

            width: 25px;

        }


        .menu a:hover,

        .menu a.active {

            background: #d6b77c;

            color: #1f2a38;

        }


        /* =================================================
           MAIN
        ================================================= */

        .main {

            margin-left: 230px;

            padding: 30px;

        }


        /* =================================================
           TOP BAR
        ================================================= */

        .topbar {

            background: white;

            padding: 20px 25px;

            border-radius: 10px;

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 25px;

            box-shadow: 0 2px 10px rgba(0,0,0,0.05);

        }


        .topbar h1 {

            color: #1f2a38;

            font-size: 26px;

        }


        .topbar p {

            color: #777;

            margin-top: 5px;

            font-size: 14px;

        }


        .admin {

            display: flex;

            align-items: center;

            gap: 10px;

        }


        .admin i {

            color: #d6b77c;

            font-size: 25px;

        }


        .admin span {

            font-weight: 600;

            color: #1f2a38;

        }


        /* =================================================
           STATISTICS
        ================================================= */

        .cards {

            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 20px;

            margin-bottom: 25px;

        }


        .card {

            background: white;

            padding: 22px;

            border-radius: 10px;

            box-shadow: 0 2px 10px rgba(0,0,0,0.05);

        }


        .card-icon {

            width: 45px;

            height: 45px;

            border-radius: 50%;

            background: #f1e6d2;

            display: flex;

            justify-content: center;

            align-items: center;

            color: #b18b4d;

            margin-bottom: 12px;

        }


        .card h3 {

            font-size: 13px;

            color: #777;

            margin-bottom: 5px;

        }


        .card h2 {

            color: #1f2a38;

            font-size: 26px;

        }


        /* =================================================
           SEARCH BOX
        ================================================= */

        .search-box {

            background: white;

            padding: 20px;

            border-radius: 10px;

            margin-bottom: 20px;

            box-shadow: 0 2px 10px rgba(0,0,0,0.05);

        }


        .search-wrapper {

            position: relative;

        }


        .search-wrapper i {

            position: absolute;

            left: 15px;

            top: 50%;

            transform: translateY(-50%);

            color: #999;

        }


        .search-input {

            width: 100%;

            padding: 12px 15px 12px 42px;

            border: 1px solid #ddd;

            border-radius: 7px;

            outline: none;

            font-size: 14px;

        }


        .search-input:focus {

            border-color: #d6b77c;

        }


        /* =================================================
           TABLE BOX
        ================================================= */

        .table-box {

            background: white;

            padding: 25px;

            border-radius: 10px;

            box-shadow: 0 2px 10px rgba(0,0,0,0.05);

        }


        .table-title {

            margin-bottom: 20px;

        }


        .table-title h2 {

            color: #1f2a38;

            font-size: 21px;

        }


        /* =================================================
           TABLE
        ================================================= */

        .table-container {

            overflow-x: auto;

        }


        table {

            width: 100%;

            border-collapse: collapse;

            min-width: 1200px;

        }


        th {

            background: #1f2a38;

            color: white;

            padding: 13px;

            text-align: left;

            font-size: 12px;

            white-space: nowrap;

        }


        td {

            padding: 13px;

            border-bottom: 1px solid #eee;

            font-size: 12px;

            vertical-align: middle;

        }


        tr:hover {

            background: #fafafa;

        }


        /* =================================================
           BOOKING ID
        ================================================= */

        .booking-id {

            color: #b18b4d;

            font-weight: 600;

        }


        /* =================================================
           CUSTOMER
        ================================================= */

        .customer-name {

            font-weight: 600;

            color: #1f2a38;

        }


        .customer-email {

            color: #888;

            font-size: 11px;

            margin-top: 2px;

        }


        /* =================================================
           STATUS
        ================================================= */

        .status {

            display: inline-block;

            padding: 6px 12px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: 600;

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


        /* =================================================
           ACTION BUTTONS
        ================================================= */

        .action-buttons {

            display: flex;

            gap: 6px;

            align-items: center;

        }


        .action-buttons form {

            display: inline;

        }


        .action-btn {

            border: none;

            padding: 7px 10px;

            border-radius: 5px;

            color: white;

            cursor: pointer;

            font-size: 11px;

            transition: 0.3s;

            white-space: nowrap;

        }


        .confirm-btn {

            background: #28a745;

        }


        .confirm-btn:hover {

            background: #218838;

        }


        .cancel-btn {

            background: #dc3545;

        }


        .cancel-btn:hover {

            background: #c82333;

        }


        .delete-btn {

            background: #1f2a38;

        }


        .delete-btn:hover {

            background: #000;

        }


        /* =================================================
           EMPTY MESSAGE
        ================================================= */

        .empty {

            text-align: center;

            padding: 50px;

            color: #777;

        }


        .empty i {

            font-size: 45px;

            color: #d6b77c;

            margin-bottom: 15px;

        }


        .empty p {

            margin-top: 10px;

        }


        /* =================================================
           RESPONSIVE
        ================================================= */

        @media (max-width: 1100px) {

            .cards {

                grid-template-columns: repeat(2, 1fr);

            }

        }


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


            .menu a {

                text-align: center;

            }


            .menu a i {

                width: auto;

            }


            .main {

                margin-left: 70px;

                padding: 15px;

            }


            .cards {

                grid-template-columns: 1fr;

            }


            .topbar {

                flex-direction: column;

                align-items: flex-start;

                gap: 15px;

            }


            .table-box {

                padding: 15px;

            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     SIDEBAR
===================================================== -->

<div class="sidebar">


    <div class="logo">

        <h2>LUXURY</h2>

        <span>HOTELS</span>

    </div>


    <div class="menu">


        <!-- DASHBOARD -->

        <a href="dashboard.php">

            <i class="fa-solid fa-chart-line"></i>

            <span>Dashboard</span>

        </a>


        <!-- USERS -->

        <a href="users.php">

            <i class="fa-solid fa-users"></i>

            <span>Users</span>

        </a>


        <!-- BOOKINGS -->

        <a
            href="bookings.php"
            class="active"
        >

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

            <h1>

                Bookings

            </h1>


            <p>

                Manage all hotel reservations

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


        <!-- TOTAL -->

        <div class="card">

            <div class="card-icon">

                <i class="fa-solid fa-calendar-days"></i>

            </div>


            <h3>

                Total Bookings

            </h3>


            <h2>

                <?php

                echo $totalBookings;

                ?>

            </h2>

        </div>



        <!-- PENDING -->

        <div class="card">

            <div class="card-icon">

                <i class="fa-solid fa-clock"></i>

            </div>


            <h3>

                Pending

            </h3>


            <h2>

                <?php

                echo $pendingBookings;

                ?>

            </h2>

        </div>



        <!-- CONFIRMED -->

        <div class="card">

            <div class="card-icon">

                <i class="fa-solid fa-circle-check"></i>

            </div>


            <h3>

                Confirmed

            </h3>


            <h2>

                <?php

                echo $confirmedBookings;

                ?>

            </h2>

        </div>



        <!-- CANCELLED -->

        <div class="card">

            <div class="card-icon">

                <i class="fa-solid fa-circle-xmark"></i>

            </div>


            <h3>

                Cancelled

            </h3>


            <h2>

                <?php

                echo $cancelledBookings;

                ?>

            </h2>

        </div>


    </div>



    <!-- =================================================
         SEARCH
    ================================================= -->

    <div class="search-box">


        <div class="search-wrapper">

            <i class="fa-solid fa-magnifying-glass"></i>


            <input
                type="text"
                id="searchInput"
                class="search-input"
                placeholder="Search by customer, email or room..."
            >

        </div>


    </div>



    <!-- =================================================
         BOOKINGS TABLE
    ================================================= -->

    <div class="table-box">


        <div class="table-title">

            <h2>

                All Bookings

            </h2>

        </div>


        <div class="table-container">


            <table id="bookingsTable">


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

                        <th>Created At</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody id="bookingsBody">


                <?php


                if (
                    $result &&
                    mysqli_num_rows($result) > 0
                ) {


                    while (
                        $row = mysqli_fetch_assoc($result)
                    ) {


                        $status = $row["STATUS"] ?? "Pending";


                        $statusClass = strtolower(
                            $status
                        );


                ?>


                    <tr>


                        <!-- ID -->

                        <td class="booking-id">

                            #

                            <?php

                            echo htmlspecialchars(
                                $row["id"]
                            );

                            ?>

                        </td>



                        <!-- CUSTOMER -->

                        <td>


                            <div class="customer-name">

                                <?php

                                echo htmlspecialchars(
                                    $row["name"] ?? "Unknown"
                                );

                                ?>

                            </div>


                            <div class="customer-email">

                                <?php

                                echo htmlspecialchars(
                                    $row["email"] ?? "-"
                                );

                                ?>

                            </div>


                        </td>



                        <!-- ROOM -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["room_name"] ?? "-"
                            );

                            ?>

                        </td>



                        <!-- CHECK IN -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["CHECK_in"] ?? "-"
                            );

                            ?>

                        </td>



                        <!-- CHECK OUT -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["CHECK_out"] ?? "-"
                            );

                            ?>

                        </td>



                        <!-- GUESTS -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["guests"] ?? "0"
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

                            <?php
                            $paymentStatus = $row["payment_status"] ?? "Unpaid";
                            $paymentClass = strtolower($paymentStatus);
                            ?>

                            <span class="status <?php echo htmlspecialchars($paymentClass); ?>">
                                <?php echo htmlspecialchars($paymentStatus); ?>
                            </span>

                        </td>



                        <!-- CREATED AT -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["created_at"] ?? "-"
                            );

                            ?>

                        </td>



                        <!-- ACTIONS -->

                        <td>


                            <div class="action-buttons">


                                <!-- =====================
                                     CONFIRM
                                ====================== -->

                                <form
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to confirm this booking?');"
                                >


                                    <input
                                        type="hidden"
                                        name="booking_id"
                                        value="<?php echo $row["id"]; ?>"
                                    >


                                    <input
                                        type="hidden"
                                        name="action"
                                        value="confirm"
                                    >


                                    <button
                                        type="submit"
                                        class="action-btn confirm-btn"
                                        title="Confirm Booking"
                                    >

                                        <i class="fa-solid fa-check"></i>

                                        Confirm

                                    </button>


                                </form>



                                <!-- =====================
                                     CANCEL
                                ====================== -->

                                <form
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to cancel this booking?');"
                                >


                                    <input
                                        type="hidden"
                                        name="booking_id"
                                        value="<?php echo $row["id"]; ?>"
                                    >


                                    <input
                                        type="hidden"
                                        name="action"
                                        value="cancel"
                                    >


                                    <button
                                        type="submit"
                                        class="action-btn cancel-btn"
                                        title="Cancel Booking"
                                    >

                                        <i class="fa-solid fa-xmark"></i>

                                        Cancel

                                    </button>


                                </form>



                                <!-- =====================
                                     DELETE
                                ====================== -->

                                <form
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to DELETE this booking?');"
                                >


                                    <input
                                        type="hidden"
                                        name="booking_id"
                                        value="<?php echo $row["id"]; ?>"
                                    >


                                    <input
                                        type="hidden"
                                        name="action"
                                        value="delete"
                                    >


                                    <button
                                        type="submit"
                                        class="action-btn delete-btn"
                                        title="Delete Booking"
                                    >

                                        <i class="fa-solid fa-trash"></i>

                                        Delete

                                    </button>


                                </form>


                            </div>


                        </td>


                    </tr>


                <?php


                    }


                } else {


                ?>


                    <tr>


                        <td colspan="10">


                            <div class="empty">


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



<!-- =====================================================
     SEARCH JAVASCRIPT
===================================================== -->

<script>

    const searchInput =
        document.getElementById("searchInput");


    const tableBody =
        document.getElementById("bookingsBody");


    searchInput.addEventListener(
        "input",
        function () {


            const searchValue =
                this.value.toLowerCase().trim();


            const rows =
                tableBody.querySelectorAll("tr");


            rows.forEach(function (row) {


                const rowText =
                    row.textContent.toLowerCase();


                if (
                    rowText.includes(searchValue)
                ) {

                    row.style.display = "";

                }

                else {

                    row.style.display = "none";

                }

            });

        }
    );

</script>


</body>

</html>