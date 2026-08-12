<?php

session_start();

include __DIR__ . "/config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: mybooking.php");
    exit();
}

$user_id = (int) $_SESSION["user_id"];
$booking_id = (int) ($_POST["booking_id"] ?? 0);

if ($booking_id <= 0) {
    header("Location: mybooking.php");
    exit();
}

$sql = "UPDATE `booking`
        SET `STATUS` = 'cancelled'
        WHERE `id` = ? AND `user_id` = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "ii", $booking_id, $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: mybooking.php");
exit();

?>
