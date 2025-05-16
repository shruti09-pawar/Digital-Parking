<?php
session_start();
require '../connection.php';

if (isset($_POST['submit'])) {
$phone = $_POST['phone'];
$password = $_POST['password'];

$query = "SELECT * FROM Users WHERE phone=? LIMIT 1";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password_hash'])) {
$_SESSION['user_id'] = $user['user_id'];
header("Location: user_dashboard.php");
} else {
        echo '<div class="alert alert-danger">Invalid mobile number or password</div>';
       }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - Digital Parking System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
            background: rgba(0, 0, 0, 0.3); 
            z-index: -1;
        }

        .container {
            margin-top: 100px;
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

        .card-footer a {
            color: var(--primary-color);
            font-weight: bold;
            text-decoration: none;
        }

        .card-footer a:hover {
            color: var(--secondary-color);
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
                        <h4>User Login</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="phone">Mobile Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button name="submit" type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <a href="register.php">New user? Register here</a>
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
