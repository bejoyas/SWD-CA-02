<?php



if (!isset($_SESSION['login_user'])) {
    header("Location: index.php"); 
    exit();
}
// ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            color: black;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #444;
            color: white;
            padding: 10px 20px;
            text-align: right;
        }
        .header a {
            color: white;
            padding: 0 10px;
            text-decoration: none;
        }
        .header a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="header">
<?php 


function myErrorHandler($errno, $errstr, $errfile, $errline) {
    echo "<b>Custom error:</b> [$errno] $errstr<br>";
    echo " Error on line $errline in $errfile<br>";
}

set_error_handler("myErrorHandler");

    if ($_SESSION['user_role_id'] === 3){
        echo"<a href= 'add_user.php'> <button>Click here to Add users</button></a>";
    }
    ?>
    <a href ="dashboard.php"> <?php echo "<b>".htmlentities($_SESSION['login_user'])."</b>" ?> </a>
    <a href="change_password.php">Change Password</a>
    <a href="logout.php">Logout</a>
    
</div>
