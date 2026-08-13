<?php

session_start();

include "../config/db.php";

// =====================================================
// CHECK ADMIN LOGIN
// =====================================================

if (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== true) {

    header("Location: ../login.php");
    exit();

}


// =====================================================
// ADD USER
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_user"])) {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $nationality = trim($_POST["nationality"] ?? "");
    $date_of_birth = $_POST["date_of_birth"] ?? "";
    $gender = $_POST["gender"] ?? "";
    $password = $_POST["password"] ?? "";

    // -------------------------------------------------
    // VALIDATION
    // -------------------------------------------------

    if (
        $name === "" ||
        $email === "" ||
        $nationality === "" ||
        $date_of_birth === "" ||
        $gender === "" ||
        $password === ""
    ) {

        header("Location: users.php?error=empty");
        exit();

    }


    // -------------------------------------------------
    // EMAIL VALIDATION
    // -------------------------------------------------

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        header("Location: users.php?error=email");
        exit();

    }


    // -------------------------------------------------
    // PASSWORD VALIDATION
    // -------------------------------------------------

    if (strlen($password) < 6) {

        header("Location: users.php?error=password");
        exit();

    }


    // -------------------------------------------------
    // CHECK DUPLICATE EMAIL
    // -------------------------------------------------

    $check = mysqli_prepare(
        $conn,
        "SELECT id FROM users WHERE email = ?"
    );

    if (!$check) {

        header("Location: users.php?error=database");
        exit();

    }


    mysqli_stmt_bind_param(
        $check,
        "s",
        $email
    );

    mysqli_stmt_execute($check);

    $check_result = mysqli_stmt_get_result($check);


    if (mysqli_num_rows($check_result) > 0) {

        mysqli_stmt_close($check);

        header("Location: users.php?error=exists");
        exit();

    }


    mysqli_stmt_close($check);


    // -------------------------------------------------
    // HASH PASSWORD
    // -------------------------------------------------

    $hashed_password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );


    // -------------------------------------------------
    // INSERT USER
    // -------------------------------------------------

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO users
        (
            name,
            email,
            phone,
            nationality,
            date_of_birth,
            gender,
            PASSWORD
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );


    if (!$stmt) {

        header("Location: users.php?error=database");
        exit();

    }


    mysqli_stmt_bind_param(
        $stmt,
        "sssssss",
        $name,
        $email,
        $phone,
        $nationality,
        $date_of_birth,
        $gender,
        $hashed_password
    );


    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        header("Location: users.php?success=added");
        exit();

    } else {

        mysqli_stmt_close($stmt);

        header("Location: users.php?error=database");
        exit();

    }

}


// =====================================================
// DELETE USER
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {

    $delete_id = (int) $_POST["delete_id"];


    if ($delete_id > 0) {

        $stmt = mysqli_prepare(
            $conn,
            "DELETE FROM users WHERE id = ?"
        );


        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $delete_id
            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);

        }

    }


    header("Location: users.php");
    exit();

}


// =====================================================
// GET ALL USERS
// =====================================================

$query = "
    SELECT
        id,
        name,
        email,
        phone,
        nationality,
        gender,
        date_of_birth
    FROM users
    ORDER BY id DESC
";


