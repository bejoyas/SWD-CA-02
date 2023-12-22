<?php
session_start();
include 'db_connect.php';
include 'input_validation.php';

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST['username'];
    if (!input_validation($username)){ ///input validation of username
        $_SESSION['error'] = "Special characters are not allowed";
        header("location: index.php");
        exit();
    }
    $password = $_POST['password'];
    $salt="#2123##%^&";
    $password = md5($password.$salt); ///Password stored as hash with salting
    $plain =$_POST['password'];

    $sql = $conn ->prepare ("SELECT id, username, role_id, password FROM users WHERE (username=? && password=?)");
                            ///Prepared statements for SQL queries        
    $sql->bind_param('ss',$username,$password);
    $sql->execute();
    $sql->bind_result($user_id,$username,$role_id, $pass);
    $result= $sql->fetch ();
    $sql->close();

    if ($result) {
        
        $_SESSION['login_user'] = $username;
        $_SESSION['user_role_id'] = $role_id;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['login_password']=$pass;
       
        
        header("location: dashboard.php");
    } else {
        $_SESSION['error'] = "Your Login Name or Password is invalid";
        header("location: index.php");
        exit();
    }}

elseif (isset($_SESSION['login_user']) && isset ( $_SESSION['user_role_id'])){
        header("location: dashboard.php");
        exit();}

else {
        header("location: index.php");
        exit();}


?>