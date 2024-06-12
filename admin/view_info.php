<?php
include('config.php');

session_start();

// Check if user is logged in as admin, if not, redirect to login page
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Delete website information if delete button is clicked
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM Website_Info WHERE infoID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Query to fetch all website information
$sql = "SELECT * FROM Website_Info";
$result = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Website Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
include('admin_navbar.php');
?>
<div class="container mt-5 mb-5">
    <h2>View Website Information</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Section</th>
                <th>Title</th>
                <th>Content</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["section"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["content"]) . "</td>";
                    echo "<td>";
                    echo "<a href='edit_info.php?id=" . $row['infoID'] . "' class='m-2 btn btn-primary btn-sm'>Edit</a>";
                    echo "<a href='?delete_id=" . $row['infoID'] . "' class='btn btn-danger btn-sm'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No website information found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="add_info.php" class="btn btn-primary ">Add New Information</a>
</div>

</body>
</html>
