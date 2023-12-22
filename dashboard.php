<?php

include 'db_connect.php';
session_start();

if (!isset($_SESSION['login_user'])) {
    header("location: index.html");
}
include 'header.php';
$role_id = $_SESSION['user_role_id'];
?>


<html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance - Employee Attendance System</title>
    <style>
        body, html {
            /* height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center; */
            background: url('bg1.jpg') no-repeat center center fixed;
            background-size: cover;
        } 

        .attendance-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            z-index: 999;
            position: relative;
            filter: blur(0);
        }
        .admin_functions{
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0   );
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

<div class="attendance-container">
    <h2>Employee Attendance</h2>
    <?php 
    if (isset ($_SESSION['success'])){
        echo "<p style='color:green'>" . $_SESSION['success'] . "</p>";
            unset($_SESSION['success']); 
    
    } 
    if (isset ($_SESSION['error'])){
        echo "<p style='color:red'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']); 
    
    } ?>
    <form action="attendance.php" method="post">
        <button type="submit" name="clockIn">Clock In</button>
        <button type="submit" name="clockOut">Clock Out</button>

    </form><br>
    <?php
    if ($_SESSION['user_role_id'] === 1 ):

        echo "<a href = 'leave_request.php'> <button>Raise Leave Request</button></a>";
    elseif ($_SESSION['user_role_id'] == 2):
        echo "<a href = 'approve_leave_request.php'><button>Approve Leave Requests</button></a>";
    elseif ($_SESSION['user_role_id'] == 3 ):
            echo "<a href = 'view_all_attendance.php'><button>View all attendance data</button></a>";
            echo "<a href = 'leave_request.php'> <button>Raise Leave Request</button></a>";
    endif;
    ?>

  
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Clock in Time</th>
                <th>Clock out Time</th>
                <th>Hours </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $user=$_SESSION['login_user']; 
            $query = "SELECT date, check_in_time, check_out_time FROM attendance WHERE (username = ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
             
                while($row = $result->fetch_assoc()) {
                    $date = $row["date"];
                    $clock_in = new DateTime($row["check_in_time"]);
                    $clock_out = "Yet to clock out";
                        // $interval = $clock_in->diff($clock_out);
                        // $hours = $interval->format('%h hours %i minutes');

                    
                    $hours = 'Still clocked in';

                   
                    echo "<tr>";
                    echo "<td>" . $date . "</td>";
                    echo "<td>" . $clock_in->format('h:i:s') . "</td>";
                    if ($row["check_out_time"] !== null) {
                        $clock_out = new DateTime($row["check_out_time"]);
                        $interval = $clock_in->diff($clock_out);
                        $hours = $interval->format('%h hours %i minutes');
                        echo "<td>" . $clock_out->format('h:i:s') . "</td>";
                    }
                    else{
                        echo "<td>" . $clock_out . "</td>";
                    }
                    
                    echo "<td>" . $hours . "</td>";
                    
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