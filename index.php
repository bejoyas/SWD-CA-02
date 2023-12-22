<?php
session_start();
if (isset($_SESSION['login_user'])){
        header("location: dashboard.php");
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Employee Attendance System</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('bg1.jpg') no-repeat center center fixed;
            background-size: cover;
            ;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            z-index: 999;
            position: relative;
            ;
        }

        input[type="text"], input[type="password"] {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
            border-radius: 4px;
        }

        button {
            background-color: #444;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 85%;
            border-radius: 4px;
        }

        button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div class="login-container">
    <!-- Your login form here -->
    <?php
    if(isset($_SESSION['error'])) {
        echo "<p style='color:red'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']); 
    }
    ?>
    <form action="login.php" method="post" enctype="multipart/form-data">
        <label for="uname"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="username" required><br>

        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required><br>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
