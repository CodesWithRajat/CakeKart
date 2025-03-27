<?php
session_start();
include 'dbconnect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get item details from the form
$item_id = $_POST['item_id'];
$item_type = $_POST['item_type'];
$item_name = $_POST['item_name'];
$item_price = $_POST['item_price'];
$item_image = $_POST['item_image'];

// Check if the item already exists in the cart for this user
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND item_id = ? AND item_type = ?");
$stmt->bind_param("iis", $user_id, $item_id, $item_type);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Item exists, update the quantity
    $row = $result->fetch_assoc();
    $new_quantity = $row['quantity'] + 1;

    $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND item_id = ? AND item_type = ?");
    $update_stmt->bind_param("iiis", $new_quantity, $user_id, $item_id, $item_type);
    $update_stmt->execute();
    $update_stmt->close();
} else {
    // Item doesn't exist, insert a new record
    $quantity = 1;
    $insert_stmt = $conn->prepare("INSERT INTO cart (user_id, item_id, item_type, item_name, item_price, item_image, quantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insert_stmt->bind_param("iissssi", $user_id, $item_id, $item_type, $item_name, $item_price, $item_image, $quantity);
    $insert_stmt->execute();
    $insert_stmt->close();
}

$stmt->close();
$conn->close();

// Redirect back to the previous page (or menu.php as a fallback)
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'menu.php';
header("Location: $referer");
exit;
?>