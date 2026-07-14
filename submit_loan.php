<?php
include 'connection.php';

if(isset($_POST['submit'])) {
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

    // Check if employee already exists
    $check_sql = "SELECT emp_id FROM tbl_employee WHERE emp_name = '$emp_name'";
    $check_result = mysqli_query($conn, $check_sql);

    if(mysqli_num_rows($check_result) > 0) {
        $row = mysqli_fetch_assoc($check_result);
        $emp_id = $row['emp_id'];
        
        // Update employee details
        $update_sql = "UPDATE tbl_employee SET 
                       SSS_ID = '$SSS_ID', 
                       pagibig_id = '$pagibig_id' 
                       WHERE emp_id = '$emp_id'";
        mysqli_query($conn, $update_sql);
    } else {
        // Insert new employee
        $insert_emp_sql = "INSERT INTO tbl_employee (emp_name, SSS_ID, pagibig_id) 
                           VALUES ('$emp_name', '$SSS_ID', '$pagibig_id')";
        mysqli_query($conn, $insert_emp_sql);
        $emp_id = mysqli_insert_id($conn);
    }

    // Insert loan record
    $sql = "INSERT INTO tbl_employee_loans (
        emp_id, dept, date_applied, loan_type, loan_amount, 
        total_deduction, deduction_start, payment_1yr, status
    ) VALUES (
        '$emp_id', '$dept', '$date_applied', '$loan_type', '$loan_amount',
        '$total_deduction', '$deduction_start', '$payment_1yr', '$status'
    )";

    if(mysqli_query($conn, $sql)) {
        // SUCCESS - New Loading Animation
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Success</title>
            <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap' rel='stylesheet'/>
            <style>
                * { 
                    font-family: 'Poppins', sans-serif; 
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    background: #f5f5f5;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                    padding: 20px;
                    transition: background-color 0.3s;
                }
                body.dark-mode {
                    background: #1a1a2e;
                }
                .loader-wrapper {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 40px;
                }
                .message-text {
                    font-size: 1.2rem;
                    font-weight: 500;
                    color: #2c3e50;
                    text-align: center;
                }
                body.dark-mode .message-text {
                    color: #e0e0e0;
                }
                .message-text .status-icon {
                    font-size: 3rem;
                    display: block;
                    margin-bottom: 10px;
                }
                .message-text .sub-text {
                    font-size: 0.9rem;
                    opacity: 0.7;
                    margin-top: 10px;
                }

                /* ===== NEW LOADER ANIMATION ===== */
                :root {
                    --bg: #1a1a2e;
                    --fg: #e0e0e0;
                    --primary1: #3498db;
                    --primary2: #2ecc71;
                    --trans-dur: 0.3s;
                }
                .pl {
                    box-shadow: 2em 0 2em rgba(0, 0, 0, 0.2) inset, -2em 0 2em rgba(255, 255, 255, 0.1) inset;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    position: relative;
                    letter-spacing: 0.1em;
                    text-transform: uppercase;
                    transform: rotateX(30deg) rotateZ(45deg);
                    width: 14em;
                    height: 14em;
                    color: white;
                }
                .pl, .pl__dot {
                    border-radius: 50%;
                }
                .pl__dot {
                    animation-name: shadow724;
                    box-shadow: 0.1em 0.1em 0 0.1em black, 0.3em 0 0.3em rgba(0, 0, 0, 0.5);
                    top: calc(50% - 0.75em);
                    left: calc(50% - 0.75em);
                    width: 1.5em;
                    height: 1.5em;
                }
                .pl__dot, .pl__dot:before, .pl__dot:after {
                    animation-duration: 2s;
                    animation-iteration-count: infinite;
                    position: absolute;
                }
                .pl__dot:before, .pl__dot:after {
                    content: '';
                    display: block;
                    left: 0;
                    width: inherit;
                    transition: background-color var(--trans-dur);
                }
                .pl__dot:before {
                    animation-name: pushInOut1724;
                    background-color: var(--bg);
                    border-radius: inherit;
                    box-shadow: 0.05em 0 0.1em rgba(255, 255, 255, 0.2) inset;
                    height: inherit;
                    z-index: 1;
                }
                .pl__dot:after {
                    animation-name: pushInOut2724;
                    background-color: var(--primary1);
                    border-radius: 0.75em;
                    box-shadow: 0.1em 0.3em 0.2em rgba(255, 255, 255, 0.4) inset, 0 -0.4em 0.2em #2e3138 inset, 0 -1em 0.25em rgba(0, 0, 0, 0.3) inset;
                    bottom: 0;
                    clip-path: polygon(0 75%, 100% 75%, 100% 100%, 0 100%);
                    height: 3em;
                    transform: rotate(-45deg);
                    transform-origin: 50% 2.25em;
                }

                .pl__dot:nth-child(1) { transform: rotate(0deg) translateX(5em) rotate(0deg); z-index: 5; }
                .pl__dot:nth-child(1), .pl__dot:nth-child(1):before, .pl__dot:nth-child(1):after { animation-delay: 0s; }
                .pl__dot:nth-child(2) { transform: rotate(-30deg) translateX(5em) rotate(30deg); z-index: 4; }
                .pl__dot:nth-child(2), .pl__dot:nth-child(2):before, .pl__dot:nth-child(2):after { animation-delay: -0.1666666667s; }
                .pl__dot:nth-child(3) { transform: rotate(-60deg) translateX(5em) rotate(60deg); z-index: 3; }
                .pl__dot:nth-child(3), .pl__dot:nth-child(3):before, .pl__dot:nth-child(3):after { animation-delay: -0.3333333333s; }
                .pl__dot:nth-child(4) { transform: rotate(-90deg) translateX(5em) rotate(90deg); z-index: 2; }
                .pl__dot:nth-child(4), .pl__dot:nth-child(4):before, .pl__dot:nth-child(4):after { animation-delay: -0.5s; }
                .pl__dot:nth-child(5) { transform: rotate(-120deg) translateX(5em) rotate(120deg); z-index: 1; }
                .pl__dot:nth-child(5), .pl__dot:nth-child(5):before, .pl__dot:nth-child(5):after { animation-delay: -0.6666666667s; }
                .pl__dot:nth-child(6) { transform: rotate(-150deg) translateX(5em) rotate(150deg); z-index: 1; }
                .pl__dot:nth-child(6), .pl__dot:nth-child(6):before, .pl__dot:nth-child(6):after { animation-delay: -0.8333333333s; }
                .pl__dot:nth-child(7) { transform: rotate(-180deg) translateX(5em) rotate(180deg); z-index: 2; }
                .pl__dot:nth-child(7), .pl__dot:nth-child(7):before, .pl__dot:nth-child(7):after { animation-delay: -1s; }
                .pl__dot:nth-child(8) { transform: rotate(-210deg) translateX(5em) rotate(210deg); z-index: 3; }
                .pl__dot:nth-child(8), .pl__dot:nth-child(8):before, .pl__dot:nth-child(8):after { animation-delay: -1.1666666667s; }
                .pl__dot:nth-child(9) { transform: rotate(-240deg) translateX(5em) rotate(240deg); z-index: 4; }
                .pl__dot:nth-child(9), .pl__dot:nth-child(9):before, .pl__dot:nth-child(9):after { animation-delay: -1.3333333333s; }
                .pl__dot:nth-child(10) { transform: rotate(-270deg) translateX(5em) rotate(270deg); z-index: 5; }
                .pl__dot:nth-child(10), .pl__dot:nth-child(10):before, .pl__dot:nth-child(10):after { animation-delay: -1.5s; }
                .pl__dot:nth-child(11) { transform: rotate(-300deg) translateX(5em) rotate(300deg); z-index: 6; }
                .pl__dot:nth-child(11), .pl__dot:nth-child(11):before, .pl__dot:nth-child(11):after { animation-delay: -1.6666666667s; }
                .pl__dot:nth-child(12) { transform: rotate(-330deg) translateX(5em) rotate(330deg); z-index: 6; }
                .pl__dot:nth-child(12), .pl__dot:nth-child(12):before, .pl__dot:nth-child(12):after { animation-delay: -1.8333333333s; }

                .pl__text {
                    font-size: 0.75em;
                    max-width: 5rem;
                    position: relative;
                    text-shadow: 0 0 0.1em var(--fg-t);
                    transform: rotateZ(-45deg);
                    color: #2c3e50;
                }
                body.dark-mode .pl__text {
                    color: #e0e0e0;
                }

                @keyframes shadow724 {
                    from {
                        animation-timing-function: ease-in;
                        box-shadow: 0.1em 0.1em 0 0.1em black, 0.3em 0 0.3em rgba(0, 0, 0, 0.3);
                    }
                    25% {
                        animation-timing-function: ease-out;
                        box-shadow: 0.1em 0.1em 0 0.1em black, 0.8em 0 0.8em rgba(0, 0, 0, 0.5);
                    }
                    50%, to {
                        box-shadow: 0.1em 0.1em 0 0.1em black, 0.3em 0 0.3em rgba(0, 0, 0, 0.3);
                    }
                }
                @keyframes pushInOut1724 {
                    from {
                        animation-timing-function: ease-in;
                        background-color: #1a1a2e;
                        transform: translate(0, 0);
                    }
                    25% {
                        animation-timing-function: ease-out;
                        background-color: #2ecc71;
                        transform: translate(-71%, -71%);
                    }
                    50%, to {
                        background-color: #1a1a2e;
                        transform: translate(0, 0);
                    }
                }
                @keyframes pushInOut2724 {
                    from {
                        animation-timing-function: ease-in;
                        background-color: #1a1a2e;
                        clip-path: polygon(0 75%, 100% 75%, 100% 100%, 0 100%);
                    }
                    25% {
                        animation-timing-function: ease-out;
                        background-color: #3498db;
                        clip-path: polygon(0 25%, 100% 25%, 100% 100%, 0 100%);
                    }
                    50%, to {
                        background-color: #1a1a2e;
                        clip-path: polygon(0 75%, 100% 75%, 100% 100%, 0 100%);
                    }
                }

                @media (max-width: 768px) {
                    .pl { width: 10em; height: 10em; }
                    .pl__dot { width: 1.2em; height: 1.2em; top: calc(50% - 0.6em); left: calc(50% - 0.6em); }
                    .pl__dot:after { height: 2.4em; }
                    .pl__dot:nth-child(1) { transform: rotate(0deg) translateX(3.5em) rotate(0deg); }
                    .pl__dot:nth-child(2) { transform: rotate(-30deg) translateX(3.5em) rotate(30deg); }
                    .pl__dot:nth-child(3) { transform: rotate(-60deg) translateX(3.5em) rotate(60deg); }
                    .pl__dot:nth-child(4) { transform: rotate(-90deg) translateX(3.5em) rotate(90deg); }
                    .pl__dot:nth-child(5) { transform: rotate(-120deg) translateX(3.5em) rotate(120deg); }
                    .pl__dot:nth-child(6) { transform: rotate(-150deg) translateX(3.5em) rotate(150deg); }
                    .pl__dot:nth-child(7) { transform: rotate(-180deg) translateX(3.5em) rotate(180deg); }
                    .pl__dot:nth-child(8) { transform: rotate(-210deg) translateX(3.5em) rotate(210deg); }
                    .pl__dot:nth-child(9) { transform: rotate(-240deg) translateX(3.5em) rotate(240deg); }
                    .pl__dot:nth-child(10) { transform: rotate(-270deg) translateX(3.5em) rotate(270deg); }
                    .pl__dot:nth-child(11) { transform: rotate(-300deg) translateX(3.5em) rotate(300deg); }
                    .pl__dot:nth-child(12) { transform: rotate(-330deg) translateX(3.5em) rotate(330deg); }
                }
                @media (max-width: 480px) {
                    .pl { width: 8em; height: 8em; }
                    .pl__dot { width: 1em; height: 1em; top: calc(50% - 0.5em); left: calc(50% - 0.5em); }
                    .pl__dot:after { height: 2em; }
                    .pl__dot:nth-child(1) { transform: rotate(0deg) translateX(2.8em) rotate(0deg); }
                    .pl__dot:nth-child(2) { transform: rotate(-30deg) translateX(2.8em) rotate(30deg); }
                    .pl__dot:nth-child(3) { transform: rotate(-60deg) translateX(2.8em) rotate(60deg); }
                    .pl__dot:nth-child(4) { transform: rotate(-90deg) translateX(2.8em) rotate(90deg); }
                    .pl__dot:nth-child(5) { transform: rotate(-120deg) translateX(2.8em) rotate(120deg); }
                    .pl__dot:nth-child(6) { transform: rotate(-150deg) translateX(2.8em) rotate(150deg); }
                    .pl__dot:nth-child(7) { transform: rotate(-180deg) translateX(2.8em) rotate(180deg); }
                    .pl__dot:nth-child(8) { transform: rotate(-210deg) translateX(2.8em) rotate(210deg); }
                    .pl__dot:nth-child(9) { transform: rotate(-240deg) translateX(2.8em) rotate(240deg); }
                    .pl__dot:nth-child(10) { transform: rotate(-270deg) translateX(2.8em) rotate(270deg); }
                    .pl__dot:nth-child(11) { transform: rotate(-300deg) translateX(2.8em) rotate(300deg); }
                    .pl__dot:nth-child(12) { transform: rotate(-330deg) translateX(2.8em) rotate(330deg); }
                    .pl__text { font-size: 0.6em; }
                }
            </style>
        </head>
        <body>
            <div class='loader-wrapper'>
                <div class='pl'>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__text'>Loading…</div>
                </div>
                <div class='message-text'>
                    <span class='status-icon'>✅</span>
                    Employee loan record added successfully!
                    <div class='sub-text'>Redirecting to dashboard...</div>
                </div>
            </div>
            <script>
                // Check for dark mode
                if (localStorage.getItem('darkMode') === 'enabled') {
                    document.body.classList.add('dark-mode');
                }
                
                // Auto redirect after 2.5 seconds
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 2500);
            </script>
        </body>
        </html>";
        exit();
    } else {
        // ERROR - New Loading Animation
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Error</title>
            <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap' rel='stylesheet'/>
            <style>
                * { 
                    font-family: 'Poppins', sans-serif; 
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    background: #f5f5f5;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                    padding: 20px;
                    transition: background-color 0.3s;
                }
                body.dark-mode {
                    background: #1a1a2e;
                }
                .loader-wrapper {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 40px;
                }
                .message-text {
                    font-size: 1.2rem;
                    font-weight: 500;
                    color: #e74c3c;
                    text-align: center;
                }
                body.dark-mode .message-text {
                    color: #ff6b6b;
                }
                .message-text .status-icon {
                    font-size: 3rem;
                    display: block;
                    margin-bottom: 10px;
                }
                .message-text .sub-text {
                    font-size: 0.9rem;
                    opacity: 0.7;
                    margin-top: 10px;
                }
                .message-text .error-msg {
                    font-size: 0.8rem;
                    opacity: 0.6;
                    margin-top: 5px;
                    word-break: break-all;
                }

                /* ===== NEW LOADER ANIMATION ===== */
                :root {
                    --bg: #1a1a2e;
                    --fg: #e0e0e0;
                    --primary1: #3498db;
                    --primary2: #e74c3c;
                    --trans-dur: 0.3s;
                }
                .pl {
                    box-shadow: 2em 0 2em rgba(0, 0, 0, 0.2) inset, -2em 0 2em rgba(255, 255, 255, 0.1) inset;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    position: relative;
                    letter-spacing: 0.1em;
                    text-transform: uppercase;
                    transform: rotateX(30deg) rotateZ(45deg);
                    width: 14em;
                    height: 14em;
                    color: white;
                }
                .pl, .pl__dot {
                    border-radius: 50%;
                }
                .pl__dot {
                    animation-name: shadow724;
                    box-shadow: 0.1em 0.1em 0 0.1em black, 0.3em 0 0.3em rgba(0, 0, 0, 0.5);
                    top: calc(50% - 0.75em);
                    left: calc(50% - 0.75em);
                    width: 1.5em;
                    height: 1.5em;
                }
                .pl__dot, .pl__dot:before, .pl__dot:after {
                    animation-duration: 2s;
                    animation-iteration-count: infinite;
                    position: absolute;
                }
                .pl__dot:before, .pl__dot:after {
                    content: '';
                    display: block;
                    left: 0;
                    width: inherit;
                    transition: background-color var(--trans-dur);
                }
                .pl__dot:before {
                    animation-name: pushInOut1724_error;
                    background-color: var(--bg);
                    border-radius: inherit;
                    box-shadow: 0.05em 0 0.1em rgba(255, 255, 255, 0.2) inset;
                    height: inherit;
                    z-index: 1;
                }
                .pl__dot:after {
                    animation-name: pushInOut2724_error;
                    background-color: var(--primary1);
                    border-radius: 0.75em;
                    box-shadow: 0.1em 0.3em 0.2em rgba(255, 255, 255, 0.4) inset, 0 -0.4em 0.2em #2e3138 inset, 0 -1em 0.25em rgba(0, 0, 0, 0.3) inset;
                    bottom: 0;
                    clip-path: polygon(0 75%, 100% 75%, 100% 100%, 0 100%);
                    height: 3em;
                    transform: rotate(-45deg);
                    transform-origin: 50% 2.25em;
                }

                .pl__dot:nth-child(1) { transform: rotate(0deg) translateX(5em) rotate(0deg); z-index: 5; }
                .pl__dot:nth-child(1), .pl__dot:nth-child(1):before, .pl__dot:nth-child(1):after { animation-delay: 0s; }
                .pl__dot:nth-child(2) { transform: rotate(-30deg) translateX(5em) rotate(30deg); z-index: 4; }
                .pl__dot:nth-child(2), .pl__dot:nth-child(2):before, .pl__dot:nth-child(2):after { animation-delay: -0.1666666667s; }
                .pl__dot:nth-child(3) { transform: rotate(-60deg) translateX(5em) rotate(60deg); z-index: 3; }
                .pl__dot:nth-child(3), .pl__dot:nth-child(3):before, .pl__dot:nth-child(3):after { animation-delay: -0.3333333333s; }
                .pl__dot:nth-child(4) { transform: rotate(-90deg) translateX(5em) rotate(90deg); z-index: 2; }
                .pl__dot:nth-child(4), .pl__dot:nth-child(4):before, .pl__dot:nth-child(4):after { animation-delay: -0.5s; }
                .pl__dot:nth-child(5) { transform: rotate(-120deg) translateX(5em) rotate(120deg); z-index: 1; }
                .pl__dot:nth-child(5), .pl__dot:nth-child(5):before, .pl__dot:nth-child(5):after { animation-delay: -0.6666666667s; }
                .pl__dot:nth-child(6) { transform: rotate(-150deg) translateX(5em) rotate(150deg); z-index: 1; }
                .pl__dot:nth-child(6), .pl__dot:nth-child(6):before, .pl__dot:nth-child(6):after { animation-delay: -0.8333333333s; }
                .pl__dot:nth-child(7) { transform: rotate(-180deg) translateX(5em) rotate(180deg); z-index: 2; }
                .pl__dot:nth-child(7), .pl__dot:nth-child(7):before, .pl__dot:nth-child(7):after { animation-delay: -1s; }
                .pl__dot:nth-child(8) { transform: rotate(-210deg) translateX(5em) rotate(210deg); z-index: 3; }
                .pl__dot:nth-child(8), .pl__dot:nth-child(8):before, .pl__dot:nth-child(8):after { animation-delay: -1.1666666667s; }
                .pl__dot:nth-child(9) { transform: rotate(-240deg) translateX(5em) rotate(240deg); z-index: 4; }
                .pl__dot:nth-child(9), .pl__dot:nth-child(9):before, .pl__dot:nth-child(9):after { animation-delay: -1.3333333333s; }
                .pl__dot:nth-child(10) { transform: rotate(-270deg) translateX(5em) rotate(270deg); z-index: 5; }
                .pl__dot:nth-child(10), .pl__dot:nth-child(10):before, .pl__dot:nth-child(10):after { animation-delay: -1.5s; }
                .pl__dot:nth-child(11) { transform: rotate(-300deg) translateX(5em) rotate(300deg); z-index: 6; }
                .pl__dot:nth-child(11), .pl__dot:nth-child(11):before, .pl__dot:nth-child(11):after { animation-delay: -1.6666666667s; }
                .pl__dot:nth-child(12) { transform: rotate(-330deg) translateX(5em) rotate(330deg); z-index: 6; }
                .pl__dot:nth-child(12), .pl__dot:nth-child(12):before, .pl__dot:nth-child(12):after { animation-delay: -1.8333333333s; }

                .pl__text {
                    font-size: 0.75em;
                    max-width: 5rem;
                    position: relative;
                    text-shadow: 0 0 0.1em var(--fg-t);
                    transform: rotateZ(-45deg);
                    color: #2c3e50;
                }
                body.dark-mode .pl__text {
                    color: #e0e0e0;
                }

                @keyframes shadow724 {
                    from {
                        animation-timing-function: ease-in;
                        box-shadow: 0.1em 0.1em 0 0.1em black, 0.3em 0 0.3em rgba(0, 0, 0, 0.3);
                    }
                    25% {
                        animation-timing-function: ease-out;
                        box-shadow: 0.1em 0.1em 0 0.1em black, 0.8em 0 0.8em rgba(0, 0, 0, 0.5);
                    }
                    50%, to {
                        box-shadow: 0.1em 0.1em 0 0.1em black, 0.3em 0 0.3em rgba(0, 0, 0, 0.3);
                    }
                }
                @keyframes pushInOut1724_error {
                    from {
                        animation-timing-function: ease-in;
                        background-color: #1a1a2e;
                        transform: translate(0, 0);
                    }
                    25% {
                        animation-timing-function: ease-out;
                        background-color: #e74c3c;
                        transform: translate(-71%, -71%);
                    }
                    50%, to {
                        background-color: #1a1a2e;
                        transform: translate(0, 0);
                    }
                }
                @keyframes pushInOut2724_error {
                    from {
                        animation-timing-function: ease-in;
                        background-color: #1a1a2e;
                        clip-path: polygon(0 75%, 100% 75%, 100% 100%, 0 100%);
                    }
                    25% {
                        animation-timing-function: ease-out;
                        background-color: #e74c3c;
                        clip-path: polygon(0 25%, 100% 25%, 100% 100%, 0 100%);
                    }
                    50%, to {
                        background-color: #1a1a2e;
                        clip-path: polygon(0 75%, 100% 75%, 100% 100%, 0 100%);
                    }
                }

                @media (max-width: 768px) {
                    .pl { width: 10em; height: 10em; }
                    .pl__dot { width: 1.2em; height: 1.2em; top: calc(50% - 0.6em); left: calc(50% - 0.6em); }
                    .pl__dot:after { height: 2.4em; }
                    .pl__dot:nth-child(1) { transform: rotate(0deg) translateX(3.5em) rotate(0deg); }
                    .pl__dot:nth-child(2) { transform: rotate(-30deg) translateX(3.5em) rotate(30deg); }
                    .pl__dot:nth-child(3) { transform: rotate(-60deg) translateX(3.5em) rotate(60deg); }
                    .pl__dot:nth-child(4) { transform: rotate(-90deg) translateX(3.5em) rotate(90deg); }
                    .pl__dot:nth-child(5) { transform: rotate(-120deg) translateX(3.5em) rotate(120deg); }
                    .pl__dot:nth-child(6) { transform: rotate(-150deg) translateX(3.5em) rotate(150deg); }
                    .pl__dot:nth-child(7) { transform: rotate(-180deg) translateX(3.5em) rotate(180deg); }
                    .pl__dot:nth-child(8) { transform: rotate(-210deg) translateX(3.5em) rotate(210deg); }
                    .pl__dot:nth-child(9) { transform: rotate(-240deg) translateX(3.5em) rotate(240deg); }
                    .pl__dot:nth-child(10) { transform: rotate(-270deg) translateX(3.5em) rotate(270deg); }
                    .pl__dot:nth-child(11) { transform: rotate(-300deg) translateX(3.5em) rotate(300deg); }
                    .pl__dot:nth-child(12) { transform: rotate(-330deg) translateX(3.5em) rotate(330deg); }
                }
                @media (max-width: 480px) {
                    .pl { width: 8em; height: 8em; }
                    .pl__dot { width: 1em; height: 1em; top: calc(50% - 0.5em); left: calc(50% - 0.5em); }
                    .pl__dot:after { height: 2em; }
                    .pl__dot:nth-child(1) { transform: rotate(0deg) translateX(2.8em) rotate(0deg); }
                    .pl__dot:nth-child(2) { transform: rotate(-30deg) translateX(2.8em) rotate(30deg); }
                    .pl__dot:nth-child(3) { transform: rotate(-60deg) translateX(2.8em) rotate(60deg); }
                    .pl__dot:nth-child(4) { transform: rotate(-90deg) translateX(2.8em) rotate(90deg); }
                    .pl__dot:nth-child(5) { transform: rotate(-120deg) translateX(2.8em) rotate(120deg); }
                    .pl__dot:nth-child(6) { transform: rotate(-150deg) translateX(2.8em) rotate(150deg); }
                    .pl__dot:nth-child(7) { transform: rotate(-180deg) translateX(2.8em) rotate(180deg); }
                    .pl__dot:nth-child(8) { transform: rotate(-210deg) translateX(2.8em) rotate(210deg); }
                    .pl__dot:nth-child(9) { transform: rotate(-240deg) translateX(2.8em) rotate(240deg); }
                    .pl__dot:nth-child(10) { transform: rotate(-270deg) translateX(2.8em) rotate(270deg); }
                    .pl__dot:nth-child(11) { transform: rotate(-300deg) translateX(2.8em) rotate(300deg); }
                    .pl__dot:nth-child(12) { transform: rotate(-330deg) translateX(2.8em) rotate(330deg); }
                    .pl__text { font-size: 0.6em; }
                }
            </style>
        </head>
        <body>
            <div class='loader-wrapper'>
                <div class='pl'>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__dot'></div>
                    <div class='pl__text'>Error</div>
                </div>
                <div class='message-text'>
                    <span class='status-icon'>❌</span>
                    Error adding loan record!
                    <div class='error-msg'>" . mysqli_error($conn) . "</div>
                    <div class='sub-text'>Redirecting back...</div>
                </div>
            </div>
            <script>
                if (localStorage.getItem('darkMode') === 'enabled') {
                    document.body.classList.add('dark-mode');
                }
                
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 4000);
            </script>
        </body>
        </html>";
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
?>