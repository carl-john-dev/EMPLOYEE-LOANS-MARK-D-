<?php 
    include 'connection.php'; 

    if (isset($_GET['id'])){
        $id = $_GET['id'];
        $deleteStd = "DELETE FROM tbl_employee WHERE emp_id = '$id'";
        $deleteStdquery = mysqli_query($conn, $deleteStd);
        header("location: index.php");
    }
?>
<?php 
    
?>