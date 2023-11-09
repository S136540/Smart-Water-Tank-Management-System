<?php
$db = mysqli_connect('localhost', 'root', '', 'temphumidnew');
if (!$db) {
    echo "Database connection failed";
}

$username = $_POST['username'];
$password = $_POST['password'];

// Check if the username already exists
$sql = "SELECT username FROM user WHERE username = '" . $username . "'";
$result = mysqli_query($db, $sql);
$count = mysqli_num_rows($result);

if ($count == 1) {
    echo json_encode("Error: Username already exists");
} else {
    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert the username and hashed password into the database
    $insert = "INSERT INTO user(username, password) VALUES ('" . $username . "', '" . $hashedPassword . "')";
    $query = mysqli_query($db, $insert);

    if ($query) {
        echo json_encode("Success");
    } else {
        echo json_encode("Error: Unable to insert data");
    }
}
?>