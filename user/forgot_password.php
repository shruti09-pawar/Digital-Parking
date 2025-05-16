<?php
require '../connection.php'; // Include your database connection file

$message = ""; // Initialize the message

if (isset($_POST['email'])) {
    // Sanitize and validate the email address
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Check if the email exists in the database
        $stmt = $con->prepare("SELECT user_id FROM users WHERE email = ?");
        if (!$stmt) {
            $message = "Database error.";
        } else {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 0) {
                $message = "Email not found.";
            } else {
                // Generate a unique token
                $token = bin2hex(random_bytes(50));

                // Update the token in the database (consider also adding an expiry time)
                $stmt = $con->prepare("UPDATE users SET token = ? WHERE email = ?");
                if (!$stmt) {
                    $message = "Database error.";
                } else {
                    $stmt->bind_param('ss', $token, $email);
                    if ($stmt->execute()) {
                        // Send reset link email
                        $resetLink = "http://localhost/digital_parking/user/reset_password.php?token=" . $token; // Replace with actual domain
                        
                        $subject = "Password Reset";
                        $messageBody = "Click this link to reset your password: " . $resetLink . "\n\n";
                        $messageBody .= "If you did not request a password reset, please ignore this email.";
                        
                        // Headers
                        $headers = "From: shrupawars21gmail.com\r\n";
                        
                        // Send email
                        if (mail($email, $subject, $messageBody, $headers)) {
                            $message = "Reset link sent!";
                        } else {
                            $message = "Failed to send reset link.";
                        }
                    } else {
                        $message = "Failed to update token.";
                    }
                }
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Digital Parking System</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #f1c40f;
            --background-color: #ecf0f1;
            --text-color: #2d3436;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('../parking1.jpg') no-repeat center center fixed;
            background-size: cover;
            color: var(--text-color);
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3); /* Dark overlay for readability */
            z-index: -1;
        }

        .container {
            margin-top: 150px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeIn 1s ease-in-out;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .card-body {
            background-color: #fff;
            padding: 40px;
        }

        .form-control {
            border-radius: 50px;
            padding: 15px;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--primary-color);
            color: #fff;
        }

        .card-footer {
            background-color: var(--background-color);
            text-align: center;
            padding: 20px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .container {
                margin-top: 50px;
            }

            .card-header h4 {
                font-size: 1.8rem;
            }
        }

        .navbar {
            background-color: var(--primary-color);
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .navbar-toggler-icon {
            background-color: var(--secondary-color);
        }

        /* Error & Success Message Styles */
        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="#">Digital Parking System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Home</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="overlay"></div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Forgot Password</h4>
                    </div>
                    <div class="card-body">
                        <form action="forgot_password.php" method="post">
                            <div class="form-group">
                                <label for="email">Enter your email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
                        </form>
                        <?php if (!empty($message)): ?>
                            <div class="message <?php echo strpos($message, 'sent') !== false ? 'success' : 'error'; ?>">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <p><a href="user_login.php">Back to Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
