<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = mysqli_connect('localhost', 'root', '', 'temphumidnew');

    if (!$db) {
        echo json_encode("Database connection failed");
    } else {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Check if the username already exists
            $sql = "SELECT username FROM user WHERE username = ?";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $count = mysqli_stmt_num_rows($stmt);

            if ($count > 0) {
                echo json_encode("Error: Username already exists");
            } else {
                // Hash the password securely
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Insert the username and hashed password into the database
                $insert = "INSERT INTO user (username, password) VALUES (?, ?)";
                $stmt = mysqli_prepare($db, $insert);
                mysqli_stmt_bind_param($stmt, 'ss', $username, $hashedPassword);
                $query = mysqli_stmt_execute($stmt);

                if ($query) {
                    echo json_encode("Success");
                } else {
                    echo json_encode("Error: Unable to insert data");
                }
            }
        } else {
            echo json_encode("Error: Username and password are required.");
        }

        mysqli_close($db);
    }
}
?>
