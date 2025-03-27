<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Bites | Login Status</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            background: #fff8e7; /* Creamy background */
            background-image: url('https://img.freepik.com/premium-vector/seamless-pattern-hand-drawn-baking-elements_621139-11.jpg?semt=ais_hybrid'); /* Add a bakery-themed background image */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .container:hover {
            transform: translateY(-5px);
        }

        h2 {
            color: #d2691e; /* Chocolate brown */
            font-size: 24px;
            margin-bottom: 14px;
            font-weight: 600;
        }

        p {
            color: #5a5a5a;
            font-size: 16px;
            margin-bottom: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }

        li {
            margin-bottom: 12px;
        }

        a {
            display: inline-block;
            color: #d2691e;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            padding: 10px 20px;
            border: 2px solid #d2691e;
            border-radius: 8px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        a:hover {
            background-color: #d2691e;
            color: #ffffff;
        }

        .error {
            color: #ff4d4d;
            font-weight: 600;
            font-size: 18px;
            margin-top: 10px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #d2691e;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
            display: none;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        

        @media (max-width: 400px) {
            .container {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div id="loading" class="spinner"></div>
        <div id="content" style="display: none;">
        <?php
        $user_name = $_POST["username"];
        $user_password = $_POST["password"];

        if ($user_name == "Admin" && $user_password == "cookie") {
            echo "<h2>🍪 Welcome, $user_name! 🍪</h2>";
            echo "<p>Here are your available links:</p>";
            echo "<ul>";
            echo "<li><a href='./display_cake.php'>🍩 View and edit cake Menu.</a></li>";
            echo "<li><a href='./display_cookie.php'>🍩 View and edit Cookies Menu.</a></li>";
            echo "<li><a href='./display_bread.php'>🍩 View and edit Bread Menu.</a></li>";
            echo "</ul>";
        } else {
            echo "<h2 class='error'>⚠️ Either the username or the password is incorrect.</h2>";
        }
        ?>
        </div>
    </div>
    <script>
    // Show spinner first
    document.getElementById('loading').style.display = 'block';

    // Simulate loading effect with a delay
    setTimeout(() => {
        document.getElementById('loading').style.display = 'none'; // Hide spinner
        document.getElementById('content').style.display = 'block'; // Show content
    }, 1500); // 1.5-second delay
</script>
</body>
</html>

