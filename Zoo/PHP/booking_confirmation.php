<?php
session_start();
include_once('db_connection.php');

// Check if the user is logged in (you might need to adjust this based on your auth system)
if (!isset($_SESSION['user_id'])) {
    header("Location: form_login.php"); // Redirect to login page
    exit();
}

$conn = get_database_connection();

if (isset($_GET['booking_id'])) {
    $booking_id = (int)$_GET['booking_id'];

    // Fetch booking details (replace with your actual query)
    $sql = "SELECT b.*, bs.start_time, bs.end_time
            FROM bookings b
            JOIN booking_slots bs ON b.slot_id = bs.slot_id
            WHERE b.booking_id = ? AND b.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    if (!$booking) {
        echo "Booking not found.";
        exit;
    }

    // Fetch booked tickets
    $sql = "SELECT bt.*, t.ticket_name
            FROM booked_tickets bt
            JOIN tickets t ON bt.ticket_id = t.ticket_id
            WHERE bt.booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booked_tickets = [];
    while ($row = $result->fetch_assoc()) {
        $booked_tickets[] = $row;
    }
} else {
    echo "No booking ID provided.";
    exit;
}

// Initialize cookie_consent if not already set
if (!isset($_SESSION['cookie_consent'])) {
    $_SESSION['cookie_consent'] = false;
}

// Handle form submission (if the user clicks "OK")
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept_cookies'])) {
    $_SESSION['cookie_consent'] = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/access_styles.css">
    <script src="../javascript/as.js"></script>
    <script src="../javascript/asopenclose.js"></script> <!--Controlling the open and close of the accessibility settings-->
    <script src="../javascript/initialisation.js"></script>
    <style>
        /* Add any page-specific styles here */
        .booking-confirmation {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
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
                    <li><a href="dashboard.php" class="button" id="dashboard-button">Dashboard</a></li>
                    <li><a href="logout.php" class="button" id="logout-button">Logout</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>

    <?php if (!$_SESSION['cookie_consent']): ?>
    <div id="cookie-overlay">
        <h1>We use cookies on this website.</h1>
        <p>This is so that we can store information about you on this website. These cookies will only be used on this website.</p>
        <br>
        <br>
        <form method="post">
            <button type="submit" id="cookie-accept" name="accept_cookies">OK</button>
        </form>
    </div>
    <?php endif; ?>

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

    <main>
        <section class="booking-confirmation">
            <h2>Booking Confirmation</h2>
            <p>Thank you for your booking!</p>
            <p>Your confirmation number is: <strong><?php echo htmlspecialchars($booking['confirmation_number']); ?></strong></p>
            <p>Booking Slot: <strong><?php echo date('D d M Y H:i', strtotime($booking['start_time'])) . ' - ' . date('H:i', strtotime($booking['end_time'])); ?></strong></p>

            <h3>Tickets:</h3>
            <ul>
                <?php foreach ($booked_tickets as $ticket): ?>
                    <li><?php echo htmlspecialchars($ticket['ticket_name']); ?> x <?php echo $ticket['quantity']; ?></li>
                <?php endforeach; ?>
            </ul>

            <p>Total Price: <strong>£<?php echo htmlspecialchars($booking['total_price']); ?></strong></p>
            <p>Payment Status: <strong><?php echo htmlspecialchars($booking['payment_status']); ?></strong></p>
            <!-- Display other booking details as needed -->
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