<?php 
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "employee_loans";
    

    $conn = mysqli_connect($server, $user, $pass, $db);

    if (!$conn) {
        die (mysqli_connect_error());  
    }

?>