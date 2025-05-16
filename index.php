<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Digital Parking System - Smart, efficient, and hassle-free parking solutions at your fingertips.">
    <meta name="keywords" content="Digital Parking, Parking System, Smart Parking, Online Parking Solutions">
    <meta name="author" content="Digital Parking System Team">
    <title>Digital Parking System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #f1c40f;
            --background-color: #ecf0f1;
            --text-color: #2d3436;
        }

        
        .hero-section {
            background: url('parking5.jpg') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-align: center;
            background-color: var(--primary-color);
        }
        .hero-section h1 {
            font-weight: bold;
            font-size: 4rem;
        }
        .hero-section p {
            font-size: 1.3rem;
        }
        .hero-section .btn {
            background-color: var(--secondary-color);
            border: none;
            color: var(--primary-color);
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .navbar {
            background-color: var(--primary-color);
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .navbar-nav .nav-link.active {
            font-weight: bold; 
            color: #fff; 
        }
        .navbar-toggler-icon {
            background-color: var(--secondary-color);
        }

        /* About Section Styling */
        .about-section {
            padding: 80px 0;
            background-color: var(--background-color);
            color: var(--text-color);
        }
        .about-section h2 {
            font-weight: bold;
            margin-bottom: 30px;
        }
        .about-section p {
            font-size: 1.2rem;
            line-height: 1.9;
        }
        .about-section .features i {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }


        footer {
            background-color: var(--primary-color);
            color: #fff;
            padding: 30px 0;
        }
        footer p {
            margin: 0;
        }
        footer a {
            color: var(--secondary-color);
            text-decoration: none;
            margin: 0 10px;
        }

  
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 3rem;
            }
            .hero-section .btn {
                font-size: 1rem;
                padding: 12px 25px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="#">Digital Parking System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user/user_login.php">User Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin/admin_login.php">Admin</a>
                </li>
            </ul>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
        <h1>Digital Parking Can Save Your Money, Time, and the Planet!</h1>
            <a href="#about" class="btn">Learn More</a>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="about-section" id="about">
        <div class="container">
            <h2 class="text-center">About Digital Parking System</h2>
            <p class="text-center">Our Digital Parking System offers a smart solution to make parking in your locality easy and hassle-free. Find, book, and pay for parking slots at our single location with real-time availability and secure payments.</p>
            
            <div class="row text-center features">
                <div class="col-md-4">
                    <i class="fas fa-car"></i>
                    <h4>Easy Parking</h4>
                    <p>Quickly find available parking spots and reserve them in seconds, ensuring a hassle-free experience.</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-credit-card"></i>
                    <h4>Secure Payments</h4>
                    <p>Make safe and secure payments using our integrated payment system.</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-map-marker-alt"></i>
                    <h4>Ticketless Access</h4>
                    <p>Our PlugNPlay solutions automate your existing equipment to provide seamless access.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center text-white">
        <p>&copy; 2025 Digital Parking System. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" defer></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>
    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
