<?php
include('config.php');
require_once('stripe-php-master/init.php');

session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION["id"];

// Fetch the booking ID from the URL
if (!isset($_GET['bookingID']) || empty($_GET['bookingID'])) {
    header("location: my_bookings.php");
    exit;
}

$bookingID = intval($_GET['bookingID']);

// Fetch the booking details including the pet name and price
$sql = "SELECT b.*, p.name AS pet_name, p.price AS pet_price FROM Booking b INNER JOIN Pets p ON b.petID = p.petID WHERE b.bookingID = ? AND b.userID = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $bookingID, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $stmt->close();

    // If booking does not exist or does not belong to the user, redirect to my_bookings.php
    if (!$booking) {
        header("location: my_bookings.php");
        exit;
    }
}

// Stripe API keys
$stripe_public_key = 'pk_test_51PQinLRrUKhdzOsDnpHkYJbi0HZIsF9xOVIcPZtsAr4nbH5h1p3o1jblMCPoB0glvFG3o1pbxQZLSiKRHgvuZRMt009qg1bTkq';
$stripe_secret_key = 'sk_test_51PQinLRrUKhdzOsDK666N2V91NnsWKtb8mcYyrYwhPgDEheMluMygqAx0MnrgRTWyVwjMvRKsUjpxuyGvFFfuhKE00cD9K5EtD';

\Stripe\Stripe::setApiKey($stripe_secret_key);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Payment for Booking ID: <?php echo htmlspecialchars($bookingID); ?></h2>
    <?php
    if (isset($message)) {
        echo '<div class="alert alert-info">' . htmlspecialchars($message) . '</div>';
    }
    ?>
    <form action="charge.php" method="post">
        <input type="hidden" name="bookingID" value="<?php echo htmlspecialchars($bookingID); ?>">
        <input type="hidden" name="petName" value="<?php echo htmlspecialchars($booking['pet_name']); ?>">
        <input type="hidden" name="amount" value="<?php echo htmlspecialchars($booking['pet_price']); ?>">
        <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="<?php echo $stripe_public_key; ?>"
            data-amount="<?php echo $booking['pet_price'] * 100; ?>"
            data-name="Pet Booking - <?php echo htmlspecialchars($booking['pet_name']); ?>"
            data-description="Payment for <?php echo htmlspecialchars($booking['pet_name']); ?>"
            data-currency="usd"
            data-locale="auto">
        </script>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
