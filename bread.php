<?php
session_start();
include 'dbconnect.php';

// Get user_id from session if logged in, otherwise set cart count to 0
$cart_count = 0;
$selected_items = []; // Array to store selected quantities for each item
$items = []; // Array to store item details from the bread table

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Get cart count for navigation bar
    $sql = "SELECT COUNT(*) as count FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_count = $result->fetch_assoc()['count'];
    $stmt->close();

    // Get selected quantities for each item in the cart
    $sql = "SELECT item_name, quantity FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $selected_items[$row['item_name']] = $row['quantity'];
    }
    $stmt->close();

    // Handle increment and decrement actions
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $item_name = $_POST['item_name'];
        $item_price = $_POST['price'];
        $action = $_POST['action'];

        // Check if the item already exists in the cart
        $sql = "SELECT id, quantity FROM cart WHERE user_id = ? AND item_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $item_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($action == 'increment') {
            if ($result->num_rows > 0) {
                // Item exists, increment the quantity
                $row = $result->fetch_assoc();
                $new_quantity = $row['quantity'] + 1;
                $sql = "UPDATE cart SET quantity = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $new_quantity, $row['id']);
                $stmt->execute();
            } else {
                // Item does not exist, insert new entry with quantity 1
                $quantity = 1;
                $sql = "INSERT INTO cart (user_id, item_name, item_price, quantity) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isdi", $user_id, $item_name, $item_price, $quantity);
                $stmt->execute();
            }
        } elseif ($action == 'decrement') {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $new_quantity = $row['quantity'] - 1;
                if ($new_quantity <= 0) {
                    // Remove the item from the cart if quantity reaches 0
                    $sql = "DELETE FROM cart WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $row['id']);
                    $stmt->execute();
                } else {
                    // Decrement the quantity
                    $sql = "UPDATE cart SET quantity = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $new_quantity, $row['id']);
                    $stmt->execute();
                }
            }
        }
        $stmt->close();

        // Redirect back to the same page to reflect the updated quantity
        header("Location: bread.php");
        exit;
    }
}

// Fetch all items from the bread table
$sql = "SELECT name, price, stock_status, image FROM bread"; // Using bread table
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[$row['name']] = $row; // Store items in an array with name as the key
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Breads - Bakery Shop</title>
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

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
        }

        .col-sm-4 {
            flex: 1 1 300px;
            max-width: 300px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 1rem;
            text-align: center;
        }

        .col-sm-4 img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .col-sm-4 p {
            font-size: 1.1rem;
            margin: 0.5rem 0;
        }

        .col-sm-4 .price {
            font-size: 1.2rem;
            color: #e6637f;
            margin-bottom: 1rem;
        }

        .col-sm-4 .quantity {
            font-size: 1rem;
            color: #555;
            margin-bottom: 1rem;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .quantity-selector button {
            background: #e6637f;
            color: white;
            border: none;
            border-radius: 5px;
            width: 30px;
            height: 30px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .quantity-selector button:hover {
            background: #d5536f;
        }

        .quantity-selector span {
            font-size: 1rem;
            color: #2d1a0f;
            min-width: 30px;
            text-align: center;
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
                    <li><a href="about.php">About</a></li>
                    <li class="active"><a href="menu.php">Menu</a></li>
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

    <!-- Breads Section -->
    <div class="container">
        <h2>Artisan Breads</h2>
        <div class="row">
            <?php foreach ($items as $item): ?>
                <div class="col-sm-4">
                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    <p><?= htmlspecialchars($item['name']) ?></p>
                    <p class="price">₹<?= number_format($item['price'], 2) ?></p>
                    <p class="quantity"><?= $item['stock_status'] ?></p>
                    <div class="quantity-selector">
                        <form method="post" action="bread.php">
                            <input type="hidden" name="item_name" value="<?= htmlspecialchars($item['name']) ?>">
                            <input type="hidden" name="price" value="<?= $item['price'] ?>">
                            <input type="hidden" name="action" value="decrement">
                            <button type="submit">-</button>
                        </form>
                        <span><?= isset($selected_items[$item['name']]) ? $selected_items[$item['name']] : 0 ?></span>
                        <form method="post" action="bread.php">
                            <input type="hidden" name="item_name" value="<?= htmlspecialchars($item['name']) ?>">
                            <input type="hidden" name="price" value="<?= $item['price'] ?>">
                            <input type="hidden" name="action" value="increment">
                            <button type="submit">+</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
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