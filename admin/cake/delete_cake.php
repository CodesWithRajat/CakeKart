<?php
if (isset($_GET["id"])) {  
    $id = $_GET["id"];

  
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "bakeryshop";

    $connection = new mysqli($servername, $username, $password, $database);

    
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    
    $stmt = $connection->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $id); 


    if ($stmt->execute()) {
        echo "Record deleted successfully.";
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}

header("Location: ../display_cake.php");
exit;
?>