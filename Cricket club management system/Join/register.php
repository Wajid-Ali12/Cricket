<?php
session_start();
$db = mysqli_connect('localhost', 'root', '', 'cricket');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = '';
$email = '';
$password_1 = '';
$password_2 = '';

if (isset($_POST['register-button'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    // Check if the username is already taken
    $check_username_query = "SELECT * FROM register WHERE username='$username'";
    $check_username_result = mysqli_query($db, $check_username_query);

    if (mysqli_num_rows($check_username_result) > 0) {
        echo "<script>alert('Username already taken. Please choose a different username.');</script>";
    } else {
        if ($password_1 == $password_2) {
            // Insert the new user into the database
            $insert_query = "INSERT INTO register (username, email, password) VALUES ('$username', '$email', '$password_1')";
            $result = mysqli_query($db, $insert_query);

            if ($result) {
                echo "<script>alert('Registration successful. You can now login.');</script>";
            } else {
                error_log(mysqli_error($db));
                echo "<script>alert('Registration failed.');</script>";
            }
        } else {
            echo "<script>alert('Passwords do not match.');</script>";
        }
    }
}

if (isset($_POST['login-button'])) {
    $username = mysqli_real_escape_string($db, trim($_POST['username']));
    $password = mysqli_real_escape_string($db, trim($_POST['password']));

    // Retrieve the user from the database based on the lowercase username
    $query = "SELECT * FROM register WHERE LOWER(username) = LOWER('$username')";
    $result = mysqli_query($db, $query);

    if (!$result) {
        error_log(mysqli_error($db));
        echo "<script>alert('Login Failed. Please try again.');</script>";
    } else {
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            if ($password == $row['password']) {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $row['id'];
                session_regenerate_id(); // Regenerate session ID for security
                header("Location: ../Landing Page/homepage.php");
                exit();
            } else {
                echo "<script>alert('Login Failed. Please check your username and password.');</script>";
            }
        } else {
            echo "<script>alert('Login Failed. User not found.');</script>";
        }
    }
}
mysqli_close($db);
