<?php
session_start();
include('../connection.php');

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $uname_search = "Select * from admins where username='$username' and password_hash='$password'";
    $query = mysqli_query($con, $uname_search);

    $uname_count = mysqli_num_rows($query);

    if($uname_count == 1 ){
        $_SESSION['username'] = $username;
        $_SESSION['password_hash'] = $password;
        header('Location: admin_dashboard.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Digital Parking System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50; 
            --secondary-color: #f1c40f; 
            --background-color: #ecf0f1;
            --text-color: #2d3436;
        }

        body {
            background-color: var(--background-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('../parking1.jpg') no-repeat center center fixed;
            background-size: cover;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Admin Login</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button name="submit" type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
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