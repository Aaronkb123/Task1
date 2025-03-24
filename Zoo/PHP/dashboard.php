<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header(header: "Location:form_login.php");
    exit();
}
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riget Zoo Adventures</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/access_styles.css">
    <script src="../javascript/as.js"></script>
    <script src="../javascript/asopenclose.js"></script> <!--Controlling the open and close of the accessibility settings-->
    <script src="../javascript/initialisation.js"></script>
</head>
<body>

    <header>
        <div class="logo">
            <a href="index.php"><img src="../Images/RGZ_logo.svg" alt="Riget Zoo Adventures Logo"></a>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="animals.php">Animals</a></li>
                <li><a href="visit.php">Visit</a></li>
                <li><a href="events.php">Events</a></li>
                <!-- <li><a href="PHP/form_login.php" class="button" id="login-button">Login/Register</a></li> -->
                </ul>

                <ul class="nav-right">
                    <!-- Login/Register Button (Visible when logged out) -->
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li><a href="PHP/form_login.php" class="button" id="login-button">Login/Register</a></li>
                    <?php else: ?>
                    <!-- Logout Button (Visible when logged in) -->
                        <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <li><a href="logout.php" class="button id="logout-button>Logout</a></li>
                    <?php endif; ?>
                </ul>
           <!-- <div class="nav-right" id=logout-button>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" id="logout-button">Logout</a>
            </div> -->
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

    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to your dashboard!</h1>
            <p>Experience a unique safari adventure!</p>
            <a href="visit.html" class="button">Plan Your Visit</a>
        </div>
    </section>

    <section class="conservation-message">
        <h2>Our Commitment to Conservation</h2>
        <p>Riget Zoo Adventures is dedicated to protecting endangered species and their habitats.  Learn how you can help!</p>
        <a href="conservation.html" class="button secondary">Learn More</a>
    </section>

    <footer>
        <p>© 2025 Riget Zoo Adventures | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </footer>

    <script>
        document.querySelector('.hamburger').addEventListener('click', function() {
            document.querySelector('nav').classList.toggle('active');
        });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var isLoggedIn = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;
        
        if (isLoggedIn) {
            // Show logout button, hide login button
            document.getElementById('login-button').style.display = 'none';
            document.getElementById('logout-button').style.display = 'inline-block';
        } else {
            // Show login button, hide logout button
            document.getElementById('login-button').style.display = 'inline-block';
            document.getElementById('logout-button').style.display = 'none';
        }
    });
    </script>


</body>
</html>