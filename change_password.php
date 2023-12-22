<?php
include 'db_connect.php';
include 'password_policy.php';
session_start();

do{
if (!isset($_SESSION['login_user']) ) {
    header("location: index.php");
    exit();
}
include 'header.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if(empty($_POST['current_password'])||empty($_POST["new_password"])){
        $_SESSION['error']="Password field is empty";
        break;
    }
    $current_password = $_POST['current_password'];
    $user_id = $_SESSION['user_id'];
    $salt="#2123##%^&";
    // $sql = $conn ->prepare ("SELECT password FROM users WHERE id=?");
   
    // $sql->bind_param('i',$user_id);
    // $sql->execute();
    // $sql->bind_result($pass_check);
    // $result= $sql->fetch ();
    // $sql->close();

    if (md5($current_password.$salt)!=$_SESSION['login_password']){
        $_SESSION['error'] ="Wrong password".$_SESSION['password']."-old".$current_password."-new";
        break;
    }
    

    $new_password = $_POST['new_password'];
    if (md5($new_password.$salt)==$_SESSION['login_password']){
        $_SESSION['error'] ="New password cannot be same as old password";
        break;
    }
    if (!check_password_complexity($new_password)){
        $_SESSION['error'] = "You've entered a weak password.";
        break;
    }
    $salt="#2123##%^&";
    $passwd =md5($new_password.$salt);
    
    

    $sql = $conn->prepare("UPDATE users SET password=? WHERE id=? ");
    $sql->bind_param("si",$passwd, $user_id);
    $result= $sql->execute();

    if ($result) {
        $_SESSION['success'] = "Successfully updated details" ;       
        break;
    }
    else{
        $_SESSION['error'] = "Something went wrong";
        break;
    }
break;}
}while (0)
?>




<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php
    
    if (isset ($_SESSION['success'])){
        echo "<p style='color:green'>" . $_SESSION['success'] . "</p>";
            unset($_SESSION['success']); 
    
    }
    if (isset ($_SESSION['error'])){
        echo "<p style='color:red'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);  
    
    }
    ?>  
    <form action="change_password.php" method="post">
    <label for="employee-details"><b>Reset Password</b></label><br><br>
        Current Password: <input type="password" name="current_password"><br>
        New Password: <input type="password" name="new_password"><br>
        
        </select>

        <input type="submit" value="Update Password  "><br><br>
        <p><i><b>Password Policy:</i></b></p>
        <p><i>Min 8 characters</i></p>
        <p><i>Atleast one Uppercase and lowercase character</i></p>
        <p><i>Atleast one number</i></p>
        <p><i>Atleast one special character</i></p>
    </form>
</body>
</html>