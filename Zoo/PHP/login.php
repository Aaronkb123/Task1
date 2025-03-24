<?php
include 'db_connection.php';
session_start();

function sanitize_input($conn, $input) {
    return $conn->real_escape_string($input);
}


function authenticate_user($conn, $username, $password) {
    $sql = "SELECT user_id, username, password FROM user_details WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return false; // Or handle error as desired
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];
        
        if (password_verify($password, $hashedPassword)) {
            // Corrected line
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            // Handle incorrect password case
            display_error("Invalid username or password");
        }
    } else {
        // Handle case where username is not found
        display_error("User not found");
    }

    $stmt->close();
    return false; // Authentication failed
}



function display_error($message) {
    echo $message;
}

function main(){
// Main execution flow
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $conn = get_database_connection();

        // Sanitize inputs
        $username = sanitize_input($conn, $_POST["username"]);
        $password = sanitize_input($conn, $_POST["password"]);
        
        echo $username;
        echo $password;
        echo 'password and username reached';
        echo "<br>";

        // Authenticate User
        $user_data = authenticate_user($conn, $username, $password);
        if ($user_data) {
            login_user($user_data);
        } else {
            display_error("Invalid username or password");
        }

        $conn->close();
    } else {
        display_error("Invalid request method.");
    }
}

main();
?>