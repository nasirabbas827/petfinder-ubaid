<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch the pet details to be edited
if (isset($_GET['petID'])) {
    $petID = intval($_GET['petID']);
    $sql = "SELECT * FROM Pets WHERE petID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $petID);
        $stmt->execute();
        $result = $stmt->get_result();
        $pet = $result->fetch_assoc();
        $stmt->close();
    }
}

// Handle form submission for editing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $petID = intval($_POST['petID']);
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
    $imageURL = $pet['imageURL']; // Default to existing image URL
    if (!empty($_FILES["image"]["name"])) {
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
            }
        }
    }

    $sql = "UPDATE Pets SET name = ?, age = ?, price = ?, breed = ?, size = ?, color = ?, energyLevel = ?, friendliness = ?, easeOfTraining = ?, status = ?, imageURL = ?, vendorInfo = ?, category = ?, nearbyArea = ? WHERE petID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sissssssssssssi", $name, $age, $price, $breed, $size, $color, $energyLevel, $friendliness, $easeOfTraining, $status, $imageURL, $vendorInfo, $category, $nearbyArea, $petID);
        if ($stmt->execute()) {
            $message = "Pet details updated successfully.";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch the updated pet details
if (isset($petID)) {
    $sql = "SELECT * FROM Pets WHERE petID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $petID);
        $stmt->execute();
        $result = $stmt->get_result();
        $pet = $result->fetch_assoc();
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pet</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5 mb-5">
    <h2>Edit Pet</h2>
    <?php
    if (isset($message)) {
        echo '<div class="alert alert-info">' . $message . '</div>';
    }
    ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="petID" value="<?php echo $pet['petID']; ?>">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $pet['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" class="form-control" id="age" name="age" value="<?php echo $pet['age']; ?>">
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" class="form-control" id="price" name="price" value="<?php echo $pet['price']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="breed">Breed:</label>
                    <input type="text" class="form-control" id="breed" name="breed" value="<?php echo $pet['breed']; ?>">
                </div>
                <div class="form-group">
                    <label for="size">Size:</label>
                    <input type="text" class="form-control" id="size" name="size" value="<?php echo $pet['size']; ?>">
                </div>
                <div class="form-group">
                    <label for="color">Color:</label>
                    <input type="text" class="form-control" id="color" name="color" value="<?php echo $pet['color']; ?>">
                </div>
                <div class="form-group">
                    <label for="energyLevel">Energy Level:</label>
                    <input type="text" class="form-control" id="energyLevel" name="energyLevel" value="<?php echo $pet['energyLevel']; ?>">
                </div>
                <div class="form-group">
                    <label for="friendliness">Friendliness:</label>
                    <input type="text" class="form-control" id="friendliness" name="friendliness" value="<?php echo $pet['friendliness']; ?>">
                </div>
                <div class="form-group">
                    <label for="easeOfTraining">Ease of Training:</label>
                    <input type="text" class="form-control" id="easeOfTraining" name="easeOfTraining" value="<?php echo $pet['easeOfTraining']; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Available for Adoption" <?php if ($pet['status'] == "Available for Adoption") echo "selected"; ?>>Available for Adoption</option>
                        <option value="Not Available for Adoption" <?php if ($pet['status'] == "Not Available for Adoption") echo "selected"; ?>>Not Available for Adoption</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="image">Image:</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <?php if (!empty($pet['imageURL'])): ?>
                        <img src="<?php echo $pet['imageURL']; ?>" alt="<?php echo $pet['name']; ?>" width="100" class="mt-2">
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="vendorInfo">Vendor Info:</label>
                    <input type="text" class="form-control" id="vendorInfo" name="vendorInfo" value="<?php echo $pet['vendorInfo']; ?>">
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <input type="text" class="form-control" id="category" name="category" value="<?php echo $pet['category']; ?>">
                </div>
                <div class="form-group">
                    <label for="nearbyArea">Nearby Area:</label>
                    <input type="text" class="form-control" id="nearbyArea" name="nearbyArea" value="<?php echo $pet['nearbyArea']; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Update Pet</button>
            </div>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
