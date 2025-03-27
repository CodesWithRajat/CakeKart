<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cakes Details - Admin Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fdf3e7;
            background-image: url('https://img.freepik.com/premium-vector/seamless-pattern-hand-drawn-baking-elements_621139-11.jpg?semt=ais_hybrid'); /* Add a bakery-themed background image */
            background-size: cover;
            color: #6a0572;
            margin: 50px;
        }

        h1 {
            font-size: 28px;
            font-weight: 600;
            color:rgb(0, 0, 0);
            margin-bottom: 20px;
            text-align: center;
        }

        .btn-primary {
            background-color: #ffb703;
            border-color: #ffb703;
            color: #fff;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #d62828;
            border-color: #d62828;
        }

        .table {
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .table th {
            background-color: #ffb703;
            color: #fff;
            font-weight: 500;
            padding: 12px;
            text-align: center;
            border: none;
        }

        .table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
            font-size: 16px;
            color: #6a0572;
        }

        .table img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .table img:hover {
            transform: scale(1.1);
        }

        .btn-sm {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            transition: background-color 0.3s ease;
            margin: 2px;
        }

        .btn-danger {
            background-color: #d62828;
            border-color: #d62828;
            color: #fff;
            font-weight: 500;
        }

        .btn-danger:hover {
            background-color: #a01414;
            border-color: #a01414;
        }

        .btn-wrapper {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
    </style>
</head>
<body style="margin: 50px;">
    <h1> List of Cakes </h1>
    <a class='btn btn-primary btn-sm' href="./cake/add.html" role="button">Add Cakes </a>
    <br>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Image</th>
            <th>Stock</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        </thead>  
        
        <tbody>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "bakeryshop";

            $connection = new mysqli($servername,$username,$password,$database);

            if($connection->connect_error) {
                die("Connection failed : " . $connection->connect_error);
            }

            $sql = "SELECT * FROM items";
            $result = $connection->query($sql);

            if(!$result){
                die("Invalid query: " . $connection->error);
            }
            while($row = $result->fetch_assoc()) {
                echo"<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["name"] . "</td>
                    <td> <img src='" . $row["image"] . "' alt='" . $row["name"] . "'> </td>
                    <td>" . $row["stock_status"] . "</td>
                    <td>" . $row["price"] . "</td>
                    <td> 
                        <a class='btn btn-primary btn-sm' href='./cake/update_cake.php?id=$row[id]'>Update </a>
                        <a class='btn btn-danger btn-sm' href='./cake/delete_cake.php?id=$row[id]'>Delete </a>
                    </td>
                </tr>";

            }
            ?>
        </tbody>

    </table>
</body>
</html>