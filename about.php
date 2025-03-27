<?php
session_start();
include 'dbconnect.php';

// Get user_id from session if logged in, otherwise set cart count to 0
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT COUNT(*) as count FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_count = $result->fetch_assoc()['count'];
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Bakery Shop</title>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial Rounded MT Bold', system-ui, -apple-system, sans-serif;
            background: #fff5f0;
            color: #2d1a0f;
            margin: 0;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            font-size: 2.5rem;
            color: #2d1a0f;
            margin: 2rem 0;
            position: relative;
            display: inline-block;
        }

        h2:after {
            content: '';
            display: block;
            width: 60%;
            height: 3px;
            background: #e6637f;
            margin: 1rem auto;
        }

        p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }

        .about-section {
            padding: 2rem 0;
            text-align: center;
        }

        .about-image {
            width: 100%;
            max-width: 600px;
            margin: 2rem auto;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>               
                </button>
                <a class="navbar-brand" href="index.php">
                    <img src="https://res.cloudinary.com/dbqqjaqqa/image/upload/v1489836162/smaller_size_logo_wigzr1.png">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <li class="active"><a href="about.php">About</a></li>
                    <li><a href="menu.php">Menu</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="signup.php">Sign Up</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="admin/login.html">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> Cart (<?= $cart_count ?>)</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- About Section -->
    <div class="container">
        <div class="about-section">
            <h2>About Us</h2>
            <p>
                Welcome to our Bakery Shop, where every bite tells a story of passion, tradition, and love for baking. 
                Established with a dream to bring the finest baked goods to our community, we’ve been crafting delicious 
                cakes, cookies, and breads that warm the heart and soul.
            </p>
            <p>
                Our journey began with a small kitchen and a big vision—to create treats that not only taste amazing but 
                also bring people together. From our signature artisan cakes to our freshly baked breads, every item is made 
                with the highest quality ingredients and a sprinkle of creativity.
            </p>
            <img src="https://images.unsplash.com/photo-1557925923-cd4648e211a0?auto=format&fit=crop&w=600" alt="Bakery Image" class="about-image">
            <p>
                We believe in the magic of baking and the joy it brings to our customers. Whether you’re celebrating a special 
                occasion or simply craving a sweet treat, we’re here to make your moments even sweeter. Thank you for choosing 
                us to be a part of your delicious journey!
            </p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="container-fluid text-center">
        <div class="row">
            <div class="col-sm-4">
                <h1 id="footer1">Shop online</h1>
                <ul class="footer-links">
                    <li>Grab & Go In-store Pick Up</li>
                    <li>Order Baked Goods</li>
                </ul>
            </div>
            <div class="col-sm-4">
                <h1 id="footer2">Our menu</h1>
                <ul class="footer-links">
                    <li>Explore our menu</li>   
                </ul>
            </div>
            <div class="col-sm-4">
                <h1 id="footer3">About</h1>
                <ul class="footer-links">
                    <li>FAQs</li>
                    <li>Store policy</li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
    <script src='//cdnjs.cloudflare.com/ajax/libs/FitText.js/1.1/jquery.fittext.min.js'></script>
    <script src="script.js"></script>
</body>
</html>