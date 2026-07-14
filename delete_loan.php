<?php
include 'connection.php';

if(isset($_GET['id'])) {
    $loan_id = $_GET['id'];
    
    // Get emp_id before deleting
    $sql = "SELECT emp_id FROM tbl_employee_loans WHERE loan_id = '$loan_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $emp_id = $row['emp_id'];

    // Delete loan
    $sql = "DELETE FROM tbl_employee_loans WHERE loan_id = '$loan_id'";
    
    if(mysqli_query($conn, $sql)) {
        // Check if employee has other loans
        $check_sql = "SELECT COUNT(*) as count FROM tbl_employee_loans WHERE emp_id = '$emp_id'";
        $check_result = mysqli_query($conn, $check_sql);
        $check_row = mysqli_fetch_assoc($check_result);
        
        // If no other loans, delete employee too (optional)
        // if($check_row['count'] == 0) {
        //     mysqli_query($conn, "DELETE FROM tbl_employee WHERE emp_id = '$emp_id'");
        // }
        
        echo "<script>
                alert('✅ Loan record deleted successfully!');
                window.location.href='index.php';
              </script>";
    } else {
        echo "<script>
                alert('❌ Error: " . mysqli_error($conn) . "');
                window.location.href='index.php';
              </script>";
    }
} else {
    header('Location: index.php');
    exit();
}
?>