$result = mysqli_query(
    $conn,
    $query
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

<title>
    Users - Luxury Hotels
</title>


<!-- FONT AWESOME -->

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
>


<style>

/* =====================================================
   RESET
===================================================== */

* {

    margin: 0;
    padding: 0;
    box-sizing: border-box;

    font-family: Arial, sans-serif;

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

    width: 230px;
    height: 100vh;

    background: #1f2a38;

    position: fixed;

    left: 0;
    top: 0;

    padding: 25px 15px;

}


/* =====================================================
   LOGO
===================================================== */

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


/* =====================================================
   MENU
===================================================== */

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


/* =====================================================
   MAIN
===================================================== */

.main {

    margin-left: 230px;

    padding: 30px;

}


/* =====================================================
   TOP BAR
===================================================== */

.topbar {

    background: white;

    padding: 18px 25px;

    border-radius: 10px;

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 30px;

    box-shadow:
        0 2px 10px rgba(0, 0, 0, 0.05);

}


.topbar h1 {

    font-size: 26px;

    color: #1f2a38;

}


.topbar p {

    color: #777;

    margin-top: 5px;

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

    font-weight: bold;

}


/* =====================================================
   USERS BOX
===================================================== */

.users-box {

    background: white;

    padding: 25px;

    border-radius: 10px;

    box-shadow:
        0 2px 10px rgba(0, 0, 0, 0.05);

}


.users-title {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 25px;

}


.users-title h2 {

    color: #1f2a38;

    font-size: 22px;

}


.users-title span {

    color: #777;

    font-size: 14px;

}


/* =====================================================
   ADD USER BUTTON
===================================================== */

.add-user-btn {

    background: #d6b77c;

    color: #1f2a38;

    border: none;

    padding: 11px 18px;

    border-radius: 6px;

    cursor: pointer;

    font-weight: bold;

    transition: 0.3s;

}


.add-user-btn:hover {

    background: #c4a568;

}


/* =====================================================
   ADD USER FORM
===================================================== */

.add-user-form {

    display: none;

    background: #fafafa;

    padding: 25px;

    margin-bottom: 25px;

    border-radius: 8px;

    border: 1px solid #eee;

}


.add-user-form h3 {

    margin-bottom: 20px;

    color: #1f2a38;

}


.form-grid {

    display: grid;

    grid-template-columns: repeat(2, 1fr);

    gap: 18px;

}


.form-group {

    display: flex;

    flex-direction: column;

}


.form-group label {

    margin-bottom: 7px;

    font-weight: bold;

    font-size: 14px;

}


.form-group input,
.form-group select {

    width: 100%;

    padding: 11px;

    border: 1px solid #ddd;

    border-radius: 5px;

    outline: none;

}


.form-group input:focus,
.form-group select:focus {

    border-color: #d6b77c;

}


/* =====================================================
   FORM BUTTONS
===================================================== */

.form-buttons {

    margin-top: 20px;

    display: flex;

    gap: 10px;

}


.save-btn {

    background: #1f2a38;

    color: white;

    border: none;

    padding: 11px 18px;

    border-radius: 5px;

    cursor: pointer;

}


.save-btn:hover {

    background: #2d3c4f;

}


.cancel-btn {

    background: #ddd;

    color: #333;

    border: none;

    padding: 11px 18px;

    border-radius: 5px;

    cursor: pointer;

}


.cancel-btn:hover {

    background: #ccc;

}


/* =====================================================
   MESSAGES
===================================================== */

.message {

    padding: 12px 15px;

    border-radius: 6px;

    margin-bottom: 20px;

    font-size: 14px;

}


.success {

    background: #d4edda;

    color: #155724;

}


.error {

    background: #f8d7da;

    color: #721c24;

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

    background: #f7f5f1;

    color: #555;

    padding: 14px;

    text-align: left;

    font-size: 14px;

}


td {

    padding: 14px;

    border-bottom: 1px solid #eee;

    font-size: 14px;

}


tr:hover {

    background: #fafafa;

}


/* =====================================================
   USER ICON
===================================================== */

.user-info {

    display: flex;

    align-items: center;

    gap: 10px;

}


.user-icon {

    width: 38px;

    height: 38px;

    border-radius: 50%;

    background: #f1e6d2;

    color: #b18b4d;

    display: flex;

    align-items: center;

    justify-content: center;

}


/* =====================================================
   DELETE BUTTON
===================================================== */

.delete-btn {

    border: none;

    background: #f8d7da;

    color: #721c24;

    padding: 8px 12px;

    border-radius: 5px;

    cursor: pointer;

    font-size: 13px;

}


.delete-btn:hover {

    background: #e9aeb3;

}


/* =====================================================
   EMPTY
===================================================== */

.empty {

    text-align: center;

    padding: 40px;

    color: #777;

}


.empty i {

    font-size: 35px;

    color: #d6b77c;

    margin-bottom: 15px;

}


/* =====================================================
   RESPONSIVE
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


    .topbar {

        flex-direction: column;

        gap: 15px;

        align-items: flex-start;

    }


    .users-title {

        flex-direction: column;

        align-items: flex-start;

        gap: 15px;

    }


    .form-grid {

        grid-template-columns: 1fr;

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

            <i class="fa-solid fa-house"></i>

            <span>
                Dashboard
            </span>

        </a>


        <!-- USERS -->

        <a
            href="users.php"
            class="active"
        >

            <i class="fa-solid fa-users"></i>

            <span>
                Users
            </span>

        </a>


        <!-- BOOKINGS -->

        <a href="bookings.php">

            <i class="fa-solid fa-calendar-check"></i>

            <span>
                Bookings
            </span>

        </a>


        <!-- WEBSITE -->

        <a href="../home.php">

            <i class="fa-solid fa-globe"></i>

            <span>
                Website
            </span>

        </a>


        <!-- LOGOUT -->

        <a href="../logout.php">

            <i class="fa-solid fa-right-from-bracket"></i>

            <span>
                Logout
            </span>

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
                Users
            </h1>

            <p>
                Manage hotel users
            </p>

        </div>


        <div class="admin">

            <i class="fa-solid fa-circle-user"></i>

            <span>
                Admin
            </span>

        </div>

    </div>


    <!-- =================================================
         USERS BOX
    ================================================= -->

    <div class="users-box">


        <!-- =================================================
             TITLE
        ================================================= -->

        <div class="users-title">

            <div>

                <h2>
                    All Users
                </h2>

                <span>

                    <?php

                    if ($result) {

                        echo mysqli_num_rows($result);

                    } else {

                        echo "0";

                    }

                    ?>

                    Users

                </span>

            </div>


            <!-- ADD USER BUTTON -->

            <button
                type="button"
                class="add-user-btn"
                onclick="document.getElementById('addUserForm').style.display='block';"
            >

                <i class="fa-solid fa-user-plus"></i>

                Add User

            </button>

        </div>


        <!-- =================================================
             SUCCESS / ERROR MESSAGE
        ================================================= -->

        <?php if (isset($_GET["success"]) && $_GET["success"] === "added") { ?>

            <div class="message success">

                <i class="fa-solid fa-circle-check"></i>

                User added successfully.

            </div>

        <?php } ?>


        <?php if (isset($_GET["error"])) { ?>

            <div class="message error">

                <?php

                if ($_GET["error"] === "empty") {

                    echo "Please fill in all required fields.";

                } elseif ($_GET["error"] === "email") {

                    echo "Please enter a valid email.";

                } elseif ($_GET["error"] === "password") {

                    echo "Password must be at least 6 characters.";

                } elseif ($_GET["error"] === "exists") {

                    echo "This email already exists.";

                } else {

                    echo "Something went wrong. Please try again.";

                }

                ?>

            </div>

        <?php } ?>


        <!-- =================================================
             ADD USER FORM
        ================================================= -->

        <div
            id="addUserForm"
            class="add-user-form"
        >

            <h3>
                Add New User
            </h3>


            <form method="POST">


                <input
                    type="hidden"
                    name="add_user"
                    value="1"
                >


                <div class="form-grid">


                    <!-- NAME -->

                    <div class="form-group">

                        <label>
                            Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            placeholder="Enter full name"
                            required
                        >

                    </div>


                    <!-- EMAIL -->

                    <div class="form-group">

                        <label>
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            placeholder="example@email.com"
                            required
                        >

                    </div>


                    <!-- PHONE -->

                    <div class="form-group">

                        <label>
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            placeholder="Enter phone number"
                        >

                    </div>


                    <!-- NATIONALITY -->

                    <div class="form-group">

                        <label>
                            Nationality
                        </label>

                        <input
                            type="text"
                            name="nationality"
                            placeholder="Enter nationality"
                            required
                        >

                    </div>


                    <!-- DATE OF BIRTH -->

                    <div class="form-group">

                        <label>
                            Date of Birth
                        </label>

                        <input
                            type="date"
                            name="date_of_birth"
                            required
                        >

                    </div>


                    <!-- GENDER -->

                    <div class="form-group">

                        <label>
                            Gender
                        </label>

                        <select
                            name="gender"
                            required
                        >

                            <option value="">
                                Select Gender
                            </option>

                            <option value="Male">
                                Male
                            </option>

                            <option value="Female">
                                Female
                            </option>

                        </select>

                    </div>


                    <!-- PASSWORD -->

                    <div class="form-group">

                        <label>
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            placeholder="Minimum 6 characters"
                            minlength="6"
                            required
                        >

                    </div>


                </div>


                <!-- BUTTONS -->

                <div class="form-buttons">


                    <button
                        type="submit"
                        class="save-btn"
                    >

                        <i class="fa-solid fa-user-plus"></i>

                        Add User

                    </button>


                    <button
                        type="button"
                        class="cancel-btn"
                        onclick="document.getElementById('addUserForm').style.display='none';"
                    >

                        Cancel

                    </button>


                </div>


            </form>

        </div>


        <!-- =================================================
             USERS TABLE
        ================================================= -->

        <div class="table-container">


            <table>


                <thead>

                    <tr>

                        <th>
                            ID
                        </th>

                        <th>
                            Name
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Phone
                        </th>

                        <th>
                            Nationality
                        </th>

                        <th>
                            Gender
                        </th>

                        <th>
                            Date of Birth
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php

                if (
                    $result &&
                    mysqli_num_rows($result) > 0
                ) {

                    while (
                        $user = mysqli_fetch_assoc($result)
                    ) {

                ?>


                    <tr>


                        <!-- ID -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $user["id"]
                            );

                            ?>

                        </td>


                        <!-- NAME -->

                        <td>

                            <div class="user-info">


                                <div class="user-icon">

                                    <i class="fa-solid fa-user"></i>

                                </div>


                                <span>

                                    <?php

                                    echo htmlspecialchars(
                                        $user["name"]
                                    );

                                    ?>

                                </span>


                            </div>

                        </td>


                        <!-- EMAIL -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $user["email"]
                            );

                            ?>

                        </td>


                        <!-- PHONE -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $user["phone"] ?? "-"
                            );

                            ?>

                        </td>


                        <!-- NATIONALITY -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $user["nationality"] ?? "-"
                            );

                            ?>

                        </td>


                        <!-- GENDER -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $user["gender"] ?? "-"
                            );

                            ?>

                        </td>


                        <!-- DATE OF BIRTH -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $user["date_of_birth"] ?? "-"
                            );

                            ?>

                        </td>


                        <!-- DELETE -->

                        <td>


                            <form
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this user?');"
                            >


                                <input
                                    type="hidden"
                                    name="delete_id"
                                    value="<?php echo (int)$user["id"]; ?>"
                                >


                                <button
                                    type="submit"
                                    class="delete-btn"
                                >

                                    <i class="fa-solid fa-trash"></i>

                                    Delete

                                </button>


                            </form>


                        </td>


                    </tr>


                <?php

                    }

                } else {

                ?>


                    <tr>

                        <td
                            colspan="8"
                            class="empty"
                        >

                            <i class="fa-solid fa-users"></i>

                            <br>

                            No users found.

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