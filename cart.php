<?php
session_start();
include 'dbconnect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle removing an item from the cart
if (isset($_GET['remove'])) {
    $cart_id = $_GET['remove'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: cart.php");
    exit;
}

// Handle updating quantity
if (isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $new_quantity = (int)$_POST['quantity'];
    
    if ($new_quantity > 0) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $new_quantity, $cart_id, $user_id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: cart.php");
    exit;
}

// Fetch cart items for the user
$sql = "SELECT * FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}
$stmt->close();

// Calculate total price and cart count
$total_price = 0;
$cart_count = count($cart_items); // Update cart count based on items
foreach ($cart_items as $item) {
    $total_price += $item['item_price'] * $item['quantity'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bakery Shop - Cart</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
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
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        .cart-table th, .cart-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .cart-table th {
            background: #f5f5f5;
        }

        .cart-table img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }

        .cart-table input[type="number"] {
            width: 60px;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-primary {
            background: #e6637f;
            border: none;
            padding: 0.5rem 1rem;
            color: white;
            border-radius: 5px;
        }

        .btn-danger {
            background: #d62828;
            border: none;
            padding: 0.5rem 1rem;
            color: white;
            border-radius: 5px;
        }

        .total {
            text-align: right;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .checkout-btn {
            display: block;
            width: 200px;
            margin: 0 auto;
            padding: 1rem;
            background: #e6637f;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 30px;
        }

        .empty-cart {
            text-align: center;
            padding: 4rem;
            font-size: 1.2rem;
        }

        .empty-cart a {
            color: #e6637f;
            text-decoration: none;
        }

        /* Fix navigation hover color */
        .nav.navbar-nav li a {
            color: #2d1a0f;
        }
        .nav.navbar-nav li a:hover {
            color: #e6637f;
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
                </ul><!-- Fixed extra > -->
            </div>
        </div>
    </nav>

    <!-- Cart Section -->
    <div class="container">
        <h2>Your Cart</h2>
        <?php if (empty($cart_items)): ?>
            <div class="empty-cart">Your cart is empty. <a href="menu.php">Start shopping now!</a></div>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Item</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($item['item_image'] ?: 'https://via.placeholder.com/100') ?>" alt="<?= htmlspecialchars($item['item_name']) ?>"></td>
                            <td><?= htmlspecialchars($item['item_name']) ?></td>
                            <td><?= ucfirst($item['item_type'] ?? 'Unknown') ?></td>
                            <td>₹<?= number_format($item['item_price'], 2) ?></td>
                            <td>
                                <form action="cart.php" method="post">
                                    <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="0">
                                    <button type="submit" name="update_quantity" class="btn btn-primary">Update</button>
                                </form>
                            </td>
                            <td>₹<?= number_format($item['item_price'] * $item['quantity'], 2) ?></td>
                            <td>
                                <a href="cart.php?remove=<?= $item['id'] ?>" class="btn btn-danger">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="total">
                Total: ₹<?= number_format($total_price, 2) ?>
            </div>
            <a href="https://www.instagram.com/_.cakeybakeybhubaneswar._?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="checkout-btn">Proceed to Checkout</a>
        <?php endif; ?>
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