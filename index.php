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
    <title>Bakery Shop Landing Page</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel="stylesheet" href="styles.css" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  </head>
  <body>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>               
      </button>
      <a class="navbar-brand" href="#">
       <img src="https://res.cloudinary.com/dbqqjaqqa/image/upload/v1489836162/smaller_size_logo_wigzr1.png"></img>
      </a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
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
</ul>
    </div>
  </div>
</nav>
<div class="container content">
  <div class="row">
    <div class="col-sm-4">
      <div class="panel">
        <a href="./cake.php"> 
          <img src="https://res.cloudinary.com/dbqqjaqqa/image/upload/v1490170457/apple-and-almond-cake_i12bux.jpg" class="img-thumbnail" alt="Image">
        <div class="box">
          <h1 id="content1-headline1">Cakes</h1>
        </div>
        </a>
      </div>
      </a>
    </div>
   
    <div class="col-sm-4">
      <div class="panel">
        <a href="./cookies.php">
          <img src="https://res.cloudinary.com/dbqqjaqqa/image/upload/v1490245951/gluten-free-bread-rolls-nut-free-bread-rolls-pale-pete-recipes-paleo-way-seed-rolls_lthh5a.jpg " class="img-thumbnail">
        <div class="box">
          <h1 id="content1-headline2">Cookies</h1>
        </div>
        </a>
      
      </div>
    </div>
    <div class="col-sm-4">
      <div class="panel">
        <a href="./bread.php"><img src="https://res.cloudinary.com/dbqqjaqqa/image/upload/v1490245830/Gluten_Free_Bread_6_-_Copy_rtbcqq.jpg" class="img-thumbnail" alt="Image">
        <div class="box">
          <h1 id="content1-headline3">Bread</h1>
        </div>
        </a>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row row-eq-height">
    <div class="col-sm-5 content2">
      <p id="content2-1">Here at The Home Bakery, baking is the passion. Come and visit - your taste buds will thank you!</p>
    </div>
    <div class="col-sm-2">
      <img src="https://res.cloudinary.com/dbqqjaqqa/image/upload/v1490342409/dsc_0114_hgqgai.jpg" class="img-circle">
    </div>
    <div class="col-sm-5 content2">
      <p id="content2-2">We use only the finest and freshest ingredients to create pastries that will warm your heart.</p>
    </div>
  </div>
</div>
      
<div id="carousel" class="carousel slide" data-ride="carousel">

    <ol class="carousel-indicators">
      <li data-target="#carousel" data-slide-to="0" class="active"></li>
      <li data-target="#carousel" data-slide-to="1"></li>
    </ol>

    <div class="carousel-inner" role="listbox">
      <div class="item active">
        <img src="https://res.cloudinary.com/dbqqjaqqa/image/upload/v1490351456/carousel1.jpg">
        <div class="carousel-caption">
          <h3>100% Homemade</h3>
          <p>We make pastries at home using 100% natural ingredients</p>
        </div>      
      </div>
      <div class="item">
        <img src="https://res.cloudinary.com/dbqqjaqqa/image/upload/v1490276719/carousel2_zxje9g.jpg" alt="Image">
        <div class="carousel-caption">
          <h3>Make order</h3>
          <p>Call us and we will delivery fresh pastries to your home</p>
        </div>      
      </div>
      <div class="item">
        <img src="https://res.cloudinary.com/dbqqjaqqa/image/upload/v1490276801/carousel3_heh2hy.jpg" alt="Image">
        <div class="carousel-caption">
          <h3>100% Homemade</h3>
          <p>We make pastries at home using 100% natural ingredients</p>
        </div>      
      </div>
      <div class="item">
        <img src="https://res.cloudinary.com/dbqqjaqqa/image/upload/v1490276736/carousel4_iuwmrn.jpg" alt="Image">
        <div class="carousel-caption">
          <h3>Make order</h3>
          <p>Call us and we will delivery fresh pastries to your home</p>
        </div>      
      </div>
    </div>

    <a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
</div> 
<!-- -->

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
        <li>Store polisy</li>
      </ul>
    </div>
</footer>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/FitText.js/1.1/jquery.fittext.min.js'></script>
    <script src="script.js"></script>
  </body>
</html>