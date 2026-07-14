<?php
include 'connection.php';

if(isset($_POST['loan_id'])) {
    $loan_id = $_POST['loan_id'];
    
    $sql = "UPDATE tbl_employee_loans SET deleted_at = NOW() WHERE loan_id = '$loan_id'";
    
    if(mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>