<?php
include 'connection.php';

$fetchLoanId = $fetchEmpId = $fetchEmpName = $fetchDept = '';
$fetchSSS = $fetchPagIbig = '';
$fetchDateApplied = $fetchLoanType = $fetchLoanAmount = '';
$fetchTotalDeduction = $fetchDeductionStart = '';
$fetchPayment1yr = $fetchPayment2yr = $fetchPayment3yr = '';
$fetchStatus = '';
$error = '';

if(isset($_GET['id'])) {
    $loan_id = $_GET['id'];
    
    $sql = "SELECT l.*, e.emp_name, e.SSS_ID, e.pagibig_id 
            FROM tbl_employee_loans l
            LEFT JOIN tbl_employee e ON l.emp_id = e.emp_id
            WHERE l.loan_id = '$loan_id'";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $fetchLoanId = $row['loan_id'];
        $fetchEmpId = $row['emp_id'];
        $fetchEmpName = $row['emp_name'];
        $fetchDept = $row['dept'];
        $fetchSSS = $row['SSS_ID'];
        $fetchPagIbig = $row['pagibig_id'];
        $fetchDateApplied = $row['date_applied'];
        $fetchLoanType = $row['loan_type'];
        $fetchLoanAmount = $row['loan_amount'];
        $fetchTotalDeduction = $row['total_deduction'];
        $fetchDeductionStart = $row['deduction_start'];
        $fetchPayment1yr = $row['payment_1yr'];
        $fetchStatus = $row['status'];
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

if(isset($_POST['update'])) {
    $loan_id = $_POST['loan_id'];
    $emp_id = $_POST['emp_id'];
    $emp_name = mysqli_real_escape_string($conn, $_POST['emp_name']);
    $dept = mysqli_real_escape_string($conn, $_POST['dept']);
    $SSS_ID = !empty($_POST['SSS_ID']) ? $_POST['SSS_ID'] : NULL;
    $pagibig_id = !empty($_POST['pagibig_id']) ? $_POST['pagibig_id'] : NULL;
    $date_applied = $_POST['date_applied'];
    $loan_type = $_POST['loan_type'];
    $loan_amount = $_POST['loan_amount'];
    $total_deduction = $_POST['total_deduction'];
    $deduction_start = $_POST['deduction_start'];
    $payment_1yr = !empty($_POST['payment_1yr']) ? $_POST['payment_1yr'] : NULL;
    $status = $_POST['status'];
    
    $errors = array();
    
    if(empty($emp_name)) {
        $errors[] = "Employee Name is required!";
    }
    
    if(empty($dept)) {
        $errors[] = "Department is required!";
    }
    
    if(empty($date_applied)) {
        $errors[] = "Date of Application is required!";
    }
    
    if(empty($loan_type)) {
        $errors[] = "Loan Type is required!";
    }
    
    if(empty($loan_amount) || $loan_amount <= 0) {
        $errors[] = "Please enter a valid Loan Amount!";
    }
    
    if(empty($total_deduction) || $total_deduction <= 0) {
        $errors[] = "Please enter a valid Total Deduction!";
    }
    
    if(empty($deduction_start)) {
        $errors[] = "Starting Date of Deduction is required!";
    }
    
    if(!empty($errors)) {
        $error_message = implode("\\n", $errors);
        echo "<script>
                alert('" . $error_message . "');
                window.location.href='edit_loan.php?id=$loan_id';
              </script>";
        exit();
    }
    
    // Update employee
    $update_emp = "UPDATE tbl_employee SET 
                   emp_name = '$emp_name',
                   SSS_ID = '$SSS_ID',
                   pagibig_id = '$pagibig_id'
                   WHERE emp_id = '$emp_id'";
    mysqli_query($conn, $update_emp);

    // Update loan
    $sql = "UPDATE tbl_employee_loans SET 
            dept = '$dept',
            date_applied = '$date_applied',
            loan_type = '$loan_type',
            loan_amount = '$loan_amount',
            total_deduction = '$total_deduction',
            deduction_start = '$deduction_start',
            payment_1yr = '$payment_1yr',
            status = '$status'
            WHERE loan_id = '$loan_id'";

    if(mysqli_query($conn, $sql)) {
        // SUCCESS - Show loading animation then redirect
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Updating...</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    background: #f5f5f5;
                    font-family: 'Poppins', sans-serif;
                }
                body.dark-mode {
                    background: #1a1a2e;
                }
                .loader-wrapper {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 30px;
                }
                .loader {
                    scale: 0.75;
                    position: relative;
                    width: 200px;
                    height: 200px;
                    translate: 10px -20px;
                }
                .loader svg {
                    position: absolute;
                    top: 0;
                    left: 0;
                }
                .head {
                    translate: 27px -30px;
                    z-index: 3;
                    animation: bob 1s infinite ease-in;
                }
                .bod {
                    translate: 0px 30px;
                    z-index: 3;
                    animation: bob 1s infinite ease-in-out;
                }
                .legr {
                    translate: 75px 135px;
                    z-index: 0;
                    animation: rstep 1s infinite ease-in;
                }
                .legr {
                    animation-delay: 0.45s;
                }
                .legl {
                    translate: 30px 155px;
                    z-index: 3;
                    animation: lstep 1s infinite ease-in;
                }
                @keyframes bob {
                    0% { transform: translateY(0) rotate(3deg); }
                    5% { transform: translateY(0) rotate(3deg); }
                    25% { transform: translateY(5px) rotate(0deg); }
                    50% { transform: translateY(0px) rotate(-3deg); }
                    70% { transform: translateY(5px) rotate(0deg); }
                    100% { transform: translateY(0) rotate(3deg); }
                }
                @keyframes lstep {
                    0% { transform: translateY(0) rotate(-5deg); }
                    33% { transform: translateY(-15px) translate(32px) rotate(35deg); }
                    66% { transform: translateY(0) translate(25px) rotate(-25deg); }
                    100% { transform: translateY(0) rotate(-5deg); }
                }
                @keyframes rstep {
                    0% { transform: translateY(0) translate(0px) rotate(-5deg); }
                    33% { transform: translateY(-10px) translate(30px) rotate(35deg); }
                    66% { transform: translateY(0) translate(20px) rotate(-25deg); }
                    100% { transform: translateY(0) translate(0px) rotate(-5deg); }
                }
                #gnd {
                    translate: -140px 0;
                    rotate: 10deg;
                    z-index: -1;
                    filter: blur(0.5px) drop-shadow(1px 3px 5px #000000);
                    opacity: 0.25;
                    animation: scroll 5s infinite linear;
                }
                @keyframes scroll {
                    0% { transform: translateY(25px) translate(50px); opacity: 0; }
                    33% { opacity: 0.25; }
                    66% { opacity: 0.25; }
                    to { transform: translateY(-50px) translate(-100px); opacity: 0; }
                }
                .message-text {
                    font-size: 1.2rem;
                    font-weight: 500;
                    color: #2c3e50;
                    text-align: center;
                    animation: pulse 1.5s ease-in-out infinite;
                }
                body.dark-mode .message-text {
                    color: #e0e0e0;
                }
                @keyframes pulse {
                    0%, 100% { opacity: 1; }
                    50% { opacity: 0.5; }
                }
                .status-icon {
                    font-size: 3rem;
                    margin-bottom: 10px;
                }
            </style>
        </head>
        <body>
            <div class='loader-wrapper'>
                <div class='loader'>
                    <svg class='legl' version='1.1' xmlns='http://www.w3.org/2000/svg' width='20.69332' height='68.19944' viewBox='0,0,20.69332,68.19944'>
                        <g transform='translate(-201.44063,-235.75466)'>
                            <g stroke-miterlimit='10'>
                                <path d='' fill='#ffffff' stroke='none' stroke-width='0.5'></path>
                                <path d='' fill-opacity='0.26667' fill='#97affd' stroke-opacity='0.48627' stroke='#ffffff' stroke-width='0'></path>
                                <path d='M218.11971,301.20087c-2.20708,1.73229 -4.41416,0 -4.41416,0l-1.43017,-1.1437c-1.42954,-1.40829 -3.04351,-2.54728 -4.56954,-3.87927c-0.95183,-0.8308 -2.29837,-1.49883 -2.7652,-2.55433c-0.42378,-0.95815 0.14432,-2.02654 0.29355,-3.03399c0.41251,-2.78499 1.82164,-5.43386 2.41472,-8.22683c1.25895,-4.44509 2.73863,-8.98683 3.15318,-13.54796c0.22615,-2.4883 -0.21672,-5.0155 -0.00278,-7.50605c0.30636,-3.56649 1.24602,-7.10406 1.59992,-10.6738c0.29105,-2.93579 -0.00785,-5.9806 -0.00785,-8.93046c0,0 0,-2.44982 3.12129,-2.44982c3.12129,0 3.12129,2.44982 3.12129,2.44982c0,3.06839 0.28868,6.22201 -0.00786,9.27779c-0.34637,3.56935 -1.30115,7.10906 -1.59992,10.6738c-0.2103,2.50918 0.22586,5.05326 -0.00278,7.56284c-0.43159,4.7371 -1.94029,9.46317 -3.24651,14.07835c-0.47439,2.23403 -1.29927,4.31705 -2.05805,6.47156c-0.18628,0.52896 -0.1402,1.0974 -0.327,1.62624c-0.09463,0.26791 -0.64731,0.47816 -0.50641,0.73323c0.19122,0.34617 0.86423,0.3445 1.2346,0.58502c1.88637,1.22503 3.50777,2.79494 5.03,4.28305l0.96971,0.73991c0,0 2.20708,1.73229 0,3.46457z' fill='none' stroke='#191e2e' stroke-width='7'></path>
                            </g>
                        </g>
                    </svg>

                    <svg class='legr' version='1.1' xmlns='http://www.w3.org/2000/svg' width='41.02537' height='64.85502' viewBox='0,0,41.02537,64.85502'>
                        <g transform='translate(-241.54137,-218.44347)'>
                            <g stroke-miterlimit='10'>
                                <path d='M279.06674,279.42662c-2.27967,1.98991 -6.08116,0.58804 -6.08116,0.58804l-2.47264,-0.92915c-2.58799,-1.18826 -5.31176,-2.08831 -7.99917,-3.18902c-1.67622,-0.68654 -3.82471,-1.16116 -4.93147,-2.13229c-1.00468,-0.88156 -0.69132,-2.00318 -0.92827,-3.00935c-0.65501,-2.78142 0.12275,-5.56236 -0.287,-8.37565c-0.2181,-4.51941 -0.17458,-9.16283 -1.60696,-13.68334c-0.78143,-2.46614 -2.50162,-4.88125 -3.30086,-7.34796c-1.14452,-3.53236 -1.40387,-7.12078 -2.48433,-10.66266c-0.88858,-2.91287 -2.63779,-5.85389 -3.93351,-8.74177c0,0 -1.07608,-2.39835 3.22395,-2.81415c4.30003,-0.41581 2.41605,1.98254 2.41605,1.98254c1.34779,3.00392 3.13072,6.05282 4.06444,9.0839c1.09065,3.54049 1.33011,7.13302 2.48433,10.66266c0.81245,2.48448 2.5308,4.917 3.31813,7.40431c1.48619,4.69506 1.48366,9.52281 1.71137,14.21503c0.32776,2.25028 0.10631,4.39942 0.00736,6.60975c-0.02429,0.54266 0.28888,1.09302 0.26382,1.63563c-0.01269,0.27488 -0.68173,0.55435 -0.37558,0.78529c0.41549,0.31342 1.34191,0.22213 1.95781,0.40826c3.13684,0.94799 6.06014,2.26892 8.81088,3.52298l1.66093,0.59519c0,0 6.76155,1.40187 4.48187,3.39177z' fill='none' stroke='#000000' stroke-width='7'></path>
                                <path d='' fill='#ffffff' stroke='none' stroke-width='0.5'></path>
                                <path d='' fill-opacity='0.26667' fill='#97affd' stroke-opacity='0.48627' stroke='#ffffff' stroke-width='0'></path>
                            </g>
                        </g>
                    </svg>

                    <div class='bod'>
                        <svg version='1.1' xmlns='http://www.w3.org/2000/svg' width='144.10576' height='144.91623' viewBox='0,0,144.10576,144.91623'>
                            <g transform='translate(-164.41679,-112.94712)'>
                                <g stroke-miterlimit='10'>
                                    <path d='M166.9168,184.02633c0,-36.49454 35.0206,-66.07921 72.05288,-66.07921c37.03228,0 67.05288,29.58467 67.05288,66.07921c0,6.94489 -1.08716,13.63956 -3.10292,19.92772c-2.71464,8.46831 -7.1134,16.19939 -12.809,22.81158c-2.31017,2.68194 -7.54471,12.91599 -7.54471,12.91599c0,0 -5.46714,-1.18309 -8.44434,0.6266c-3.86867,2.35159 -10.95356,10.86714 -10.95356,10.86714c0,0 -6.96906,-3.20396 -9.87477,-2.58085c-2.64748,0.56773 -6.72538,5.77072 -6.72538,5.77072c0,0 -5.5023,-4.25969 -7.5982,-4.25969c-3.08622,0 -9.09924,3.48259 -9.09924,3.48259c0,0 -6.0782,-5.11244 -9.00348,-5.91884c-4.26461,-1.17561 -12.23343,0.75049 -12.23343,0.75049c0,0 -5.18164,-8.26065 -7.60688,-9.90388c-3.50443,-2.37445 -8.8271,-3.95414 -8.8271,-3.95414c0,0 -5.33472,-8.81718 -7.27019,-11.40895c-4.81099,-6.44239 -13.46422,-9.83437 -15.65729,-17.76175c-1.53558,-5.55073 -2.35527,-21.36472 -2.35527,-21.36472z' fill='#191e2e' stroke='#000000' stroke-width='5' stroke-linecap='butt'></path>
                                    <path d='M167.94713,180c0,-37.03228 35.0206,-67.05288 72.05288,-67.05288c37.03228,0 67.05288,30.0206 67.05288,67.05288c0,7.04722 -1.08716,13.84053 -3.10292,20.22135c-2.71464,8.59309 -7.1134,16.43809 -12.809,23.14771c-2.31017,2.72146 -7.54471,13.1063 -7.54471,13.1063c0,0 -5.46714,-1.20052 -8.44434,0.63584c-3.86867,2.38624 -10.95356,11.02726 -10.95356,11.02726c0,0 -6.96906,-3.25117 -9.87477,-2.61888c-2.64748,0.5761 -6.72538,5.85575 -6.72538,5.85575c0,0 -5.5023,-4.32246 -7.5982,-4.32246c-3.08622,0 -9.09924,3.5339 -9.09924,3.5339c0,0 -6.0782,-5.18777 -9.00348,-6.00605c-4.26461,-1.19293 -12.23343,0.76155 -12.23343,0.76155c0,0 -5.18164,-8.38236 -7.60688,-10.04981c-3.50443,-2.40943 -8.8271,-4.0124 -8.8271,-4.0124c0,0 -5.33472,-8.9471 -7.27019,-11.57706c-4.81099,-6.53732 -13.46422,-9.97928 -15.65729,-18.02347c-1.53558,-5.63252 -2.35527,-21.67953 -2.35527,-21.67953z' fill='#191e2e' stroke='none' stroke-width='0' stroke-linecap='butt'></path>
                                    <path d='' fill='#ffffff' stroke='none' stroke-width='0.5' stroke-linecap='butt'></path>
                                    <path d='' fill-opacity='0.26667' fill='#97affd' stroke-opacity='0.48627' stroke='#ffffff' stroke-width='0' stroke-linecap='butt'></path>
                                    <path d='M216.22445,188.06994c0,0 1.02834,11.73245 -3.62335,21.11235c-4.65169,9.3799 -13.06183,10.03776 -13.06183,10.03776c0,0 7.0703,-3.03121 10.89231,-10.7381c4.34839,-8.76831 5.79288,-20.41201 5.79288,-20.41201z' fill='none' stroke='#2f3a50' stroke-width='3' stroke-linecap='round'></path>
                                </g>
                            </g>
                        </svg>

                        <svg class='head' version='1.1' xmlns='http://www.w3.org/2000/svg' width='115.68559' height='88.29441' viewBox='0,0,115.68559,88.29441'>
                            <g transform='translate(-191.87889,-75.62023)'>
                                <g stroke-miterlimit='10'>
                                    <path d='' fill='#ffffff' stroke='none' stroke-width='0.5' stroke-linecap='butt'></path>
                                    <path d='M195.12889,128.77752c0,-26.96048 21.33334,-48.81626 47.64934,-48.81626c26.316,0 47.64935,21.85578 47.64935,48.81626c0,0.60102 -9.22352,20.49284 -9.22352,20.49284l-7.75885,0.35623l-7.59417,6.15039l-8.64295,-1.74822l-11.70703,6.06119l-6.38599,-4.79382l-6.45999,2.36133l-7.01451,-7.38888l-8.11916,1.29382l-6.19237,-6.07265l-7.6263,-1.37795l-4.19835,-7.87062l-4.24236,-4.16907c0,0 -0.13314,-2.0999 -0.13314,-3.29458z' fill='none' stroke='#2f3a50' stroke-width='6' stroke-linecap='butt'></path>
                                    <path d='M195.31785,124.43649c0,-26.96048 21.33334,-48.81626 47.64934,-48.81626c26.316,0 47.64935,21.85578 47.64935,48.81626c0,1.03481 -0.08666,2.8866 -0.08666,2.8866c0,0 16.8538,15.99287 16.21847,17.23929c-0.66726,1.30905 -23.05667,-4.14265 -23.05667,-4.14265l-2.29866,4.5096l-7.75885,0.35623l-7.59417,6.15039l-8.64295,-1.74822l-11.70703,6.06119l-6.38599,-4.79382l-6.45999,2.36133l-7.01451,-7.38888l-8.11916,1.29382l-6.19237,-6.07265l-7.6263,-1.37795l-4.19835,-7.87062l-4.24236,-4.16907c0,0 -0.13314,-2.0999 -0.13314,-3.29458z' fill='#191e2e' stroke-opacity='0.48627' stroke='#ffffff' stroke-width='0' stroke-linecap='butt'></path>
                                    <path d='M271.10348,122.46768l10.06374,-3.28166l24.06547,24.28424' fill='none' stroke='#2f3a50' stroke-width='6' stroke-linecap='round'></path>
                                    <path d='M306.56448,144.85764l-41.62024,-8.16845l2.44004,-7.87698' fill='none' stroke='#000000' stroke-width='3.5' stroke-linecap='round'></path>
                                    <path d='M276.02738,115.72434c-0.66448,-4.64715 2.56411,-8.95308 7.21127,-9.61756c4.64715,-0.66448 8.95309,2.56411 9.61757,7.21126c0.46467,3.24972 -1.94776,8.02206 -5.96624,9.09336c-2.11289,-1.73012 -5.08673,-5.03426 -5.08673,-5.03426c0,0 -4.12095,1.16329 -4.60481,1.54229c-0.16433,-0.04891 -0.62732,-0.38126 -0.72803,-0.61269c-0.30602,-0.70328 -0.36302,-2.02286 -0.44303,-2.58239z' fill='#ffffff' stroke='none' stroke-width='0.5' stroke-linecap='butt'></path>
                                    <path d='M242.49281,125.6424c0,-4.69442 3.80558,-8.5 8.5,-8.5c4.69442,0 8.5,3.80558 8.5,8.5c0,4.69442 -3.80558,8.5 -8.5,8.5c-4.69442,0 -8.5,-3.80558 -8.5,-8.5z' fill='#ffffff' stroke='none' stroke-width='0.5' stroke-linecap='butt'></path>
                                    <path d='' fill-opacity='0.26667' fill='#97affd' stroke-opacity='0.48627' stroke='#ffffff' stroke-width='0' stroke-linecap='butt'></path>
                                </g>
                            </g>
                        </svg>
                    </div>

                    <svg id='gnd' version='1.1' xmlns='http://www.w3.org/2000/svg' width='475' height='530' viewBox='0,0,163.40011,85.20095'>
                        <g transform='translate(-176.25,-207.64957)'>
                            <g stroke='#000000' stroke-width='2.5' stroke-linecap='round' stroke-miterlimit='10'>
                                <path d='M295.5,273.1829c0,0 -57.38915,6.69521 -76.94095,-9.01465c-13.65063,-10.50609 15.70098,-20.69467 -2.5451,-19.94465c-30.31027,2.05753 -38.51396,-26.84135 -38.51396,-26.84135c0,0 6.50084,13.30023 18.93224,19.17888c9.53286,4.50796 26.23632,-1.02541 32.09529,4.95137c3.62417,3.69704 2.8012,6.33005 0.66517,8.49452c-3.79415,3.84467 -11.7312,6.21103 -6.24682,10.43645c22.01082,16.95812 72.55412,12.73944 72.55412,12.73944z' fill='#000000'></path>
                                <path d='M338.92138,217.76285c0,0 -17.49626,12.55408 -45.36424,10.00353c-8.39872,-0.76867 -17.29557,-6.23066 -17.29557,-6.23066c0,0 3.06461,-2.23972 15.41857,0.72484c26.30467,6.31228 47.24124,-4.49771 47.24124,-4.49771z' fill='#000000'></path>
                                <path d='M209.14443,223.00182l1.34223,15.4356l-10.0667,-15.4356' fill='none'></path>
                                <path d='M198.20391,230.41806l12.95386,7.34824l6.71113,-12.08004' fill='none'></path>
                                <path d='M211.19621,238.53825l8.5262,-6.09014' fill='none'></path>
                                <path d='M317.57068,215.80173l5.27812,6.49615l0.40601,-13.39831' fill='none'></path>
                                <path d='M323.66082,222.70389l6.09014,-9.33822' fill='none'></path>
                            </g>
                        </g>
                    </svg>
                </div>
                <div class='message-text'>
                    <div>✅ Loan record updated successfully!</div>
                    <div style='font-size: 0.9rem; opacity: 0.7; margin-top: 10px;'>Redirecting to Employee Loans...</div>
                </div>
            </div>
            <script>
                // Auto redirect after 3 seconds
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 3000);

                // Check for dark mode
                if (localStorage.getItem('darkMode') === 'enabled') {
                    document.body.classList.add('dark-mode');
                }
            </script>
        </body>
        </html>";
        exit();
    } else {
        // ERROR - Show error with loading animation then redirect back
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Error!</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    background: #f5f5f5;
                    font-family: 'Poppins', sans-serif;
                }
                body.dark-mode {
                    background: #1a1a2e;
                }
                .loader-wrapper {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 30px;
                }
                .loader {
                    scale: 0.75;
                    position: relative;
                    width: 200px;
                    height: 200px;
                    translate: 10px -20px;
                }
                .loader svg {
                    position: absolute;
                    top: 0;
                    left: 0;
                }
                .head {
                    translate: 27px -30px;
                    z-index: 3;
                    animation: bob 1s infinite ease-in;
                }
                .bod {
                    translate: 0px 30px;
                    z-index: 3;
                    animation: bob 1s infinite ease-in-out;
                }
                .legr {
                    translate: 75px 135px;
                    z-index: 0;
                    animation: rstep 1s infinite ease-in;
                }
                .legr {
                    animation-delay: 0.45s;
                }
                .legl {
                    translate: 30px 155px;
                    z-index: 3;
                    animation: lstep 1s infinite ease-in;
                }
                @keyframes bob {
                    0% { transform: translateY(0) rotate(3deg); }
                    5% { transform: translateY(0) rotate(3deg); }
                    25% { transform: translateY(5px) rotate(0deg); }
                    50% { transform: translateY(0px) rotate(-3deg); }
                    70% { transform: translateY(5px) rotate(0deg); }
                    100% { transform: translateY(0) rotate(3deg); }
                }
                @keyframes lstep {
                    0% { transform: translateY(0) rotate(-5deg); }
                    33% { transform: translateY(-15px) translate(32px) rotate(35deg); }
                    66% { transform: translateY(0) translate(25px) rotate(-25deg); }
                    100% { transform: translateY(0) rotate(-5deg); }
                }
                @keyframes rstep {
                    0% { transform: translateY(0) translate(0px) rotate(-5deg); }
                    33% { transform: translateY(-10px) translate(30px) rotate(35deg); }
                    66% { transform: translateY(0) translate(20px) rotate(-25deg); }
                    100% { transform: translateY(0) translate(0px) rotate(-5deg); }
                }
                #gnd {
                    translate: -140px 0;
                    rotate: 10deg;
                    z-index: -1;
                    filter: blur(0.5px) drop-shadow(1px 3px 5px #000000);
                    opacity: 0.25;
                    animation: scroll 5s infinite linear;
                }
                @keyframes scroll {
                    0% { transform: translateY(25px) translate(50px); opacity: 0; }
                    33% { opacity: 0.25; }
                    66% { opacity: 0.25; }
                    to { transform: translateY(-50px) translate(-100px); opacity: 0; }
                }
                .message-text {
                    font-size: 1.2rem;
                    font-weight: 500;
                    color: #e74c3c;
                    text-align: center;
                    animation: pulse 1.5s ease-in-out infinite;
                }
                body.dark-mode .message-text {
                    color: #ff6b6b;
                }
                @keyframes pulse {
                    0%, 100% { opacity: 1; }
                    50% { opacity: 0.5; }
                }
                .status-icon {
                    font-size: 3rem;
                    margin-bottom: 10px;
                }
            </style>
        </head>
        <body>
            <div class='loader-wrapper'>
                <div class='loader'>
                    <svg class='legl' version='1.1' xmlns='http://www.w3.org/2000/svg' width='20.69332' height='68.19944' viewBox='0,0,20.69332,68.19944'>
                        <g transform='translate(-201.44063,-235.75466)'>
                            <g stroke-miterlimit='10'>
                                <path d='' fill='#ffffff' stroke='none' stroke-width='0.5'></path>
                                <path d='' fill-opacity='0.26667' fill='#97affd' stroke-opacity='0.48627' stroke='#ffffff' stroke-width='0'></path>
                                <path d='M218.11971,301.20087c-2.20708,1.73229 -4.41416,0 -4.41416,0l-1.43017,-1.1437c-1.42954,-1.40829 -3.04351,-2.54728 -4.56954,-3.87927c-0.95183,-0.8308 -2.29837,-1.49883 -2.7652,-2.55433c-0.42378,-0.95815 0.14432,-2.02654 0.29355,-3.03399c0.41251,-2.78499 1.82164,-5.43386 2.41472,-8.22683c1.25895,-4.44509 2.73863,-8.98683 3.15318,-13.54796c0.22615,-2.4883 -0.21672,-5.0155 -0.00278,-7.50605c0.30636,-3.56649 1.24602,-7.10406 1.59992,-10.6738c0.29105,-2.93579 -0.00785,-5.9806 -0.00785,-8.93046c0,0 0,-2.44982 3.12129,-2.44982c3.12129,0 3.12129,2.44982 3.12129,2.44982c0,3.06839 0.28868,6.22201 -0.00786,9.27779c-0.34637,3.56935 -1.30115,7.10906 -1.59992,10.6738c-0.2103,2.50918 0.22586,5.05326 -0.00278,7.56284c-0.43159,4.7371 -1.94029,9.46317 -3.24651,14.07835c-0.47439,2.23403 -1.29927,4.31705 -2.05805,6.47156c-0.18628,0.52896 -0.1402,1.0974 -0.327,1.62624c-0.09463,0.26791 -0.64731,0.47816 -0.50641,0.73323c0.19122,0.34617 0.86423,0.3445 1.2346,0.58502c1.88637,1.22503 3.50777,2.79494 5.03,4.28305l0.96971,0.73991c0,0 2.20708,1.73229 0,3.46457z' fill='none' stroke='#191e2e' stroke-width='7'></path>
                            </g>
                        </g>
                    </svg>

                    <svg class='legr' version='1.1' xmlns='http://www.w3.org/2000/svg' width='41.02537' height='64.85502' viewBox='0,0,41.02537,64.85502'>
                        <g transform='translate(-241.54137,-218.44347)'>
                            <g stroke-miterlimit='10'>
                                <path d='M279.06674,279.42662c-2.27967,1.98991 -6.08116,0.58804 -6.08116,0.58804l-2.47264,-0.92915c-2.58799,-1.18826 -5.31176,-2.08831 -7.99917,-3.18902c-1.67622,-0.68654 -3.82471,-1.16116 -4.93147,-2.13229c-1.00468,-0.88156 -0.69132,-2.00318 -0.92827,-3.00935c-0.65501,-2.78142 0.12275,-5.56236 -0.287,-8.37565c-0.2181,-4.51941 -0.17458,-9.16283 -1.60696,-13.68334c-0.78143,-2.46614 -2.50162,-4.88125 -3.30086,-7.34796c-1.14452,-3.53236 -1.40387,-7.12078 -2.48433,-10.66266c-0.88858,-2.91287 -2.63779,-5.85389 -3.93351,-8.74177c0,0 -1.07608,-2.39835 3.22395,-2.81415c4.30003,-0.41581 2.41605,1.98254 2.41605,1.98254c1.34779,3.00392 3.13072,6.05282 4.06444,9.0839c1.09065,3.54049 1.33011,7.13302 2.48433,10.66266c0.81245,2.48448 2.5308,4.917 3.31813,7.40431c1.48619,4.69506 1.48366,9.52281 1.71137,14.21503c0.32776,2.25028 0.10631,4.39942 0.00736,6.60975c-0.02429,0.54266 0.28888,1.09302 0.26382,1.63563c-0.01269,0.27488 -0.68173,0.55435 -0.37558,0.78529c0.41549,0.31342 1.34191,0.22213 1.95781,0.40826c3.13684,0.94799 6.06014,2.26892 8.81088,3.52298l1.66093,0.59519c0,0 6.76155,1.40187 4.48187,3.39177z' fill='none' stroke='#000000' stroke-width='7'></path>
                                <path d='' fill='#ffffff' stroke='none' stroke-width='0.5'></path>
                                <path d='' fill-opacity='0.26667' fill='#97affd' stroke-opacity='0.48627' stroke='#ffffff' stroke-width='0'></path>
                            </g>
                        </g>
                    </svg>

                    <div class='bod'>
                        <svg version='1.1' xmlns='http://www.w3.org/2000/svg' width='144.10576' height='144.91623' viewBox='0,0,144.10576,144.91623'>
                            <g transform='translate(-164.41679,-112.94712)'>
                                <g stroke-miterlimit='10'>
                                    <path d='M166.9168,184.02633c0,-36.49454 35.0206,-66.07921 72.05288,-66.07921c37.03228,0 67.05288,29.58467 67.05288,66.07921c0,6.94489 -1.08716,13.63956 -3.10292,19.92772c-2.71464,8.46831 -7.1134,16.19939 -12.809,22.81158c-2.31017,2.68194 -7.54471,12.91599 -7.54471,12.91599c0,0 -5.46714,-1.18309 -8.44434,0.6266c-3.86867,2.35159 -10.95356,10.86714 -10.95356,10.86714c0,0 -6.96906,-3.20396 -9.87477,-2.58085c-2.64748,0.56773 -6.72538,5.77072 -6.72538,5.77072c0,0 -5.5023,-4.25969 -7.5982,-4.25969c-3.08622,0 -9.09924,3.48259 -9.09924,3.48259c0,0 -6.0782,-5.11244 -9.00348,-5.91884c-4.26461,-1.17561 -12.23343,0.75049 -12.23343,0.75049c0,0 -5.18164,-8.26065 -7.60688,-9.90388c-3.50443,-2.37445 -8.8271,-3.95414 -8.8271,-3.95414c0,0 -5.33472,-8.81718 -7.27019,-11.40895c-4.81099,-6.44239 -13.46422,-9.83437 -15.65729,-17.76175c-1.53558,-5.55073 -2.35527,-21.36472 -2.35527,-21.36472z' fill='#191e2e' stroke='#000000' stroke-width='5' stroke-linecap='butt'></path>
                                    <path d='M167.94713,180c0,-37.03228 35.0206,-67.05288 72.05288,-67.05288c37.03228,0 67.05288,30.0206 67.05288,67.05288c0,7.04722 -1.08716,13.84053 -3.10292,20.22135c-2.71464,8.59309 -7.1134,16.43809 -12.809,23.14771c-2.31017,2.72146 -7.54471,13.1063 -7.54471,13.1063c0,0 -5.46714,-1.20052 -8.44434,0.63584c-3.86867,2.38624 -10.95356,11.02726 -10.95356,11.02726c0,0 -6.96906,-3.25117 -9.87477,-2.61888c-2.64748,0.5761 -6.72538,5.85575 -6.72538,5.85575c0,0 -5.5023,-4.32246 -7.5982,-4.32246c-3.08622,0 -9.09924,3.5339 -9.09924,3.5339c0,0 -6.0782,-5.18777 -9.00348,-6.00605c-4.26461,-1.19293 -12.23343,0.76155 -12.23343,0.76155c0,0 -5.18164,-8.38236 -7.60688,-10.04981c-3.50443,-2.40943 -8.8271,-4.0124 -8.8271,-4.0124c0,0 -5.33472,-8.9471 -7.27019,-11.57706c-4.81099,-6.53732 -13.46422,-9.97928 -15.65729,-18.02347c-1.53558,-5.63252 -2.35527,-21.67953 -2.35527,-21.67953z' fill='#191e2e' stroke='none' stroke-width='0' stroke-linecap='butt'></path>
                                    <path d='' fill='#ffffff' stroke='none' stroke-width='0.5' stroke-linecap='butt'></path>
                                    <path d='' fill-opacity='0.26667' fill='#97affd' stroke-opacity='0.48627' stroke='#ffffff' stroke-width='0' stroke-linecap='butt'></path>
                                    <path d='M216.22445,188.06994c0,0 1.02834,11.73245 -3.62335,21.11235c-4.65169,9.3799 -13.06183,10.03776 -13.06183,10.03776c0,0 7.0703,-3.03121 10.89231,-10.7381c4.34839,-8.76831 5.79288,-20.41201 5.79288,-20.41201z' fill='none' stroke='#2f3a50' stroke-width='3' stroke-linecap='round'></path>
                                </g>
                            </g>
                        </svg>

                        <svg class='head' version='1.1' xmlns='http://www.w3.org/2000/svg' width='115.68559' height='88.29441' viewBox='0,0,115.68559,88.29441'>
                            <g transform='translate(-191.87889,-75.62023)'>
                                <g stroke-miterlimit='10'>
                                    <path d='' fill='#ffffff' stroke='none' stroke-width='0.5' stroke-linecap='butt'></path>
                                    <path d='M195.12889,128.77752c0,-26.96048 21.33334,-48.81626 47.64934,-48.81626c26.316,0 47.64935,21.85578 47.64935,48.81626c0,0.60102 -9.22352,20.49284 -9.22352,20.49284l-7.75885,0.35623l-7.59417,6.15039l-8.64295,-1.74822l-11.70703,6.06119l-6.38599,-4.79382l-6.45999,2.36133l-7.01451,-7.38888l-8.11916,1.29382l-6.19237,-6.07265l-7.6263,-1.37795l-4.19835,-7.87062l-4.24236,-4.16907c0,0 -0.13314,-2.0999 -0.13314,-3.29458z' fill='none' stroke='#2f3a50' stroke-width='6' stroke-linecap='butt'></path>
                                    <path d='M195.31785,124.43649c0,-26.96048 21.33334,-48.81626 47.64934,-48.81626c26.316,0 47.64935,21.85578 47.64935,48.81626c0,1.03481 -0.08666,2.8866 -0.08666,2.8866c0,0 16.8538,15.99287 16.21847,17.23929c-0.66726,1.30905 -23.05667,-4.14265 -23.05667,-4.14265l-2.29866,4.5096l-7.75885,0.35623l-7.59417,6.15039l-8.64295,-1.74822l-11.70703,6.06119l-6.38599,-4.79382l-6.45999,2.36133l-7.01451,-7.38888l-8.11916,1.29382l-6.19237,-6.07265l-7.6263,-1.37795l-4.19835,-7.87062l-4.24236,-4.16907c0,0 -0.13314,-2.0999 -0.13314,-3.29458z' fill='#191e2e' stroke-opacity='0.48627' stroke='#ffffff' stroke-width='0' stroke-linecap='butt'></path>
                                    <path d='M271.10348,122.46768l10.06374,-3.28166l24.06547,24.28424' fill='none' stroke='#2f3a50' stroke-width='6' stroke-linecap='round'></path>
                                    <path d='M306.56448,144.85764l-41.62024,-8.16845l2.44004,-7.87698' fill='none' stroke='#000000' stroke-width='3.5' stroke-linecap='round'></path>
                                    <path d='M276.02738,115.72434c-0.66448,-4.64715 2.56411,-8.95308 7.21127,-9.61756c4.64715,-0.66448 8.95309,2.56411 9.61757,7.21126c0.46467,3.24972 -1.94776,8.02206 -5.96624,9.09336c-2.11289,-1.73012 -5.08673,-5.03426 -5.08673,-5.03426c0,0 -4.12095,1.16329 -4.60481,1.54229c-0.16433,-0.04891 -0.62732,-0.38126 -0.72803,-0.61269c-0.30602,-0.70328 -0.36302,-2.02286 -0.44303,-2.58239z' fill='#ffffff' stroke='none' stroke-width='0.5' stroke-linecap='butt'></path>
                                    <path d='M242.49281,125.6424c0,-4.69442 3.80558,-8.5 8.5,-8.5c4.69442,0 8.5,3.80558 8.5,8.5c0,4.69442 -3.80558,8.5 -8.5,8.5c-4.69442,0 -8.5,-3.80558 -8.5,-8.5z' fill='#ffffff' stroke='none' stroke-width='0.5' stroke-linecap='butt'></path>
                                    <path d='' fill-opacity='0.26667' fill='#97affd' stroke-opacity='0.48627' stroke='#ffffff' stroke-width='0' stroke-linecap='butt'></path>
                                </g>
                            </g>
                        </svg>
                    </div>

                    <svg id='gnd' version='1.1' xmlns='http://www.w3.org/2000/svg' width='475' height='530' viewBox='0,0,163.40011,85.20095'>
                        <g transform='translate(-176.25,-207.64957)'>
                            <g stroke='#000000' stroke-width='2.5' stroke-linecap='round' stroke-miterlimit='10'>
                                <path d='M295.5,273.1829c0,0 -57.38915,6.69521 -76.94095,-9.01465c-13.65063,-10.50609 15.70098,-20.69467 -2.5451,-19.94465c-30.31027,2.05753 -38.51396,-26.84135 -38.51396,-26.84135c0,0 6.50084,13.30023 18.93224,19.17888c9.53286,4.50796 26.23632,-1.02541 32.09529,4.95137c3.62417,3.69704 2.8012,6.33005 0.66517,8.49452c-3.79415,3.84467 -11.7312,6.21103 -6.24682,10.43645c22.01082,16.95812 72.55412,12.73944 72.55412,12.73944z' fill='#000000'></path>
                                <path d='M338.92138,217.76285c0,0 -17.49626,12.55408 -45.36424,10.00353c-8.39872,-0.76867 -17.29557,-6.23066 -17.29557,-6.23066c0,0 3.06461,-2.23972 15.41857,0.72484c26.30467,6.31228 47.24124,-4.49771 47.24124,-4.49771z' fill='#000000'></path>
                                <path d='M209.14443,223.00182l1.34223,15.4356l-10.0667,-15.4356' fill='none'></path>
                                <path d='M198.20391,230.41806l12.95386,7.34824l6.71113,-12.08004' fill='none'></path>
                                <path d='M211.19621,238.53825l8.5262,-6.09014' fill='none'></path>
                                <path d='M317.57068,215.80173l5.27812,6.49615l0.40601,-13.39831' fill='none'></path>
                                <path d='M323.66082,222.70389l6.09014,-9.33822' fill='none'></path>
                            </g>
                        </g>
                    </svg>
                </div>
                <div class='message-text'>
                    <div>❌ Error updating record!</div>
                    <div style='font-size: 0.9rem; opacity: 0.7; margin-top: 10px;'>" . mysqli_error($conn) . "</div>
                    <div style='font-size: 0.9rem; opacity: 0.7; margin-top: 5px;'>Redirecting back...</div>
                </div>
            </div>
            <script>
                // Auto redirect after 4 seconds
                setTimeout(function() {
                    window.location.href = 'edit_loan.php?id=$loan_id';
                }, 4000);

                // Check for dark mode
                if (localStorage.getItem('darkMode') === 'enabled') {
                    document.body.classList.add('dark-mode');
                }
            </script>
        </body>
        </html>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="icon" href="MDUE LOGO.jpg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDIT LOAN RECORD</title>
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

        /* ===== HEADER WITH LOGO ABOVE TITLE ===== */
        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
            gap: 12px;
        }

        /* Logo */
        .logo-stretch {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            padding: 0;
            width: 100%;
        }

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

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .header-left h2 {
            color: #2c3e50;
            margin: 0;
            font-weight: 600;
            font-size: 1.2rem;
        }

        body.dark-mode .header-left h2 {
            color: #e0e0e0;
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

        /* ===== THEME TOGGLE ===== */
        .theme-wrapper {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .theme-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-color);
        }

        .theme-switch {
            --toggle-size: 20px;
            --container-width: 3.75em;
            --container-height: 1.8em;
            --container-radius: 4em;
            --container-light-bg: #3D7EAE;
            --container-night-bg: #1D1F2C;
            --circle-container-diameter: 2.2em;
            --sun-moon-diameter: 1.5em;
            --sun-bg: #ECCA2F;
            --moon-bg: #C4C9D1;
            --spot-color: #959DB1;
            --circle-container-offset: calc((var(--circle-container-diameter) - var(--container-height)) / 2 * -1);
            --stars-color: #fff;
            --clouds-color: #F3FDFF;
            --back-clouds-color: #AACADF;
            --transition: .4s cubic-bezier(0, -0.02, 0.4, 1.25);
            --circle-transition: .3s cubic-bezier(0, -0.02, 0.35, 1.17);
        }

        .theme-switch, .theme-switch *, .theme-switch *::before, .theme-switch *::after {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-size: var(--toggle-size);
        }

        .theme-switch__container {
            width: var(--container-width);
            height: var(--container-height);
            background-color: var(--container-light-bg);
            border-radius: var(--container-radius);
            overflow: hidden;
            cursor: pointer;
            -webkit-box-shadow: 0em -0.062em 0.062em rgba(0, 0, 0, 0.25), 0em 0.062em 0.125em rgba(255, 255, 255, 0.94);
            box-shadow: 0em -0.062em 0.062em rgba(0, 0, 0, 0.25), 0em 0.062em 0.125em rgba(255, 255, 255, 0.94);
            -webkit-transition: var(--transition);
            -o-transition: var(--transition);
            transition: var(--transition);
            position: relative;
        }

        .theme-switch__container::before {
            content: "";
            position: absolute;
            z-index: 1;
            inset: 0;
            -webkit-box-shadow: 0em 0.05em 0.187em rgba(0, 0, 0, 0.25) inset, 0em 0.05em 0.187em rgba(0, 0, 0, 0.25) inset;
            box-shadow: 0em 0.05em 0.187em rgba(0, 0, 0, 0.25) inset, 0em 0.05em 0.187em rgba(0, 0, 0, 0.25) inset;
            border-radius: var(--container-radius);
        }

        .theme-switch__checkbox {
            display: none;
        }

        .theme-switch__circle-container {
            width: var(--circle-container-diameter);
            height: var(--circle-container-diameter);
            background-color: rgba(255, 255, 255, 0.1);
            position: absolute;
            left: var(--circle-container-offset);
            top: var(--circle-container-offset);
            border-radius: var(--container-radius);
            -webkit-box-shadow: inset 0 0 0 2.5em rgba(255, 255, 255, 0.1), inset 0 0 0 2.5em rgba(255, 255, 255, 0.1), 0 0 0 0.4em rgba(255, 255, 255, 0.1), 0 0 0 0.8em rgba(255, 255, 255, 0.1);
            box-shadow: inset 0 0 0 2.5em rgba(255, 255, 255, 0.1), inset 0 0 0 2.5em rgba(255, 255, 255, 0.1), 0 0 0 0.4em rgba(255, 255, 255, 0.1), 0 0 0 0.8em rgba(255, 255, 255, 0.1);
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-transition: var(--circle-transition);
            -o-transition: var(--circle-transition);
            transition: var(--circle-transition);
            pointer-events: none;
        }

        .theme-switch__sun-moon-container {
            pointer-events: auto;
            position: relative;
            z-index: 2;
            width: var(--sun-moon-diameter);
            height: var(--sun-moon-diameter);
            margin: auto;
            border-radius: var(--container-radius);
            background-color: var(--sun-bg);
            -webkit-box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset, 0em -0.062em 0.062em 0em #a1872a inset;
            box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset, 0em -0.062em 0.062em 0em #a1872a inset;
            -webkit-filter: drop-shadow(0.062em 0.125em 0.125em rgba(0, 0, 0, 0.25)) drop-shadow(0em 0.062em 0.125em rgba(0, 0, 0, 0.25));
            filter: drop-shadow(0.062em 0.125em 0.125em rgba(0, 0, 0, 0.25)) drop-shadow(0em 0.062em 0.125em rgba(0, 0, 0, 0.25));
            overflow: hidden;
            -webkit-transition: var(--transition);
            -o-transition: var(--transition);
            transition: var(--transition);
        }

        .theme-switch__moon {
            -webkit-transform: translateX(100%);
            -ms-transform: translateX(100%);
            transform: translateX(100%);
            width: 100%;
            height: 100%;
            background-color: var(--moon-bg);
            border-radius: inherit;
            -webkit-box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset, 0em -0.062em 0.062em 0em #969696 inset;
            box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset, 0em -0.062em 0.062em 0em #969696 inset;
            -webkit-transition: var(--transition);
            -o-transition: var(--transition);
            transition: var(--transition);
            position: relative;
        }

        .theme-switch__spot {
            position: absolute;
            top: 0.5em;
            left: 0.2em;
            width: 0.5em;
            height: 0.5em;
            border-radius: var(--container-radius);
            background-color: var(--spot-color);
            -webkit-box-shadow: 0em 0.0312em 0.062em rgba(0, 0, 0, 0.25) inset;
            box-shadow: 0em 0.0312em 0.062em rgba(0, 0, 0, 0.25) inset;
        }

        .theme-switch__spot:nth-of-type(2) {
            width: 0.25em;
            height: 0.25em;
            top: 0.6em;
            left: 0.9em;
        }

        .theme-switch__spot:nth-last-of-type(3) {
            width: 0.18em;
            height: 0.18em;
            top: 0.2em;
            left: 0.55em;
        }

        .theme-switch__clouds {
            width: 0.9em;
            height: 0.9em;
            background-color: var(--clouds-color);
            border-radius: var(--container-radius);
            position: absolute;
            bottom: -0.4em;
            left: 0.2em;
            -webkit-box-shadow: 0.6em 0.2em var(--clouds-color), -0.2em -0.2em var(--back-clouds-color), 0.9em 0.25em var(--clouds-color), 0.3em -0.08em var(--back-clouds-color), 1.4em 0 var(--clouds-color), 0.8em -0.04em var(--back-clouds-color), 1.9em 0.2em var(--clouds-color), 1.3em -0.2em var(--back-clouds-color), 2.3em -0.04em var(--clouds-color), 1.7em 0em var(--back-clouds-color), 2.9em -0.2em var(--clouds-color), 2.2em -0.28em var(--back-clouds-color), 3em -1.1em 0 0.28em var(--clouds-color), 2.6em -0.4em var(--back-clouds-color), 2.7em -1.35em 0 0.28em var(--back-clouds-color);
            box-shadow: 0.6em 0.2em var(--clouds-color), -0.2em -0.2em var(--back-clouds-color), 0.9em 0.25em var(--clouds-color), 0.3em -0.08em var(--back-clouds-color), 1.4em 0 var(--clouds-color), 0.8em -0.04em var(--back-clouds-color), 1.9em 0.2em var(--clouds-color), 1.3em -0.2em var(--back-clouds-color), 2.3em -0.04em var(--clouds-color), 1.7em 0em var(--back-clouds-color), 2.9em -0.2em var(--clouds-color), 2.2em -0.28em var(--back-clouds-color), 3em -1.1em 0 0.28em var(--clouds-color), 2.6em -0.4em var(--back-clouds-color), 2.7em -1.35em 0 0.28em var(--back-clouds-color);
            -webkit-transition: 0.5s cubic-bezier(0, -0.02, 0.4, 1.25);
            -o-transition: 0.5s cubic-bezier(0, -0.02, 0.4, 1.25);
            transition: 0.5s cubic-bezier(0, -0.02, 0.4, 1.25);
        }

        .theme-switch__stars-container {
            position: absolute;
            color: var(--stars-color);
            top: -100%;
            left: 0.2em;
            width: 1.8em;
            height: auto;
            -webkit-transition: var(--transition);
            -o-transition: var(--transition);
            transition: var(--transition);
        }

        .theme-switch__stars-container svg {
            width: 100%;
            height: auto;
        }

        .theme-switch__checkbox:checked + .theme-switch__container {
            background-color: var(--container-night-bg);
        }

        .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__circle-container {
            left: calc(100% - var(--circle-container-offset) - var(--circle-container-diameter));
        }

        .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__circle-container:hover {
            left: calc(100% - var(--circle-container-offset) - var(--circle-container-diameter) - 0.12em);
        }

        .theme-switch__circle-container:hover {
            left: calc(var(--circle-container-offset) + 0.12em);
        }

        .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__moon {
            -webkit-transform: translate(0);
            -ms-transform: translate(0);
            transform: translate(0);
        }

        .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__clouds {
            bottom: -2.6em;
        }

        .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__stars-container {
            top: 50%;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        /* Card */
        .card-custom {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, box-shadow 0.3s;
            max-width: 800px;
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

        /* Responsive logo */
        @media (max-width: 1200px) {
            .logo-stretch img {
                width: 100%;
                max-width: 1150px;
            }
        }

        @media (max-width: 768px) {
            .logo-stretch img {
                height: 60px;
                max-height: 68px;
            }

            .header-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .header-left {
                justify-content: center;
                flex-wrap: wrap;
            }

            .header-left h2 {
                font-size: 1rem;
            }

            .theme-wrapper {
                justify-content: center;
            }

            .card-custom {
                margin: 10px;
                padding: 20px !important;
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
        <div class="header">
            <!-- Logo -->
            <div class="logo-stretch">
                <img src="MDUE LOGO STRETCH.png" alt="MDUE Logo" class="img-fluid" />
            </div>
            
            <!-- Header Content: Back Button, Title, and Theme Toggle -->
            <div class="header-content">
                <div class="header-left">
                    <a href="index.php" class="back-btn">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <h2>✏️ Edit Loan Record</h2>
                </div>
                <div class="theme-wrapper">
                    <span class="theme-label">🌙</span>
                    <label class="theme-switch">
                        <input type="checkbox" class="theme-switch__checkbox" id="themeToggle">
                        <div class="theme-switch__container">
                            <div class="theme-switch__clouds"></div>
                            <div class="theme-switch__stars-container">
                                <svg viewBox="0 0 144 55" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M135.831 3.00688C135.055 3.85027 134.111 4.29946 133 4.35447C134.111 4.40947 135.055 4.85867 135.831 5.71123C136.607 6.55462 136.996 7.56303 136.996 8.72727C136.996 7.95722 137.172 7.25134 137.525 6.59129C137.886 5.93124 138.372 5.39954 138.98 5.00535C139.598 4.60199 140.268 4.39114 141 4.35447C139.88 4.2903 138.936 3.85027 138.16 3.00688C137.384 2.16348 136.996 1.16425 136.996 0C136.996 1.16425 136.607 2.16348 135.831 3.00688ZM31 23.3545C32.1114 23.2995 33.0551 22.8503 33.8313 22.0069C34.6075 21.1635 34.9956 20.1642 34.9956 19C34.9956 20.1642 35.3837 21.1635 36.1599 22.0069C36.9361 22.8503 37.8798 23.2903 39 23.3545C38.2679 23.3911 37.5976 23.602 36.9802 24.0053C36.3716 24.3995 35.8864 24.9312 35.5248 25.5913C35.172 26.2513 34.9956 26.9572 34.9956 27.7273C34.9956 26.563 34.6075 25.5546 33.8313 24.7112C33.0551 23.8587 32.1114 23.4095 31 23.3545ZM0 36.3545C1.11136 36.2995 2.05513 35.8503 2.83131 35.0069C3.6075 34.1635 3.99559 33.1642 3.99559 32C3.99559 33.1642 4.38368 34.1635 5.15987 35.0069C5.93605 35.8503 6.87982 36.2903 8 36.3545C7.26792 36.3911 6.59757 36.602 5.98015 37.0053C5.37155 37.3995 4.88644 37.9312 4.52481 38.5913C4.172 39.2513 3.99559 39.9572 3.99559 40.7273C3.99559 39.563 3.6075 38.5546 2.83131 37.7112C2.05513 36.8587 1.11136 36.4095 0 36.3545ZM56.8313 24.0069C56.0551 24.8503 55.1114 25.2995 54 25.3545C55.1114 25.4095 56.0551 25.8587 56.8313 26.7112C57.6075 27.5546 57.9956 28.563 57.9956 29.7273C57.9956 28.9572 58.172 28.2513 58.5248 27.5913C58.8864 26.9312 59.3716 26.3995 59.9802 26.0053C60.5976 25.602 61.2679 25.3911 62 25.3545C60.8798 25.2903 59.9361 24.8503 59.1599 24.0069C58.3837 23.1635 57.9956 22.1642 57.9956 21C57.9956 22.1642 57.6075 23.1635 56.8313 24.0069ZM81 25.3545C82.1114 25.2995 83.0551 24.8503 83.8313 24.0069C84.6075 23.1635 84.9956 22.1642 84.9956 21C84.9956 22.1642 85.3837 23.1635 86.1599 24.0069C86.9361 24.8503 87.8798 25.2903 89 25.3545C88.2679 25.3911 87.5976 25.602 86.9802 26.0053C86.3716 26.3995 85.8864 26.9312 85.5248 27.5913C85.172 28.2513 84.9956 28.9572 84.9956 29.7273C84.9956 28.563 84.6075 27.5546 83.8313 26.7112C83.0551 25.8587 82.1114 25.4095 81 25.3545ZM136 36.3545C137.111 36.2995 138.055 35.8503 138.831 35.0069C139.607 34.1635 139.996 33.1642 139.996 32C139.996 33.1642 140.384 34.1635 141.16 35.0069C141.936 35.8503 142.88 36.2903 144 36.3545C143.268 36.3911 142.598 36.602 141.98 37.0053C141.372 37.3995 140.886 37.9312 140.525 38.5913C140.172 39.2513 139.996 39.9572 139.996 40.7273C139.996 39.563 139.607 38.5546 138.831 37.7112C138.055 36.8587 137.111 36.4095 136 36.3545ZM101.831 49.0069C101.055 49.8503 100.111 50.2995 99 50.3545C100.111 50.4095 101.055 50.8587 101.831 51.7112C102.607 52.5546 102.996 53.563 102.996 54.7273C102.996 53.9572 103.172 53.2513 103.525 52.5913C103.886 51.9312 104.372 51.3995 104.98 51.0053C105.598 50.602 106.268 50.3911 107 50.3545C105.88 50.2903 104.936 49.8503 104.16 49.0069C103.384 48.1635 102.996 47.1642 102.996 46C102.996 47.1642 102.607 48.1635 101.831 49.0069Z" fill="currentColor"/>
                                </svg>
                            </div>
                            <div class="theme-switch__circle-container">
                                <div class="theme-switch__sun-moon-container">
                                    <div class="theme-switch__moon">
                                        <div class="theme-switch__spot"></div>
                                        <div class="theme-switch__spot"></div>
                                        <div class="theme-switch__spot"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </label>
                    <span class="theme-label">☀️</span>
                </div>
            </div>
        </div>

        <!-- ===== FORM ===== -->
        <div class="card-custom p-4 p-md-5">
            <form method="post">
                <input type="hidden" name="loan_id" value="<?php echo htmlspecialchars($fetchLoanId); ?>">
                <input type="hidden" name="emp_id" value="<?php echo htmlspecialchars($fetchEmpId); ?>">
                
                <div class="row g-4">
                    <div class="col-md-12">
                        <label for="emp_name" class="form-label fw-medium text-center d-block">Employee Name:</label>
                        <input type="text" 
                               class="form-control form-control-custom"
                               id="emp_name" 
                               name="emp_name" 
                               value="<?php echo htmlspecialchars($fetchEmpName); ?>" 
                               required
                               placeholder="Enter full name">
                    </div>

                    <div class="col-md-6">
                        <label for="dept" class="form-label fw-medium text-center d-block">Department:</label>
                        <input type="text" 
                               class="form-control form-control-custom"
                               id="dept" 
                               name="dept" 
                               value="<?php echo htmlspecialchars($fetchDept); ?>" 
                               required
                               placeholder="Enter department">
                    </div>

                    <div class="col-md-6">
                        <label for="date_applied" class="form-label fw-medium text-center d-block">Date Applied:</label>
                        <input type="date" 
                               class="form-control form-control-custom"
                               id="date_applied" 
                               name="date_applied" 
                               value="<?php echo htmlspecialchars($fetchDateApplied); ?>" 
                               required>
                    </div>

                    <div class="col-md-6">
                        <label for="SSS_ID" class="form-label fw-medium text-center d-block">SSS ID (10 digits):</label>
                        <input type="text" 
                               class="form-control form-control-custom"
                               id="SSS_ID" 
                               name="SSS_ID"   
                               value="<?php echo htmlspecialchars($fetchSSS); ?>" 
                               maxlength="10"
                               pattern="[0-9]{10}"
                               placeholder="Enter 10-digit SSS ID"
                               oninput="limitSSS(this)">
                        <span class="char-counter d-block text-center" id="sssCounter"><?php echo strlen($fetchSSS); ?> / 10 digits</span>
                    </div>
                    
                    <div class="col-md-6">
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

                    <div class="col-md-6">
                        <label for="loan_type" class="form-label fw-medium text-center d-block">Loan Type:</label>
                        <select class="form-select form-control-custom" id="loan_type" name="loan_type" required>
                            <option value="SSS" <?php echo ($fetchLoanType == 'SSS') ? 'selected' : ''; ?>>SSS</option>
                            <option value="PAG-IBIG" <?php echo ($fetchLoanType == 'PAG-IBIG') ? 'selected' : ''; ?>>PAG-IBIG</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-medium text-center d-block">Status:</label>
                        <select class="form-select form-control-custom" id="status" name="status" required>
                            <option value="Unpaid" <?php echo ($fetchStatus == 'Unpaid') ? 'selected' : ''; ?>>Unpaid</option>
                            <option value="Paid" <?php echo ($fetchStatus == 'Paid') ? 'selected' : ''; ?>>Paid</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="loan_amount" class="form-label fw-medium text-center d-block">Loan Amount:</label>
                        <input type="number" 
                               class="form-control form-control-custom"
                               id="loan_amount" 
                               name="loan_amount" 
                               value="<?php echo htmlspecialchars($fetchLoanAmount); ?>"
                               step="0.01"
                               min="0"
                               required
                               placeholder="0.00">
                    </div>

                    <div class="col-md-6">
                        <label for="total_deduction" class="form-label fw-medium text-center d-block">Total Deduction:</label>
                        <input type="number" 
                               class="form-control form-control-custom"
                               id="total_deduction" 
                               name="total_deduction" 
                               value="<?php echo htmlspecialchars($fetchTotalDeduction); ?>"
                               step="0.01"
                               min="0"
                               required
                               placeholder="0.00">
                    </div>

                    <div class="col-md-6">
                        <label for="deduction_start" class="form-label fw-medium text-center d-block">Deduction Start:</label>
                        <input type="date" 
                               class="form-control form-control-custom"
                               id="deduction_start" 
                               name="deduction_start" 
                               value="<?php echo htmlspecialchars($fetchDeductionStart); ?>"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label for="payment_1yr" class="form-label fw-medium text-center d-block">First Payment:</label>
                        <input type="date" 
                               class="form-control form-control-custom"
                               id="payment_1yr" 
                               name="payment_1yr" 
                               value="<?php echo htmlspecialchars($fetchPayment1yr); ?>">
                    </div>
                </div>            
                <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                    <button type="submit" name="update" class="btn btn-primary px-4 py-2">
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

            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
                themeToggle.checked = true;
            }

            themeToggle.addEventListener('change', function() {
                if (this.checked) {
                    document.body.classList.add('dark-mode');
                    localStorage.setItem('darkMode', 'enabled');
                } else {
                    document.body.classList.remove('dark-mode');
                    localStorage.setItem('darkMode', 'disabled');
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