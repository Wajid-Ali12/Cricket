<?php

// Function to check if user is admin (replace with your actual connection details)
function isAdmin($conn)
{
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if (!$userId) {
        return false; // Not logged in
    }

    $query = "SELECT isAdmin FROM profileinfo WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query); // Prepare statement for security

    if (!$stmt) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $userId); // Bind user ID parameter
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die("Error getting result: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $isAdmin = $row['isAdmin'];
    } else {
        $isAdmin = false; // No user found with ID
    }

    mysqli_stmt_close($stmt); // Close prepared statement

    return $isAdmin;
}

// Function to display admin navbar content
function displayAdminNavbar()
{
    if (isAdmin($GLOBALS['conn'])) { // Use global connection variable if available
        echo '
      <li><a href="../Profile/profile.php"><i class="glyphicon glyphicon-user"></i>&nbsp; Profile</a></li>
      <li><a href="../Admin Panel/User/user.php"><i class="fa-solid fa-circle-user"></i>&nbsp; View Users</a></li>
      <li><a href="../Admin Panel/Team/team.php"><i class="fa-solid fa-people-group"></i>&nbsp; View Teams</a></li>
      <li><a href="../Admin Panel/Contract/contract.php"><i class="fa-solid fa-address-book"></i>&nbsp; View Contracts</a></li>
      <li><a href="../Admin Panel/Club/club.php"><i class="fa-solid fa-house-lock"></i>&nbsp; View CLUB</a></li>
      <li><a href="../Admin Panel/Player/player.php">View Players</a></li>
    ';
    }
}
