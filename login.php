<?php
$db = mysqli_connect('localhost', 'root', '', 'temphumidnew');
$username = $_POST['username'];
$password = $_POST['password'];

// Retrieve the hashed password from the database
$sql = "SELECT password FROM user WHERE username = '" . $username . "'";
$result = mysqli_query($db, $sql);

if ($result && mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $hashedPasswordFromDatabase = $row['password'];

    // Use password_verify() to check if the provided password matches the stored hashed password
    if (password_verify($password, $hashedPasswordFromDatabase)) {
        echo json_encode("Success");
    } else {
        echo json_encode("Error: Incorrect password");
    }
} else {
    echo json_encode("Error: User not found");
}
?>