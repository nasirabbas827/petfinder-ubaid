<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle delete request
if (isset($_GET['delete'])) {
    $petID = intval($_GET['delete']);
    $sql = "DELETE FROM Pets WHERE petID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $petID);
        $stmt->execute();
        $stmt->close();
        $message = "Pet deleted successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch all pets
$sql = "SELECT * FROM Pets";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - View Pets</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>View All Pets</h2>
    <?php
    if (isset($message)) {
        echo '<div class="alert alert-info">' . $message . '</div>';
    }
    ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Price</th>
                <th>Breed</th>
                <th>Size</th>
                <th>Color</th>
                <th>Energy Level</th>
                <th>Friendliness</th>
                <th>Ease of Training</th>
                <th>Status</th>
                <th>Image</th>
                <th>Vendor Info</th>
                <th>Category</th>
                <th>Nearby Area</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['petID']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['breed']; ?></td>
                <td><?php echo $row['size']; ?></td>
                <td><?php echo $row['color']; ?></td>
                <td><?php echo $row['energyLevel']; ?></td>
                <td><?php echo $row['friendliness']; ?></td>
                <td><?php echo $row['easeOfTraining']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><img src="<?php echo $row['imageURL']; ?>" alt="<?php echo $row['name']; ?>" width="50"></td>
                <td><?php echo $row['vendorInfo']; ?></td>
                <td><?php echo $row['category']; ?></td>
                <td><?php echo $row['nearbyArea']; ?></td>
                <td>
                    <a href="edit_pet.php?petID=<?php echo $row['petID']; ?>" class="mb-2 btn btn-warning btn-sm">Edit</a>
                    <a href="view_pets.php?delete=<?php echo $row['petID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this pet?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
