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

// Fetch the username
$sql = "SELECT username FROM Users WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

// Get the pet ID from the URL
if (!isset($_GET['petID']) || empty($_GET['petID'])) {
    header("location: index.php");
    exit;
}

$petID = intval($_GET['petID']);

// Fetch the pet details
$sql = "SELECT * FROM Pets WHERE petID = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $petID);
    $stmt->execute();
    $result = $stmt->get_result();
    $pet = $result->fetch_assoc();
    $stmt->close();
}

// Handle the booking
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookingDate = date("Y-m-d H:i:s");
    $status = 'pending';

    // Insert the booking
    $sql = "INSERT INTO Booking (userID, petID, bookingDate, status) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iiss", $user_id, $petID, $bookingDate, $status);
        if ($stmt->execute()) {
            // Update the pet status to 'not available for booking'
            $sqlUpdate = "UPDATE Pets SET status = 'not available for booking' WHERE petID = ?";
            if ($stmtUpdate = $conn->prepare($sqlUpdate)) {
                $stmtUpdate->bind_param("i", $petID);
                $stmtUpdate->execute();
                $stmtUpdate->close();
            }
            $message = "Booking successful!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5 mb-5">
    <h2>Booking Pet: <?php echo htmlspecialchars($pet['name']); ?></h2>
    <?php
    if (isset($message)) {
        echo '<div class="alert alert-info">' . htmlspecialchars($message) . '</div>';
    }
    ?>
    <div class="card mb-3">
        <div class="row no-gutters">
            <div class="col-md-4">
                <img src="admin/<?php echo htmlspecialchars($pet['imageURL']); ?>" class="card-img" alt="<?php echo htmlspecialchars($pet['name']); ?>">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($pet['name']); ?></h5>
                    <p class="card-text">Age: <?php echo htmlspecialchars($pet['age']); ?></p>
                    <p class="card-text">Price: $<?php echo htmlspecialchars($pet['price']); ?></p>
                    <p class="card-text">Breed: <?php echo htmlspecialchars($pet['breed']); ?></p>
                    <p class="card-text">Size: <?php echo htmlspecialchars($pet['size']); ?></p>
                    <p class="card-text">Color: <?php echo htmlspecialchars($pet['color']); ?></p>
                    <p class="card-text">Energy Level: <?php echo htmlspecialchars($pet['energyLevel']); ?></p>
                    <p class="card-text">Friendliness: <?php echo htmlspecialchars($pet['friendliness']); ?></p>
                    <p class="card-text">Ease of Training: <?php echo htmlspecialchars($pet['easeOfTraining']); ?></p>
                    <p class="card-text">Vendor Info: <?php echo htmlspecialchars($pet['vendorInfo']); ?></p>
                    <p class="card-text">Category: <?php echo htmlspecialchars($pet['category']); ?></p>
                    <p class="card-text">Nearby Area: <?php echo htmlspecialchars($pet['nearbyArea']); ?></p>
                    <p class="card-text">Status: <?php echo htmlspecialchars($pet['status']); ?></p>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
        </div>
        <button type="submit" class="btn btn-primary">Confirm Booking</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
