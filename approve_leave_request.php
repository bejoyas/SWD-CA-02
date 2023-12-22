<?php
include 'db_connect.php';

session_start();
include 'header.php';

if (!isset($_SESSION['login_user']) || ($_SESSION['user_role_id']!=2) ) {
    header("location: index.php");
}

do{
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_POST['decision'])){
        $_SESSION['error'] = "Choose your decision";
        break;
    }
    $sql = $conn->prepare("UPDATE leave_details SET decision=?, approved_by=? WHERE id=?");
    $sql->bind_param("ssi",$decision, $approver, $id);

    $decision = $_POST['decision'];
    $approver = $_SESSION['login_user'];
    $id = $_POST['id'];
    $result= $sql->execute();

    if ($result) {
        $_SESSION['success'] = "Successfully updated details" ;
        break;       
        
    }
    else{
        $_SESSION['error']= "Error updating details" ;       
        break;
        
    }


}
} while(0);
?>

<html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance - Employee Attendance System</title>
    <style>
        body, html {
         
            /* background: url('bg1.jpg') no-repeat center center fixed; */
            background-size: cover;
            
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
<?php
 if (isset ($_SESSION['success'])){
    echo "<p style='color:green'>" . $_SESSION['success'] . "</p>";
        unset($_SESSION['success']); }
    if (isset ($_SESSION['error'])){
            echo "<p style='color:red'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']); } ?>
<table>
        <thead>
            <tr>
                <th>User</th>
                <th>Leave Date</th>
                <th>Leave Reason</th>
                <th>Approve</th>
            </tr>
        </thead>
        <tbody>
            <?php
           
            $user=$_SESSION['login_user']; 
            $query = "SELECT id, username,leave_date, leave_reason FROM leave_details WHERE decision IS NULL";
            $stmt = $conn->prepare($query);
            // $stmt->bind_param("s", $user);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    $user_name = $row["username"];
                    $leave_date = $row["leave_date"];
                    $leave_reason = $row["leave_reason"];
                    $id =$row["id"];
                                        
                    

                   
                    echo "<tr>";
                    echo "<td>" . $user_name . "</td>";
                    echo "<td>" . $leave_date . "</td>";
                    echo "<td>" . $leave_reason . "</td>";                    
                    echo '<td>
                    <form action="approve_leave_request.php" method="post">
                    <input type="radio"  id ="yes" name="decision" value="Approved">
                    <label for="yes">YES</label><br>
                    <input type="radio" id ="no" name="decision" value="Denied">
                    <label for="no">NO</label><br>
                    <input type="hidden"  name="id" value='.$id.'>
                    <input type="submit" value = "Submit">
                  </form>
                    </td>';
                    
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