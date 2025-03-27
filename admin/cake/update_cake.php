<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "bakeryshop";

$connection = new mysqli($servername, $username, $password, $database);

$id = "";
$name = "";
$image = "";
$stock_status = "";
$price = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["id"])) {
        header("location: ../display_cake.php");
        exit;
    }

    $id = $_GET["id"];
    $sql = "SELECT * FROM items WHERE id=$id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: ../display_cake.php");
        exit;
    }

    $name = $row["name"];
    $image = $row["image"];
    $stock_status = $row["stock_status"];
    $price = $row["price"];
} else {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $image = $_POST["image"];
    $stock_status = $_POST["stock_status"];
    $price = $_POST["price"];

    do {
        $sql = "UPDATE items SET name = '$name', image= '$image', stock_status = '$stock_status', price ='$price' WHERE id =$id";

        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "❌ Invalid query: " . $connection->error;
            break;
        }

        $successMessage = "✅ Cake updated successfully!";
        header("refresh:2;url=../display_cake.php");
        exit;
    } while (true);
}
?>