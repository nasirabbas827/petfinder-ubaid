<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Query to get total number of users
$sqlUsers = "SELECT COUNT(*) AS totalUsers FROM Users";
$resultUsers = $conn->query($sqlUsers);
$totalUsers = $resultUsers->fetch_assoc()["totalUsers"];

// Query to get total number of pets
$sqlPets = "SELECT COUNT(*) AS totalPets FROM Pets";
$resultPets = $conn->query($sqlPets);
$totalPets = $resultPets->fetch_assoc()["totalPets"];

// Query to get total number of bookings
$sqlBookings = "SELECT COUNT(*) AS totalBookings FROM Booking";
$resultBookings = $conn->query($sqlBookings);
$totalBookings = $resultBookings->fetch_assoc()["totalBookings"];

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text"><?php echo $totalUsers; ?></p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Pets</h5>
                    <p class="card-text"><?php echo $totalPets; ?></p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Bookings</h5>
                    <p class="card-text"><?php echo $totalBookings; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
