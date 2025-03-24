<?php
session_start();  // Start the session FIRST!
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animals at Riget Zoo Adventures</title>
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
            </ul>
            <ul class="nav-right">
                    <!-- Login/Register Button (Visible when logged out) -->
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li><a href="form_login.php" class="button" id="login-button">Login/Register</a></li>
                    <?php else: ?>
                    <!-- Logout Button (Visible when logged in) -->
                        <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <li><a href="dashboard.php" class="button id="dashboard-button>Dashboard</a></li>
                        <li><a href="logout.php" class="button id="logout-button>Logout</a></li>
                    <?php endif; ?>
                </ul>
        </nav>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>

    <body>
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
    </body>

    <main>
        <section class="about-hero">  <!-- Reusing the hero section -->
            <div class="hero-content">
                <h1>Our Amazing Animals</h1>
                <p>Discover the incredible wildlife that calls Riget Zoo Adventures home.  From majestic lions to playful primates, we offer a unique opportunity to connect with nature.</p>
            </div>
        </section>

        <section>
            <h2>African Savanna</h2>
            <div class="animal-grid">
                <div class="animal-card">
                    <img src="../Images/lion.jpg" alt="Lion">
                    <h3>African Lion</h3>
                    <p>King of the savanna, our lions are a sight to behold.  Learn about their social structure and hunting habits.</p>
                </div>
                <div class="animal-card">
                    <img src="../Images/giraffe.jpg" alt="Giraffe">
                    <h3>Giraffe</h3>
                    <p>These gentle giants roam our savanna exhibit.  Did you know their spots are unique like fingerprints?</p>
                </div>
                <div class="animal-card">
                    <img src="../Images/zebra.jpg" alt="Zebra">
                    <h3>Zebra</h3>
                    <p>A large family of these iconic creatures, the stripes are used to confuse preditors.</p>
                </div>
                <div class="animal-card">
                    <img src="../Images/elephant.jpg" alt="Elephant">
                    <h3>African Elephant</h3>
                    <p>These giants will capture the hearts of anyone!</p>
                </div>
            </div>
        </section>

        <section>
            <h2>Asian Rainforest</h2>
            <div class="animal-grid">
                <div class="animal-card">
                    <img src="../Images/tiger.jpg" alt="Tiger">
                    <h3>Bengal Tiger</h3>
                    <p>Observe these magnificent tigers, masters of camouflage and stealth. </p>
                </div>
                <div class="animal-card">
                    <img src="../Images/orangutan.jpg" alt="Orangutan">
                    <h3>Orangutan</h3>
                    <p>Our orangutans are highly intelligent and playful.  Learn about their arboreal lifestyle.</p>
                </div>
                <div class="animal-card">
                    <img src="../Images/red_panda.jpg" alt="Red Panda">
                    <h3>Red Panda</h3>
                    <p>A family favourite, the small bears are always up to mischief!</p>
                </div>
            </div>
        </section>

        <section>
            <h2>South American Jungle</h2>
            <div class="animal-grid">
                <div class="animal-card">
                    <img src="../Images/jaguar.jpg" alt="Jaguar">
                    <h3>Jaguar</h3>
                    <p>These striking creatures prowl our jungle exhibit, masters of ambushing.</p>
                </div>
                <div class="animal-card">
                    <img src="../Images/macaw.jpg" alt="Macaw">
                    <h3>Macaw</h3>
                    <p>The vibrant colours of these creatures are one of the many highlights of the park!</p>
                </div>
                <div class="animal-card">
                    <img src="../Images/anaconda.jpg" alt="Anaconda">
                    <h3>Anaconda</h3>
                    <p>These reptiles will certainly shock visitors. </p>
                </div>
            </div>
        </section>
    </main>

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