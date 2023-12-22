<?php
include 'db_connect.php';

session_start();
include 'header.php';
if ((!isset($_SESSION['login_user'])) || ($_SESSION['user_role_id']!=3) ) {
    header("location: index.php");
}

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
<table>
        <thead>
            <tr>
                <th>User</th>
                <th>Date</th>
                <th>Clock in Time</th>
                <th>Clock out Time</th>
                <th>Hours </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $user=$_SESSION['login_user']; 
            $query = "SELECT username,date, check_in_time, check_out_time FROM attendance";
            $stmt = $conn->prepare($query);
            // $stmt->bind_param("s", $user);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    $user_name = $row["username"];
                    $date = $row["date"];
                    $clock_in = new DateTime($row["check_in_time"]);
                    $clock_out = "Yet to clock out";
                        // $interval = $clock_in->diff($clock_out);
                        // $hours = $interval->format('%h hours %i minutes');

                    
                    $hours = 'Still clocked in';

                   
                    echo "<tr>";
                    echo "<td>" . htmlentities($user_name) . "</td>";
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