<?php
include 'connection.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $checkQuery = "SELECT * FROM tbl_users WHERE username='$username'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            $error = "Username already taken!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Only insert username and password as per your DB structure
            $insertQuery = "INSERT INTO tbl_users (username, pass) VALUES ('$username', '$hashed_password')";
            if (mysqli_query($conn, $insertQuery)) {
                $success = "Signup successful! You can now <a href='login.php'>login</a>.";
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <link rel="icon" type="icon" href="student.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animated Login & Signup Forms</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .container {
            position: relative;
            width: 100%;
            max-width: 850px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            margin: 20px;
        }

        .forms-container {
            display: flex;
            transition: transform 0.6s ease-in-out;
        }

        .form-wrapper {
            min-width: 100%;
            padding: 40px;
            transition: opacity 0.5s ease;
        }

        .form-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #333;
            text-align: center;
            position: relative;
        }

        .form-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: #4a90e2;
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s forwards;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            transition: color 0.3s;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
            background: #f9f9f9;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4a90e2;
            background: white;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
        }

        .form-group input:valid {
            border-color: #4CAF50;
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #4a90e2, #3a7bc8);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s 0.6s forwards;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 144, 226, 0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        .btn:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }

        .form-footer {
            text-align: center;
            margin-top: 25px;
            color: #666;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s 0.7s forwards;
        }

        .form-footer a {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            position: relative;
        }

        .form-footer a:hover {
            color: #3a7bc8;
        }

        .form-footer a:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #4a90e2;
            transition: width 0.3s;
        }

        .form-footer a:hover:after {
            width: 100%;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            padding: 12px;
            border-radius: 8px;
            opacity: 0;
            transform: translateY(-10px);
            animation: slideDown 0.5s forwards;
        }

        @keyframes slideDown {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.error {
            color: #d32f2f;
            background: rgba(211, 47, 47, 0.1);
            border-left: 4px solid #d32f2f;
        }

        .message.success {
            color: #388e3c;
            background: rgba(56, 142, 60, 0.1);
            border-left: 4px solid #388e3c;
        }

        .password-strength {
            margin-top: 5px;
            height: 5px;
            border-radius: 5px;
            background: #e0e0e0;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .weak { background: #f44336; width: 33%; }
        .medium { background: #ff9800; width: 66%; }
        .strong { background: #4CAF50; width: 100%; }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 42px;
            color: #999;
            transition: color 0.3s;
        }

        .form-group input:focus + .input-icon {
            color: #4a90e2;
        }

        /* Floating animation for the container */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .container {
            animation: float 6s ease-in-out infinite;
        }

        /* Particle background */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            animation: float-particle 15s infinite linear;
        }

        @keyframes float-particle {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Particle Background -->
    <div class="particles" id="particles"></div>

    <div class="container">
        <div class="forms-container">
            <!-- Signup Form -->
            <div class="form-wrapper" id="signup-form">
                <form action="signup.php" method="post" id="signupForm">
                    <div class="form-title">Create Account</div>

                    <div id="signup-message"></div>

                    <div class="form-group">
                        <label for="signup-name">Full Name</label>
                        <input type="text" id="signup-name" name="fullname" placeholder="Enter your full name" required>
                        <span class="input-icon">👤</span>
                    </div>
                    <div class="form-group">
                        <label for="signup-username">Username</label>
                        <input type="text" id="signup-username" name="username" placeholder="Choose a username" required>
                        <span class="input-icon">🔑</span>
                    </div>
                    <div class="form-group">
                        <label for="signup-email">Email</label>
                        <input type="email" id="signup-email" name="email" placeholder="Enter your email" required>
                        <span class="input-icon">✉️</span>
                    </div>
                    <div class="form-group">
                        <label for="signup-password">Password</label>
                        <input type="password" id="signup-password" name="password" placeholder="Create a password" required>
                        <span class="input-icon">🔒</span>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="password-strength-bar"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="signup-confirm-password">Confirm Password</label>
                        <input type="password" id="signup-confirm-password" name="confirm_password" placeholder="Confirm your password" required>
                        <span class="input-icon">🔒</span>
                    </div>
                    <button type="submit" class="btn" id="signup-btn">Sign Up</button>
                    <div class="form-footer">
                        Already have an account? <a href="login.php">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Create particle background
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random properties
                const size = Math.random() * 20 + 5;
                const left = Math.random() * 100;
                const animationDuration = Math.random() * 20 + 10;
                const animationDelay = Math.random() * 5;
                
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${left}%`;
                particle.style.animationDuration = `${animationDuration}s`;
                particle.style.animationDelay = `${animationDelay}s`;
                
                particlesContainer.appendChild(particle);
            }
        }

        // Password strength indicator
        document.getElementById('signup-password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('password-strength-bar');
            
            // Reset classes
            strengthBar.className = 'password-strength-bar';
            
            if (password.length === 0) {
                strengthBar.style.width = '0';
                return;
            }
            
            // Calculate strength
            let strength = 0;
            if (password.length >= 6) strength += 1;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 1;
            if (password.match(/\d/)) strength += 1;
            if (password.match(/[^a-zA-Z\d]/)) strength += 1;
            
            // Update strength bar
            if (strength <= 1) {
                strengthBar.classList.add('weak');
            } else if (strength <= 2) {
                strengthBar.classList.add('medium');
            } else {
                strengthBar.classList.add('strong');
            }
        });

        // Form validation with animation
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            const password = document.getElementById('signup-password').value;
            const confirmPassword = document.getElementById('signup-confirm-password').value;
            const messageDiv = document.getElementById('signup-message');
            
            // Clear previous message
            messageDiv.innerHTML = '';
            messageDiv.className = 'message';
            
            if (password !== confirmPassword) {
                e.preventDefault();
                messageDiv.textContent = 'Passwords do not match!';
                messageDiv.classList.add('error');
                
                // Shake animation for password fields
                const passwordFields = [document.getElementById('signup-password'), document.getElementById('signup-confirm-password')];
                passwordFields.forEach(field => {
                    field.style.borderColor = '#f44336';
                    field.classList.add('shake');
                    setTimeout(() => {
                        field.classList.remove('shake');
                    }, 500);
                });
                
                return false;
            }
            
            // If validation passes, show success message
            messageDiv.textContent = 'Signup successful! Redirecting...';
            messageDiv.classList.add('success');
        });

        // Add shake animation
        const style = document.createElement('style');
        style.textContent = `
            .shake {
                animation: shake 0.5s;
            }
            
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
        `;
        document.head.appendChild(style);

        // Initialize
        createParticles();
    </script>
</body>
</html>