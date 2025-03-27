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

// Fetch Cakes
$sql_cakes = "SELECT * FROM items";
$result_cakes = $conn->query($sql_cakes);

// Fetch Cookies
$sql_cookies = "SELECT * FROM cookies";
$result_cookies = $conn->query($sql_cookies);

// Fetch Bread
$sql_bread = "SELECT * FROM bread";
$result_bread = $conn->query($sql_bread);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bakery Shop - Menu</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <style>
        :root {
            --primary: #e6637f;
            --secondary: #fbdcad;
            --dark: #2d1a0f;
            --light: #fff5f0;
        }

        body {
            font-family: 'Arial Rounded MT Bold', system-ui, -apple-system, sans-serif;
            background: var(--light);
            color: var(--dark);
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
            color: var(--dark);
            margin: 2rem 0;
            position: relative;
            display: inline-block;
        }

        h2:after {
            content: '';
            display: block;
            width: 60%;
            height: 3px;
            background: var(--primary);
            margin: 1rem auto;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            padding: 2rem 0;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image {
            height: 280px;
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .product-info {
            padding: 1.5rem;
            text-align: left;
        }

        .product-info h3 {
            margin: 0 0 0.5rem;
            font-size: 1.4rem;
            color: var(--dark);
        }

        .price-tag {
            font-size: 1.3rem;
            color: var(--primary);
            font-weight: bold;
            margin: 0.5rem 0;
        }

        .stock-status {
            font-size: 0.9rem;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            display: inline-block;
            background: #f0f0f0;
        }

        .stock-status.in-stock {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .cta-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--dark);
            color: white !important;
            padding: 0.8rem 1.5rem;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .cta-button:hover {
            background: var(--primary);
            transform: scale(1.05);
        }

        .no-products {
            text-align: center;
            padding: 4rem;
            font-size: 1.2rem;
            color: #666;
        }

        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
            
禁止: 1fr;
            h2 {
                font-size: 2rem;
            }
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

    <!-- Menu Section -->
    <div class="container">
        <!-- Cakes Section -->
        <h2 id="cakes">Artisan Cakes & Pastries</h2>
        <div class="product-grid">
            <?php if ($result_cakes->num_rows > 0): ?>
                <?php while ($row = $result_cakes->fetch_assoc()): ?>
                    <?php 
                    $image = !empty($row['image']) ? $row['image'] : "https://images.unsplash.com/photo-1557925923-cd4648e211a0?auto=format&fit=crop&w=600";
                    $statusClass = strtolower($row['stock_status']) === 'in stock' ? 'in-stock' : '';
                    ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($image, ENT_QUOTES) ?>" 
                                 alt="<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>"
                                 loading="lazy"
                                 onerror="this.src='https://images.unsplash.com/photo-1557925923-cd4648e211a0?auto=format&fit=crop&w=600'">
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($row['name'], ENT_QUOTES) ?></h3>
                            <div class="price-tag">₹<?= number_format($row['price'], 2) ?></div>
                            <div class="stock-status <?= $statusClass ?>"><?= $row['stock_status'] ?></div>
                            <form action="add_to_cart.php" method="post">
                                <input type="hidden" name="item_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="item_type" value="cake">
                                <input type="hidden" name="item_name" value="<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>">
                                <input type="hidden" name="item_price" value="<?= $row['price'] ?>">
                                <input type="hidden" name="item_image" value="<?= htmlspecialchars($image, ENT_QUOTES) ?>">
                                <button type="submit" class="cta-button">
                                    <i class="fas fa-shopping-cart"></i> Buy Now
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-products">Currently baking fresh cakes! Check back soon 🧁</div>
            <?php endif; ?>
        </div>

        <!-- Cookies Section -->
        <h2 id="cookies">Artisan Cookies</h2>
        <div class="product-grid">
            <?php if ($result_cookies->num_rows > 0): ?>
                <?php while ($row = $result_cookies->fetch_assoc()): ?>
                    <?php 
                    $image = !empty($row['image']) ? $row['image'] : "https://images.unsplash.com/photo-1557925923-cd4648e211a0?auto=format&fit=crop&w=600";
                    $statusClass = strtolower($row['stock_status']) === 'in stock' ? 'in-stock' : '';
                    ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($image, ENT_QUOTES) ?>" 
                                 alt="<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>"
                                 loading="lazy"
                                 onerror="this.src='https://images.unsplash.com/photo-1557925923-cd4648e211a0?auto=format&fit=crop&w=600'">
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($row['name'], ENT_QUOTES) ?></h3>
                            <div class="price-tag">₹<?= number_format($row['price'], 2) ?></div>
                            <div class="stock-status <?= $statusClass ?>"><?= $row['stock_status'] ?></div>
                            <form action="add_to_cart.php" method="post">
                                <input type="hidden" name="item_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="item_type" value="cookie">
                                <input type="hidden" name="item_name" value="<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>">
                                <input type="hidden" name="item_price" value="<?= $row['price'] ?>">
                                <input type="hidden" name="item_image" value="<?= htmlspecialchars($image, ENT_QUOTES) ?>">
                                <button type="submit" class="cta-button">
                                    <i class="fas fa-shopping-cart"></i> Buy Now
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-products">Currently baking fresh cookies! Check back soon 🧁</div>
            <?php endif; ?>
        </div>

        <!-- Bread Section -->
        <h2 id="bread">Artisan Breads</h2>
        <div class="product-grid">
            <?php if ($result_bread->num_rows > 0): ?>
                <?php while ($row = $result_bread->fetch_assoc()): ?>
                    <?php 
                    $image = !empty($row['image']) ? $row['image'] : "https://images.unsplash.com/photo-1557925923-cd4648e211a0?auto=format&fit=crop&w=600";
                    $statusClass = strtolower($row['stock_status']) === 'in stock' ? 'in-stock' : '';
                    ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($image, ENT_QUOTES) ?>" 
                                 alt="<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>"
                                 loading="lazy"
                                 onerror="this.src='https://images.unsplash.com/photo-1557925923-cd4648e211a0?auto=format&fit=crop&w=600'">
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($row['name'], ENT_QUOTES) ?></h3>
                            <div class="price-tag">₹<?= number_format($row['price'], 2) ?></div>
                            <div class="stock-status <?= $statusClass ?>"><?= $row['stock_status'] ?></div>
                            <form action="add_to_cart.php" method="post">
                                <input type="hidden" name="item_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="item_type" value="bread">
                                <input type="hidden" name="item_name" value="<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>">
                                <input type="hidden" name="item_price" value="<?= $row['price'] ?>">
                                <input type="hidden" name="item_image" value="<?= htmlspecialchars($image, ENT_QUOTES) ?>">
                                <button type="submit" class="cta-button">
                                    <i class="fas fa-shopping-cart"></i> Buy Now
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-products">Currently baking fresh bread! Check back soon 🧁</div>
            <?php endif; ?>
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

    <?php $conn->close(); ?>
</body>
</html>