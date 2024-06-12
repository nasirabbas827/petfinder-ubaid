<?php
include('config.php');

session_start();


// Initialize search parameters
$searchParams = [
    'price' => '',
    'color' => '',
    'name' => '',
    'breed' => '',
    'category' => '',
    'nearbyArea' => ''
];

// Build the query with search conditions
$query = "SELECT * FROM Pets WHERE 1=1";

foreach ($searchParams as $key => $value) {
    if (!empty($_GET[$key])) {
        $query .= " AND $key LIKE '%" . $conn->real_escape_string($_GET[$key]) . "%'";
        $searchParams[$key] = $_GET[$key];
    }
}

$result = $conn->query($query);

?>
<!DOCTYPE html>
<html>
<head>
    <title>PET FINDER FOUNDATION (PFF)</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .jumbotron {
            height: 500px;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('./images/hotel.jpg');
            background-size: cover;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .jumbotron h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .jumbotron p {
            font-size: 1.5rem;
        }

        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php
include('navbar.php');
?>

<div class="jumbotron text-center">
    <h1>Welcome to PET FINDER FOUNDATION (PFF)</h1>
    <p>Search your Pets For Adoption</p>
    <a href="login.php" class="btn btn-primary btn-lg">Login</a>
</div>
<div class="container mt-5">
    <h2>Search Pets</h2>
    <form class="form-inline mb-3" method="GET" action="index.php">
        <input type="text" name="name" class="form-control mr-sm-2 m-2" placeholder="Name" value="<?php echo $searchParams['name']; ?>">
        <input type="text" name="breed" class="form-control mr-sm-2 m-2" placeholder="Breed" value="<?php echo $searchParams['breed']; ?>">
        <input type="text" name="color" class="form-control mr-sm-2 m-2" placeholder="Color" value="<?php echo $searchParams['color']; ?>">
        <input type="text" name="category" class="form-control mr-sm-2 m-2" placeholder="Category" value="<?php echo $searchParams['category']; ?>">
        <input type="text" name="nearbyArea" class="form-control mr-sm-2 m-2" placeholder="Nearby Area" value="<?php echo $searchParams['nearbyArea']; ?>">
        <input type="number" name="price" class="form-control mr-sm-2 m-2" placeholder="Max Price" value="<?php echo $searchParams['price']; ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="admin/<?php echo $row['imageURL']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['name']; ?></h5>
                            <p class="card-text">Breed: <?php echo $row['breed']; ?></p>
                            <p class="card-text">Color: <?php echo $row['color']; ?></p>
                            <p class="card-text">Category: <?php echo $row['category']; ?></p>
                            <p class="card-text">Nearby Area: <?php echo $row['nearbyArea']; ?></p>
                            <p class="card-text">Price: $<?php echo $row['price']; ?></p>
                            <?php if ($row['status'] == 'Available for Adoption'): ?>
                                <a href="booking.php?petID=<?php echo $row['petID']; ?>" class="btn btn-primary">Book Now</a>
                            <?php else: ?>
                                <p class="text-danger">Not Available for Adoption</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    No pets found matching your search criteria.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="container mt-5">
    <?php

    // Fetch website information from the database
    $info_sql = "SELECT * FROM Website_Info";
    $info_result = $conn->query($info_sql);

    // Initialize an array to hold the sections and their corresponding content
    $sections = array();

    // Loop through each row of the result
    while ($row = $info_result->fetch_assoc()) {
        $section = $row['section'];
        $title = $row['title'];
        $content = $row['content'];

        // Add the title and content to the array under the corresponding section
        $sections[$section][] = array('title' => $title, 'content' => $content);
    }
    $conn->close();

    // Loop through each section and display its content
    foreach ($sections as $section => $contentArray) {
        ?>
        <div class="mb-5">
            <h2><?php echo $section; ?></h2>
            <div class="row">
                <?php
                // Loop through the content array for the current section
                foreach ($contentArray as $contentItem) {
                    ?>
                    <!-- Display each content item as a card -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $contentItem['title']; ?></h5>
                                <p class="card-text"><?php echo $contentItem['content']; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<footer class="mt-5 py-3 bg-light">
    <div class="container text-center">
        <p>&copy; 2024 PET FINDER FOUNDATION (PFF). All rights reserved.</p>
    </div>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
