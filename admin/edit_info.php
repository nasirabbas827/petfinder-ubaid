<?php
include('config.php');

session_start();

// Check if user is logged in as admin, if not, redirect to login page
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Initialize variables
$infoID = $section = $title = $content = "";

// Check if infoID parameter is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_info.php");
    exit;
}

// Fetch infoID from the URL
$infoID = intval($_GET['id']);

// Fetch website information based on infoID
$sql = "SELECT * FROM Website_Info WHERE infoID = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $infoID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if (!$row) {
        header("Location: view_info.php");
        exit;
    }
    // Assign values to variables
    $section = $row['section'];
    $title = $row['title'];
    $content = $row['content'];
    $stmt->close();
}

// Update website information if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $section = $_POST['section'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Update database
    $sql = "UPDATE Website_Info SET section = ?, title = ?, content = ? WHERE infoID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssi", $section, $title, $content, $infoID);
        $stmt->execute();
        $stmt->close();
        header("Location: view_info.php");
        exit;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Website Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Edit Website Information</h2>
    <form method="post">
        <div class="form-group">
            <label for="section">Section</label>
            <select class="form-control" name="section">
                <option value="" disabled>Select a section</option>
                <option value="About" <?php if ($section == 'About') echo 'selected'; ?>>About</option>
                <option value="Adopting Pets" <?php if ($section == 'Adopting Pets') echo 'selected'; ?>>Adopting Pets</option>
                <option value="Animal Shelters & Rescues" <?php if ($section == 'Animal Shelters & Rescues') echo 'selected'; ?>>Animal Shelters & Rescues</option>
                <option value="Pet-Finder Foundation" <?php if ($section == 'Pet-Finder Foundation') echo 'selected'; ?>>Pet-Finder Foundation</option>
            </select>
        </div>
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea class="form-control" id="content" name="content" rows="5"><?php echo htmlspecialchars($content); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="view_info.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
