<?php
include 'connection.php';

$fetchId = $fetchName = $fetchSSS = $fetchPagIbig = '';
$error = '';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $selectStd = "SELECT * FROM tbl_employee WHERE emp_id = '$id'";
    $selectStdquery = mysqli_query($conn, $selectStd);
    
    if(mysqli_num_rows($selectStdquery) > 0) {
        $result = mysqli_fetch_assoc($selectStdquery);
        $fetchId = $result['emp_id'];
        $fetchName = $result['emp_name'];
        $fetchSSS = $result['SSS_ID'];
        $fetchPagIbig = $result['pagibig_id'];
    } else {
        echo "<script>
                alert('Record not found!');
                window.location.href='index.php';
              </script>";
        exit();
    }
} else {
    echo "<script>
            alert('No ID provided!');
            window.location.href='index.php';
          </script>";
    exit();
}

if(isset($_POST['submit'])) {
    $id = $_POST['id'];
    $emp_name = isset($_POST['emp_name']) ? trim($_POST['emp_name']) : '';
    $SSS_ID = isset($_POST['SSS_ID']) ? trim($_POST['SSS_ID']) : '';
    $pagibig_id = isset($_POST['pagibig_id']) ? trim($_POST['pagibig_id']) : '';
    
    $errors = array();
    
    if(empty($emp_name)) {
        $errors[] = "Employee Name is required!";
    }
    
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
    
    if(empty($errors) && !empty($SSS_ID)) {
        $check_sql = "SELECT SSS_ID FROM tbl_employee WHERE SSS_ID = '$SSS_ID' AND emp_id != '$id'";
        $check_query = mysqli_query($conn, $check_sql);
        if(mysqli_num_rows($check_query) > 0) {
            $errors[] = "SSS ID already exists! Please use a unique SSS ID.";
        }
    }
    
    if(!empty($errors)) {
        $error_message = implode("\\n", $errors);
        echo "<script>
                alert('" . $error_message . "');
                window.location.href='editEmployee.php?id=$id';
              </script>";
        exit();
    }
    
    $emp_name = mysqli_real_escape_string($conn, $emp_name);
    $SSS_ID = mysqli_real_escape_string($conn, $SSS_ID);
    
    if($pagibig_id === NULL || empty($pagibig_id)) {
        $updateStd = "UPDATE tbl_employee SET 
                     emp_name = '$emp_name',
                     SSS_ID = '$SSS_ID',
                     pagibig_id = NULL
                     WHERE emp_id = '$id'";
    } else {
        $pagibig_id = mysqli_real_escape_string($conn, $pagibig_id);
        $updateStd = "UPDATE tbl_employee SET 
                     emp_name = '$emp_name',
                     SSS_ID = '$SSS_ID',
                     pagibig_id = '$pagibig_id'
                     WHERE emp_id = '$id'";
    }

    $updateStdquery = mysqli_query($conn, $updateStd);

    if($updateStdquery) {
        echo "<script>
                alert('Employee record updated successfully!');
                window.location.href='index.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Error updating record: " . mysqli_error($conn) . "');
                window.location.href='editEmployee.php?id=$id';
              </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="icon" href="MDUE LOGO.jpg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDIT EMPLOYEE DATA</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            padding: 20px;
            transition: background-color 0.3s, color 0.3s;
        }

        body.dark-mode {
            background-color: #1a1a2e;
            color: #e0e0e0;
        }

        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Logo */
        .logo-stretch img {
            height: 80px;
            width: 1150px;
            max-height: 90px;
            object-fit: fill;
            border-radius: 0.5px;
            transition: filter 0.3s;
        }

        body.dark-mode .logo-stretch img {
            filter: brightness(0.95) drop-shadow(0 0 4px rgba(255,255,240,0.15));
        }

        /* Card */
        .card-custom {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, box-shadow 0.3s;
            max-width: 600px;
            margin: 0 auto;
        }

        body.dark-mode .card-custom {
            background: #16213e;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
        }

        /* Form inputs */
        .form-control-custom {
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
            text-align: center;
        }

        body.dark-mode .form-control-custom {
            background-color: #1a1a3e;
            border-color: #2a2a4a;
            color: #e0e0e0;
        }

        body.dark-mode .form-control-custom:focus {
            background-color: #1a1a3e;
            color: #e0e0e0;
            border-color: #3498db;
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }

        body.dark-mode .form-control-custom::placeholder {
            color: #8888aa;
        }

        body.dark-mode .form-label {
            color: #e0e0e0;
        }

        /* Dark mode toggle button */
        .theme-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 50%;
            transition: all 0.3s;
            color: var(--text-color);
        }

        .theme-btn:hover {
            background: rgba(0, 0, 0, 0.1);
            transform: scale(1.1);
        }

        body.dark-mode .theme-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Char counter */
        .char-counter {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }

        .char-counter.limit-reached {
            color: #e74c3c;
            font-weight: 600;
        }

        body.dark-mode .char-counter {
            color: #8888aa;
        }

        /* Back button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            font-size: 13px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .back-btn:hover {
            background-color: #2980b9;
            color: white;
            transform: translateX(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .back-btn {
            background-color: #2980b9;
        }

        body.dark-mode .back-btn:hover {
            background-color: #3498db;
        }

        /* Cancel button */
        .btn-cancel {
            display: inline-block;
            padding: 12px 24px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-cancel:hover {
            background: #c0392b;
            color: white;
            transform: translateY(-2px);
        }

        body.dark-mode .btn-cancel {
            background: #c0392b;
        }

        body.dark-mode .btn-cancel:hover {
            background: #e74c3c;
        }

        /* Responsive logo */
        @media (max-width: 768px) {
            .logo-stretch img {
                height: 60px;
                max-height: 68px;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .logo-stretch img {
                height: 48px;
                max-height: 56px;
            }
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <!-- ===== HEADER WITH LOGO ABOVE TITLE ===== -->
        <div class="text-center mb-4">
            <!-- Logo -->
            <div class="logo-stretch mb-2">
                <img src="MDUE LOGO STRETCH.png" alt="MDUE Logo" class="img-fluid" />
            </div>
            
            <!-- Header Content: Back Button, Title, and Theme Toggle -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-3">
                    <a href="index.php" class="back-btn">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <h2 class="fw-semibold m-0">✏️ Edit Employee</h2>
                </div>
                <button class="theme-btn" id="themeToggle" title="Toggle Dark Mode">
                    <i class="bi bi-moon-fill" id="themeIcon"></i>
                </button>
            </div>
        </div>

        <!-- ===== FORM ===== -->
        <div class="card-custom p-4 p-md-5">
            <form method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($fetchId); ?>">
                
                <div class="mb-4">
                    <label for="SSS_ID" class="form-label fw-medium text-center d-block">SSS ID (10 digits):</label>
                    <input type="text" 
                           class="form-control form-control-custom"
                           id="SSS_ID" 
                           name="SSS_ID"   
                           value="<?php echo htmlspecialchars($fetchSSS); ?>" 
                           required
                           maxlength="10"
                           pattern="[0-9]{10}"
                           placeholder="Enter 10-digit SSS ID"
                           oninput="limitSSS(this)">
                    <span class="char-counter d-block text-center" id="sssCounter"><?php echo strlen($fetchSSS); ?> / 10 digits</span>
                </div>
                
                <div class="mb-4">
                    <label for="pagibig_id" class="form-label fw-medium text-center d-block">Pag-Ibig ID (12 digits):</label>
                    <input type="text" 
                           class="form-control form-control-custom"
                           id="pagibig_id" 
                           name="pagibig_id" 
                           value="<?php echo htmlspecialchars($fetchPagIbig); ?>"
                           maxlength="12"
                           pattern="[0-9]{12}"
                           placeholder="Enter 12-digit Pag-Ibig ID"
                           oninput="limitPagIbig(this)">
                    <span class="char-counter d-block text-center" id="pagibigCounter"><?php echo strlen($fetchPagIbig); ?> / 12 digits</span>
                </div>
                
                <div class="mb-4">
                    <label for="emp_name" class="form-label fw-medium text-center d-block">Employee Name:</label>
                    <input type="text" 
                           class="form-control form-control-custom"
                           id="emp_name" 
                           name="emp_name" 
                           value="<?php echo htmlspecialchars($fetchName); ?>" 
                           required
                           placeholder="Enter full name">
                </div>
                
                <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                    <button type="submit" name="submit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-pencil me-1"></i> Update
                    </button>
                    <button type="reset" class="btn btn-secondary px-4 py-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </button>
                    <a href="index.php" class="btn-cancel">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ===== THEME TOGGLE =====
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');

            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
                themeIcon.className = 'bi bi-sun-fill';
            }

            themeToggle.addEventListener('click', function() {
                document.body.classList.toggle('dark-mode');
                
                if (document.body.classList.contains('dark-mode')) {
                    localStorage.setItem('darkMode', 'enabled');
                    themeIcon.className = 'bi bi-sun-fill';
                } else {
                    localStorage.setItem('darkMode', 'disabled');
                    themeIcon.className = 'bi bi-moon-fill';
                }
            });
        });

        // ===== SSS COUNTER =====
        function limitSSS(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            input.value = value;
            
            const counter = document.getElementById('sssCounter');
            counter.textContent = value.length + ' / 10 digits';
            
            if (value.length === 10) {
                counter.classList.add('limit-reached');
            } else {
                counter.classList.remove('limit-reached');
            }
        }

        // ===== PAG-IBIG COUNTER =====
        function limitPagIbig(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 12) {
                value = value.slice(0, 12);
            }
            input.value = value;
            
            const counter = document.getElementById('pagibigCounter');
            counter.textContent = value.length + ' / 12 digits';
            
            if (value.length === 12) {
                counter.classList.add('limit-reached');
            } else {
                counter.classList.remove('limit-reached');
            }
        }

        // ===== INITIALIZE COUNTERS =====
        document.addEventListener('DOMContentLoaded', function() {
            const sssInput = document.getElementById('SSS_ID');
            const pagIbigInput = document.getElementById('pagibig_id');
            
            if (sssInput.value) {
                limitSSS(sssInput);
            }
            
            if (pagIbigInput.value) {
                limitPagIbig(pagIbigInput);
            }
        });
    </script>
</body>
</html>