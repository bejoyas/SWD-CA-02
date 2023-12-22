<?php
session_start();
include 'db_connect.php';
include 'header.php';
include 'input_validation.php';
do{
if (!isset($_SESSION['login_user']) || (($_SESSION['user_role_id']==2))){
    header("location: index.php");}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['del-request'])) {
    
    
    
    $sql = $conn->prepare("DELETE FROM leave_details WHERE username=? and leave_date=? and leave_reason=?");
    $sql->bind_param("sss",$username, $leave_date, $leave_reason);
    $username = $_SESSION['login_user'];
    $leave_date = $_POST["leave_date"];
    $leave_reason =$_POST['leave_reason'];
    
    $result= $sql->execute();
    if ($result) {
        $_SESSION['success'] = "Leave Request deleted successfully" ;       
        break;
    }
    else{

        $_SESSION['error'] = "Something went wrong" ; 
        break;

    }

    break;}

elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['raise-request'])){

    $sql = $conn->prepare("INSERT INTO leave_details (username, leave_date, leave_reason) VALUES (?, ?, ?)");
    $sql->bind_param("sss",$username, $leave_date, $leave_reason);
    $username = $_SESSION['login_user'];
    $leave_date = $_POST["ldate"];
    if (empty($_POST['lreason'])){
        $_SESSION['error'] = "Reason cannot be blank";
        break;
    }
    $leave_reason =$_POST['lreason'];
    if (!input_validation($leave_reason)){
        $_SESSION['error'] = "Special characters are not allowed";
        
       break;
    }
    
    $result= $sql->execute();
    if ($result) {
        $_SESSION['success'] = "Leave Request Raised successfully" ;       
        break;
    }
    else{

        $_SESSION['error'] = "Something went wrong" ; 
        break;

    }

    break;
}

 } while(0)

?>

<html>
<head>
    <title>Leave Request</title>
    <style>
        body, html {
            /* height: 100%;
            margin: 0; */
            font-family: Arial, sans-serif;
            /* display: flex;
            justify-content: center;
            align-items: center; */
            background: url('bg1.jpg') no-repeat center center fixed;
            /* background-size: cover; */
        } 
        .leave-request-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            z-index: 999;
            position: relative;
            filter: blur(0);
        }
        .leave_form {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            z-index: 999;
            position: relative;
            filter: blur(0);
        }
        
        button {
            background-color: black;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 25%;
            border-radius: 4px;
        }

        button:hover {
            opacity: 0.8;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #444;
            color: white;
        }
        </style>
</head>
<body>
<div class="leave-request-container">
    <form class = "leave_form" action="leave_request.php" method="post">
        <h2>Leave Request Form</h2>
        Date : <input type="date" name="ldate"><br><br>
        Reason for leave: <input type="textarea" name="lreason" rows="5" cols="40"><br><br>
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

        <input type="submit" name = "raise-request" value="Raise Leave Request">
    </form>
    <table>
        <thead>
            <tr>
                
                <th>Date</th>
                <th>Leave Reason</th>
                <th>Decision</th>
                <th>Approved By </th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $user=$_SESSION['login_user']; 
            $query = "SELECT leave_date, leave_reason, decision, approved_by FROM leave_details WHERE (username = ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
             
                while($row = $result->fetch_assoc()) {
                    $leave_date = $row["leave_date"];
                    $leave_reason = $row["leave_reason"];
                    $decision = "Pending";

                    
                    $approver = 'N/A';

                   
                    echo "<tr>";
                    
                    
                    echo "<td>" . htmlentities($leave_date) . "</td>";
                    echo "<td>" . htmlentities($leave_reason) . "</td>";
                    if ($row["decision"] !== null) {
                        $decision = $row["decision"];
                        $approver = $row['approved_by'];
                        echo "<td>" .htmlentities($decision). "</td>";
                        echo "<td>" . htmlentities($approver). "</td>";
                    }
                    else{
                        echo "<td>" . htmlentities($decision) . "</td>";
                        echo "<td>" . htmlentities($approver). "</td>";
                        echo "<td>
                    <form action='leave_request.php' method='post'>
                    <input type='hidden' name='leave_reason' value =".htmlentities($leave_reason).">
                    <input type='hidden' name='leave_date' value =".htmlentities($leave_date).">

                    <button type='submit' name='del-request' value='delete' > Delete Request </button>
                    </form>
                    </td>";
                    }
                    
                    
                    
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No records found</td></tr>";
            }

            $stmt->close();
            $conn->close();
            ?>
            
        </tbody>
    </table>
        </div>
</body>
</html>