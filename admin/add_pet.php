<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $price = $_POST['price'];
    $breed = $_POST['breed'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $energyLevel = $_POST['energyLevel'];
    $friendliness = $_POST['friendliness'];
    $easeOfTraining = $_POST['easeOfTraining'];
    $status = $_POST['status'];
    $vendorInfo = $_POST['vendorInfo'];
    $category = $_POST['category'];
    $nearbyArea = $_POST['nearbyArea'];

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 500000) { // 500KB max size
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $imageURL = $target_file;
        } else {
            $uploadOk = 0;
        }
    } else {
        $imageURL = "";
    }

    // Insert the data into the database
    $sql = "INSERT INTO Pets (name, age, price, breed, size, color, energyLevel, friendliness, easeOfTraining, status, imageURL, vendorInfo, category, nearbyArea) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sissssssssssss", $name, $age, $price, $breed, $size, $color, $energyLevel, $friendliness, $easeOfTraining, $status, $imageURL, $vendorInfo, $category, $nearbyArea);

        if ($stmt->execute()) {
            $message = "New pet added successfully.";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Error: " . $conn->error;
    }

    $conn->close();
}
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

<div class="container mt-5 mb-5">
    <h2>Add a New Pet</h2>
    <?php
    if (isset($message)) {
        echo '<div class="alert alert-info">' . $message . '</div>';
    }
    ?>
    <form action="" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" class="form-control" id="age" name="age">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="breed">Breed:</label>
                <input type="text" class="form-control" id="breed" name="breed">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="size">Size:</label>
                <input type="text" class="form-control" id="size" name="size">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" class="form-control" id="color" name="color">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="energyLevel">Energy Level:</label>
                <input type="text" class="form-control" id="energyLevel" name="energyLevel">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="friendliness">Friendliness:</label>
                <input type="text" class="form-control" id="friendliness" name="friendliness">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="easeOfTraining">Ease of Training:</label>
                <input type="text" class="form-control" id="easeOfTraining" name="easeOfTraining">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Available for Adoption">Available for Adoption</option>
                    <option value="Not Available for Adoption">Not Available for Adoption</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="vendorInfo">Vendor Info:</label>
                <input type="text" class="form-control" id="vendorInfo" name="vendorInfo">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" class="form-control" id="category" name="category">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="nearbyArea">Nearby Area:</label>
                <input type="text" class="form-control" id="nearbyArea" name="nearbyArea">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Add Pet</button>
            <a class="btn btn-outline-dark " href="view_pets.php">View Pets</a>

        </div>
    </div>
</form>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
