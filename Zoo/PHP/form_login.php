<?php
session_start();

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register - Riget Zoo Adventures</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/form_styles_login.css">
    <script src="../javascript/as.js"></script>
    <script src="../javascript/asopenclose.js"></script> <!--Controlling the open and close of the accessibility settings-->
    <script src="../javascript/initialisation.js"></script>
    <link rel="stylesheet" href="../CSS/access_styles.css">
</head>
<body>

    <header>
        <div class="logo">
            <a href="../index.html"><img src="../Images/RGZ_logo.svg" alt="Riget Zoo Adventures Logo"></a>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="animals.php">Animals</a></li>
                <li><a href="visit.php">Visit</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="form_ref.php" class="button">Login/Register</a></li>
            </ul>
        </nav>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>

    <div id="accessibility" class="accessibility">
        <a href="javascript:void(0)" class="closebtn" onclick="asClose()">Close</a>
        <a onclick="increaseTextSize()">Increase font size</a>
        <a onclick="decreaseTextSize()">Decrease font size</a>
        <a onclick="changeArial()">Readable font</a>
        <a onclick="underlineLinks()">Underline links</a>
        <a onclick="hContrast()">High contrast</a>
        <a onclick="asReset()">Reset</a>
    </div>
    <div id="accessibility_main">
        <button class="accessibility_btn" onclick="asOpen()">Accessibility</button>
    </div>

    <main class="login-container">
        <div class="form-container">
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" placeholder="username" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" placeholder="password" id="password" name="password" required>
                </div>
                <button type="submit" class="button">Login</button>
            </form>

            <p class="register-link">
                Don't have an account? <a href="form_register.php">Register here</a>
            </p>
        </div>
    </main>

    <footer>
        <p>© 2025 Riget Zoo Adventures | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </footer>

    <script>
        document.querySelector('.hamburger').addEventListener('click', function() {
            document.querySelector('nav').classList.toggle('active');
        });
    </script>

</body>
</html>