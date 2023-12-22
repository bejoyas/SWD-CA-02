<?php
session_start();
include 'db_connect.php';



if ($_SERVER["REQUEST_METHOD"] == "POST"):

    if (isset($_POST['clockIn'])){
        
        $user = $_SESSION['login_user'];
        $date = date("Y-m-d");

        $sql = $conn ->prepare ("SELECT check_in_time FROM attendance WHERE (username=? && date = ?)");
   
        $sql->bind_param('ss',$user,$date);
        $sql->execute();
        $sql->bind_result($checkin);
        $result= $sql->fetch ();
        $sql->close();
        if($checkin){
            $_SESSION['error'] = "You already clocked in !!" ;       
            header("location: dashboard.php");
        }
    
        else{
        $clock_in = date("h:i");
        $sql = $conn->prepare("INSERT INTO attendance (username, check_in_time, date) VALUES (?, ?, ?)");
        $sql->bind_param("sss",$user, $clock_in, $date);
        $result= $sql->execute();

        if ($result) {
            $_SESSION['success'] = "CLOCKED IN SUCCESSFULLY !!!" ;       
            header("location: dashboard.php");
    }}

    }

    if (isset($_POST['clockOut'])){
        
        $user = $_SESSION['login_user'];
        $date = date("Y-m-d");
        $sql = $conn ->prepare ("SELECT check_out_time, check_in_time FROM attendance WHERE (username=? && date = ?)");
   
        $sql->bind_param('ss',$user,$date);
        $sql->execute();
        $sql->bind_result($checkout,$check_in);
        $result= $sql->fetch ();
        $sql->close();
        if($checkout){
            $_SESSION['error'] = "You already clocked out !!" ;       
            header("location: dashboard.php");
        }
        
    
        elseif (!$check_in){
            $_SESSION['error'] = "You haven't clocked in !!" ;       
            header("location: dashboard.php");
        }

        else{
            $clock_out = date("h:i");
            $sql = $conn->prepare("UPDATE attendance SET check_out_time=? WHERE username=? and date =?");
            $sql->bind_param("sss",$clock_out, $user, $date);
            $result= $sql->execute();

            if ($result) {
                $_SESSION['success'] = "BYE BYE..YOU'VE CLOCKED OUT" ;       
                header("location: dashboard.php");
            }
            
        }

        } 

    
        endif;
    
?>