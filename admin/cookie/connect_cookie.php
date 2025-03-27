<?php
$name = $_POST['name'];
$image = $_POST['image'];
$stock_status = $_POST['stock_status'];
$price = $_POST['price'];

$conn = new mysqli('localhost','root','','bakeryshop');
if($conn->connect_error){
    die('Connection Failed :' .$conn->connect_error);
}else{
    $stmt = $conn->prepare("insert into cookies(name, image, stock_status,price) values(?,?,?,?)"); #placeholder
    $stmt->bind_param("ssss",$name,$image,$stock_status,$price);
    $stmt->execute();
    echo "Added Successfull.....";
    $stmt->close();

    $conn->close();
    header("location: ../display_cookie.php");
}