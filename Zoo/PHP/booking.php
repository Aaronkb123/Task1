<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in (you might need to adjust this based on your auth system)
if (!isset($_SESSION['user_id'])) {
    header("Location: form_login.php"); // Redirect to login page
    exit();
}

$pageTitle = "Book Tickets";
include_once('db_connection.php');
include_once('functions.php'); // Include functions.php here


$conn = get_database_connection(); // Call the function and assign

if (isset($conn)) {
    echo "<p>Database connection successful!</p>";


    $slots = get_booking_slots($conn);  // Call get_booking_slots
    $tickets = get_tickets($conn); // Call get_tickets

    echo "<p>get_booking_slots() and get_tickets() called successfully!</p>";

    $error_message = ""; // Initialize error message
    $total_price = null; // Initialize total price

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate slot_id
        $slot_id = isset($_POST['slot_id']) ? (int)$_POST['slot_id'] : 0;
        if ($slot_id <= 0) {
            $error_message = "Please select a valid booking slot.";
        } else {

            // Collect ticket quantities
            $ticket_quantities = [];
            foreach ($tickets as $ticket) {
                $ticket_id = $ticket['ticket_id'];
                $quantity = isset($_POST['ticket_' . $ticket_id]) ? (int)$_POST['ticket_' . $ticket_id] : 0;
                if ($quantity < 0) {
                    $quantity = 0; // Ensure no negative quantities
                }
                $ticket_quantities[$ticket_id] = $quantity;
            }

            // Calculate total price
            $total_price = calculate_total_price($ticket_quantities, $tickets);

            // Process booking
            $confirmation_number = generate_confirmation_number();
            $user_id = $_SESSION['user_id'];
            $booking_id = process_booking($conn, $user_id, $slot_id, $ticket_quantities, $total_price, $confirmation_number);

            if ($booking_id) {
                // Redirect to confirmation page
                header("Location: booking_confirmation.php?booking_id=" . $booking_id);
                exit();
            } else {
                $error_message = "Booking failed. Please try again.";
            }
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?></title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link rel="stylesheet" href="../CSS/booking_styles.css">
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
        <section class="booking-form">
            <h2>Book Your Tickets</h2>
       <?php  if ($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="slot_id">Select Booking Slot:</label>
                    <select name="slot_id" id="slot_id">
                        <option value="">-- Select a Slot --</option>
                        <?php foreach ($slots as $slot): ?>
                            <option value="<?php echo $slot['slot_id']; ?>"
                            <?php if (isset($slot_id) && $slot['slot_id'] == $slot_id) echo 'selected="selected"'; ?>>
                                <?php echo date('D d M Y H:i', strtotime($slot['start_time'])) . ' - ' . date('H:i', strtotime($slot['end_time'])); ?>
                                (<?php echo $slot['availability_spots']; ?> spots left)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
    <?php if (count($tickets) > 0): ?>
        <div class = "tickets">
            <?php foreach ($tickets as $ticket): ?>
                <div class="form-group">
                    <label for="ticket_<?php echo $ticket['ticket_id']; ?>"><?php echo htmlspecialchars($ticket['ticket_name']); ?> - £<?php echo htmlspecialchars($ticket['price']); ?></label>
                    <input type="number" id="ticket_<?php echo $ticket['ticket_id']; ?>" name="ticket_<?php echo $ticket['ticket_id']; ?>" value="<?php echo isset($ticket_quantities[$ticket['ticket_id']]) ? $ticket_quantities[$ticket['ticket_id']] : 0; ?>" min="0" max="<?php echo $ticket['availability']; ?>">
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No tickets found.</p>
    <?php endif; ?>
            <button type = "submit"> Book Now</button>
   <?php  if (isset($total_price)): ?>
                <div class="total-price">Total price £ <?php echo $total_price; ?></div>
            <?php endif; ?>
            </form>
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
<?php
    $conn->close();
} else {
    echo "<p>Database connection failed. \$conn is not set.</p>";
}
?>