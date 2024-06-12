<?php
include('config.php');

session_start();

// Check if user is logged in as admin, if not, redirect to login page
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch all bookings
$sql = "SELECT b.bookingID, b.userID, b.petID, b.bookingDate, b.status, b.paymentStatus, p.name AS petName, u.username
        FROM Booking b
        INNER JOIN Pets p ON b.petID = p.petID
        INNER JOIN Users u ON b.userID = u.id";
$result = $conn->query($sql);

// Check if there are any bookings
if ($result->num_rows > 0) {
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $bookings = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - All Bookings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>All Bookings</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>User</th>
                <th>Pet Name</th>
                <th>Booking Date</th>
                <th>Status</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($booking['bookingID']); ?></td>
                    <td><?php echo htmlspecialchars($booking['username']); ?></td>
                    <td><?php echo htmlspecialchars($booking['petName']); ?></td>
                    <td><?php echo htmlspecialchars($booking['bookingDate']); ?></td>
                    <td><?php echo htmlspecialchars($booking['status']); ?></td>
                    <td><?php echo htmlspecialchars($booking['paymentStatus']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
