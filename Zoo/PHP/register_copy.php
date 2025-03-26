<?php
include 'db_connection.php';
session_start();

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

/**
 * Checks if a username is already taken in the database.
 *
 * @param mysqli $conn  The database connection.
 * @param string $username The username to check.
 *
 * @return bool True if the username exists, false otherwise.
 */
function is_username_taken($conn, $username)
{
    $sql = "SELECT username FROM user_details WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error); // Log error
        return false; // Indicate failure
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result && $result->num_rows > 0;
}

/**
 * Creates a new user in the database.
 *
 * @param mysqli $conn The database connection.
 * @param string $username The username.
 * @param string $password The password (hashed).
 * @param string $firstname The first name.
 * @param string $surname The surname.
 * @param string $email The email address.
 * @param string $mobile The mobile phone number.
 *
 * @return int|false The user ID if successful, false otherwise.
 */
function create_user($conn, $username, $password, $firstname, $surname, $email, $mobile, $address_line_1, $address_line_2, $city, $postal_code, $date_recorded)
{
    $sql = "INSERT INTO user_details (username, password, firstname, surname, email, mobile, address_line_1, address_line_2, city, postal_code date_recorded) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error); // Log error
        return false; // Indicate failure
    }

    $stmt->bind_param("ssssssssssss", $username, $password, $firstname, $surname, $email, $mobile, $address_line_1, $address_line_2, $city, $postal_code, $date_recorded)
    $success = $stmt->execute();
    $stmt->close();

    if ($success) {
        return $conn->insert_id;
    } else {
     
        error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error); // Log error
        return false; // Indicate failure
    }
}

/**
 * Sets session variables and redirects to the dashboard.
 *
 * @param int $user_id The user ID.
 * @param string $username The username.
 */
function set_session_and_redirect($user_id, $username)
{
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    header("Location: dashboard.php");
    exit();
}

/**
 * Sets an error message in the session and redirects to the registration form.
 *
 * @param string $message The error message.
 */
function display_error_and_redirect($message)
{
    $_SESSION['error_message'] = $message;
    header("Location: form_register.php");
    exit();
}

/**
 * Validates the registration form data.
 *
 * @param array $data The form data ($_POST).
 *
 * @return array An array of error messages, or an empty array if there are no errors.
 */
function validate_registration_data($data)
{
    $errors = [];

    $username = $data["username"];
    $password = $data["password"];
    $firstname = $data["firstname"];
    $surname = $data["surname"];
    $email = $data["email"];
    $mobile = $data["mobile"];
    $address_line_1 = $data["address_line_1"];
    $address_line_2 = $data["address_line_2"];
    $city = $data["city"];
    $postal_code = $data["postal_code"];

    // Username Validation
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $errors[] = "Username must be between 3 and 50 characters.";
    }

    // Password Validation (Example - add more complexity checks)
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Email Validation
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    
    // Email Validation
    if (empty($address_line_1)) {
        $errors[] = "Address Line 1 is required.";
    }

    if (empty($city)) {
        $errors[] = "City is required";
    }

    if (empty($postal_code)) {
        $errors[] = "Postal code is required.";
    }

    // First Name and Last Name validation
    if (empty($firstname)) {
        $errors[] = "First Name is required.";
    }

    if (empty($surname)) {
        $errors[] = "Surname is required.";
    }

    // Mobile validation (very basic - improve with regex)
    if (empty($mobile)) {
        $errors[] = "Mobile is required.";
    } elseif (!is_numeric($mobile)) {
        $errors[] = "Mobile must be numeric.";
    }
    return $errors;
}

/**
 * Handles the user registration process.
 *
 * @param mysqli $conn The database connection.
 */
function handle_registration($conn)
{
    $validation_errors = validate_registration_data($_POST);

    if (!empty($validation_errors)) {
        display_error_and_redirect(implode("<br>", $validation_errors));
        return; // Stop further execution
    }

    $username = $_POST["username"];
    $password = $_POST["password"];
    $firstname = $_POST["firstname"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile"];
    $address_line_1 = $data["address_line_1"];
    $address_line_2 = $data["address_line_2"];
    $city = $data["city"];
    $postal_code = $data["postal_code"];

    if (is_username_taken($conn, $username)) {
        display_error_and_redirect("Username already exists. Please choose another.");
        return; // Stop further execution
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $user_id = create_user($conn, $username, $password_hash, $firstname, $surname, $email, $mobile, $address_line_1, $address_line_2, $city, $postal_code);

    if ($user_id) {
        set_session_and_redirect($user_id, $username);
    } else {
        error_log("Error registering user: " . $conn->error);
        display_error_and_redirect("Error registering user. Please try again.");
    }
}

// Main execution flow
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = get_database_connection();

    handle_registration($conn);

    $conn->close();
}
?>