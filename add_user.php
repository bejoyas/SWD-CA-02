<?php
include 'db_connect.php';
include 'password_policy.php';
include 'input_validation.php'; 
session_start();

do{
if (!isset($_SESSION['login_user']) || $_SESSION['user_role_id']!=3) {
    header("location: index.php");
    exit();
}
include 'header.php';
$roles_to_role_id = array(
    "employee" => 1,
    "team_lead" => 2,
    "hr_admin" =>3);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if(empty($_POST['username'])||empty($_POST["password"])){
        $_SESSION['error']="Username or Password field is empty";
        break;
    }
    $username = $_POST['username'];

    if (!input_validation($username)){
        $_SESSION['error'] = "Special characters are not allowed";
        
        break;
    }
    $sql = $conn ->prepare ("SELECT username FROM users WHERE username=?");
   
    $sql->bind_param('s',$username);
    $sql->execute();
    $sql->bind_result($user_check);
    $result= $sql->fetch ();
    $sql->close();

    if (isset($user_check)||!empty($user_check)){
        $_SESSION['error'] ="Username already exists";
        break;
    }
    $passwd = $_POST['password'];
    if (!check_password_complexity($passwd)){
        $_SESSION['error'] = "You've entered a weak password.";
        break;
    }
    $salt="#2123##%^&";
    $passwd =md5($passwd.$salt);
    $role_id = $roles_to_role_id[$_POST['roles']];
    

    $sql = $conn->prepare("INSERT INTO users (username, password, role_id) VALUES (?, ?, ?)");
    $sql->bind_param("ssi",$username, $passwd, $role_id);
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
    <form action="add_user.php" method="post">
    <label for="employee-details">Fill in the employee details below</label><br><br>
        Employee Username: <input type="text" name="username"><br>
        Employee Password: <input type="password" name="password"><br>
        <label for="roles">Choose a role:</label>
        <select name="roles" id="roles">
        <option value="employee">Employee</option> 
        <option value="team_lead">Team Lead</option> 
        <option value="hr_admin">HR Admin</option> 
        </select>

        <input type="submit" value="Update Details  "><br><br>
        <p><i><b>Password Policy:</i></b></p>
        <p><i>Min 8 characters</i></p>
        <p><i>Atleast one Uppercase and lowercase character</i></p>
        <p><i>Atleast one number</i></p>
        <p><i>Atleast one special character</i></p>
    </form>
</body>
</html>

