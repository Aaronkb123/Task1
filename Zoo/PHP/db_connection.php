<?php
function get_database_connection() {
    $servername = "ccsw-mysql1.mysql.database.azure.com:3306";
    $username = "0020033934_User1";
    $password = "U2eFCqfKjycz";
    $database = "0020033934_DB1";
    echo"hello";
    // Create connection
    $conn = new mysqli ($servername, $username, $password, $database);
 
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connection successful!"; // Debugging output
    return $conn;
}
?>