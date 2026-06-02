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

// Get the booking ID from the form submission
if (!isset($_POST['bookingID']) || empty($_POST['bookingID'])) {
    header("location: my_bookings.php");
    exit;
}
$bookingID = intval($_POST['bookingID']);

// Get the pet name and amount from the form submission
if (!isset($_POST['petName']) || empty($_POST['petName']) || !isset($_POST['amount']) || empty($_POST['amount'])) {
    header("location: my_bookings.php");
    exit;
}
$petName = $_POST['petName'];
$amount = $_POST['amount'];

// Stripe API keys
$stripe_secret_key = "YOUR_OWN_API_KEY";

\Stripe\Stripe::setApiKey($stripe_secret_key);

try {
    // Create a charge
    $charge = \Stripe\Charge::create([
        'amount' => $amount * 100, // Convert amount to cents
        'currency' => 'usd',
        'description' => 'Payment for ' . $petName,
        'source' => $_POST['stripeToken'],
    ]);

    // Payment successful, update booking status to 'approved' and payment status to 'paid'
    $sqlUpdate = "UPDATE Booking SET status = 'approved', paymentStatus = 'paid' WHERE bookingID = ?";
    if ($stmtUpdate = $conn->prepare($sqlUpdate)) {
        $stmtUpdate->bind_param("i", $bookingID);
        $stmtUpdate->execute();
        $stmtUpdate->close();
    }

    $message = "Payment successful!";
} catch (Exception $e) {
    $message = "Error: " . $e->getMessage();
}

$conn->close();

// Redirect back to the payment page with the message
header("location: my_bookings.php");
exit;
?>
