<?php
include('config.php');

session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION["id"];

// Fetch the user's bookings
$sql = "SELECT b.bookingID, b.petID, b.bookingDate, b.status, b.paymentStatus, p.name
        FROM Booking b
        INNER JOIN Pets p ON b.petID = p.petID
        WHERE b.userID = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Automatically cancel bookings not paid within three hours
$current_time = time();
$three_hours_ago = $current_time - (3 * 60 * 60); // 3 hours in seconds

foreach ($bookings as $booking) {
    if ($booking['paymentStatus'] === 'unpaid' && $booking['status'] === 'pending' && strtotime($booking['bookingDate']) < $three_hours_ago) {
        // Cancel the booking
        $bookingID = $booking['bookingID'];
        $sqlCancel = "UPDATE Booking SET status = 'cancelled', paymentStatus = 'unpaid' WHERE bookingID = ?";
        if ($stmtCancel = $conn->prepare($sqlCancel)) {
            $stmtCancel->bind_param("i", $bookingID);
            $stmtCancel->execute();
            $stmtCancel->close();
        }

        // Make the pet available for booking again
        $petID = $booking['petID'];
        $sqlUpdatePet = "UPDATE Pets SET status = 'available' WHERE petID = ?";
        if ($stmtUpdatePet = $conn->prepare($sqlUpdatePet)) {
            $stmtUpdatePet->bind_param("i", $petID);
            $stmtUpdatePet->execute();
            $stmtUpdatePet->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>My Bookings</h2>
    <?php
    if (isset($message)) {
        echo '<div class="alert alert-info">' . htmlspecialchars($message) . '</div>';
    }
    ?>
    <table class="table">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Pet Name</th>
                <th>Booking Date</th>
                <th>Status</th>
                <th>Payment Status</th>
                <th>Remaining Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($booking['bookingID']); ?></td>
                    <td><?php echo htmlspecialchars($booking['name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['bookingDate']); ?></td>
                    <td><?php echo htmlspecialchars($booking['status']); ?></td>
                    <td><?php echo htmlspecialchars($booking['paymentStatus']); ?></td>
                    <td>
                        <?php
                        if ($booking['paymentStatus'] === 'unpaid' && $booking['status'] === 'pending' && strtotime($booking['bookingDate']) >= $three_hours_ago) {
                            // Calculate remaining time
                            $bookingDate = strtotime($booking['bookingDate']);
                            $remainingTime = $bookingDate - $three_hours_ago;
                            $hours = floor($remainingTime / 3600);
                            $minutes = floor(($remainingTime % 3600) / 60);
                            $seconds = $remainingTime % 60;
                            echo "$hours:$minutes:$seconds";
                        } else {
                            echo "Payment Success On Time";
                        }
                        ?>
                    </td>
                    <td>
                        <?php if ($booking['paymentStatus'] === 'unpaid' && $booking['status'] === 'pending' && strtotime($booking['bookingDate']) >= $three_hours_ago) : ?>
                            <form method="POST" action="payment.php?bookingID=<?php echo htmlspecialchars($booking['bookingID']); ?>">
                                <button type="submit" class="btn btn-primary">Make Payment</button>
                            </form>
                        <?php endif; ?>
                    </td>
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
