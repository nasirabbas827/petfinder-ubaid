<?php
include('config.php');

session_start();

// Check if user is logged in as admin, if not, redirect to login page
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Initialize variables
$title = $content = $section = "";
$title_err = $content_err = $section_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate section
    if (empty(trim($_POST["section"]))) {
        $section_err = "Please select a section.";
    } else {
        $section = trim($_POST["section"]);
    }

    // Validate title
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter a title.";
    } else {
        $title = trim($_POST["title"]);
    }

    // Validate content
    if (empty(trim($_POST["content"]))) {
        $content_err = "Please enter content.";
    } else {
        $content = trim($_POST["content"]);
    }

    // Check input errors before inserting into database
    if (empty($section_err) && empty($title_err) && empty($content_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO Website_Info (section, title, content) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_section, $param_title, $param_content);

            // Set parameters
            $param_section = $section;
            $param_title = $title;
            $param_content = $content;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to view all website info page
                header("location: view_info.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Website Info</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
include('admin_navbar.php');
?>
<div class="container mt-5">
        <h2>Add Website Information</h2>
        <p>Please fill this form to add website information.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($section_err)) ? 'has-error' : ''; ?>">
                <label>Section</label>
                <select class="form-control" name="section">
                    <option value="" disabled selected>Select a section</option>
                    <option value="About">About</option>
                    <option value="Adopting Pets">Adopting Pets</option>
                    <option value="Animal Shelters & Rescues">Animal Shelters & Rescues</option>
                    <option value="Pet-Finder Foundation">Pet-Finder Foundation</option>
                </select>
                <span class="help-block"><?php echo $section_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
                <span class="help-block"><?php echo $title_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($content_err)) ? 'has-error' : ''; ?>">
                <label>Content</label>
                <textarea name="content" class="form-control"><?php echo $content; ?></textarea>
                <span class="help-block"><?php echo $content_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a href="view_info.php" class="btn btn-default">View</a>
            </div>
        </form>
    </div>
</body>
</html>
