<?php 
include 'connection.php';

if (isset($_POST['submit'])) {
    
    // GET FORM DATA
    $emp_name = isset($_POST['emp_name']) ? trim($_POST['emp_name']) : '';
    $SSS_ID = isset($_POST['SSS_ID']) ? trim($_POST['SSS_ID']) : '';  // <-- CAPSLOCK
    $pagibig_id = isset($_POST['pagibig_id']) ? trim($_POST['pagibig_id']) : '';
    
    // ===== VALIDATION =====
    $errors = array();
    
    // 1. Validate Employee Name
    if(empty($emp_name)) {
        $errors[] = "Employee Name is required!";
    }
    
    // 2. Validate SSS ID - CAPSLOCK
    $SSS_ID_clean = preg_replace('/\D/', '', $SSS_ID);
    
    if(empty($SSS_ID_clean)) {
        $errors[] = "SSS ID is required! Please enter a valid 10-digit SSS ID.";
    } elseif(strlen($SSS_ID_clean) != 10) {
        $errors[] = "SSS ID must be exactly 10 digits. Current: " . strlen($SSS_ID_clean) . " digits";
    } elseif($SSS_ID_clean === '0000000000') {
        $errors[] = "SSS ID cannot be all zeros! Please enter a valid SSS ID.";
    } else {
        $SSS_ID = $SSS_ID_clean;
    }
    
    // 3. Validate Pag-Ibig ID
    $pagibig_id_clean = preg_replace('/\D/', '', $pagibig_id);
    
    if(!empty($pagibig_id_clean)) {
        if(strlen($pagibig_id_clean) != 12) {
            $errors[] = "Pag-Ibig ID must be exactly 12 digits. Current: " . strlen($pagibig_id_clean) . " digits";
        } elseif($pagibig_id_clean === '000000000000') {
            $errors[] = "Pag-Ibig ID cannot be all zeros! Please enter a valid Pag-Ibig ID.";
        } else {
            $pagibig_id = $pagibig_id_clean;
        }
    } else {
        $pagibig_id = NULL;
    }
    
    // 4. Check if SSS ID already exists - CAPSLOCK
    if(empty($errors) && !empty($SSS_ID)) {
        $check_sql = "SELECT SSS_ID FROM tbl_employee WHERE SSS_ID = '$SSS_ID'";
        $check_query = mysqli_query($conn, $check_sql);
        if(mysqli_num_rows($check_query) > 0) {
            $errors[] = "SSS ID already exists! Please use a unique SSS ID.";
        }
    }
    
    // 5. If there are errors, show them
    if(!empty($errors)) {
        $error_message = implode("\\n", $errors);
        echo "<script>
                alert('" . $error_message . "');
                window.location.href='index.php';
              </script>";
        exit();
    }
    
    // ===== INSERT TO DATABASE - CAPSLOCK =====
    $emp_name = mysqli_real_escape_string($conn, $emp_name);
    $SSS_ID = mysqli_real_escape_string($conn, $SSS_ID);
    
    if($pagibig_id === NULL || empty($pagibig_id)) {
        $string_insert = "INSERT INTO tbl_employee (emp_name, SSS_ID, pagibig_id) 
                          VALUES ('$emp_name', '$SSS_ID', NULL)";
    } else {
        $pagibig_id = mysqli_real_escape_string($conn, $pagibig_id);
        $string_insert = "INSERT INTO tbl_employee (emp_name, SSS_ID, pagibig_id) 
                          VALUES ('$emp_name', '$SSS_ID', '$pagibig_id')";
    }

    $insert_query = mysqli_query($conn, $string_insert);

    if($insert_query) {
        echo "<script>
                alert('Employee record added successfully!');
                window.location.href='index.php';
              </script>";
    } else {
        echo "<script>
                alert('Error adding record: " . mysqli_error($conn) . "');
                window.location.href='index.php';
              </script>";
    }
    
} else {
    header("Location: index.php");
    exit();
}
?>