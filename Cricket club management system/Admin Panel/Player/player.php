<?php
include('../../Join/register.php');
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
if (!isset($_SESSION['username'])) {
  header("Location: ../../Join/join.php");
  exit();
}

// Establish connection (if not already done)
$conn = mysqli_connect("localhost", "root", "", "cricket");

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Player Information</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="../../Landing Page/css/style.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="../../Landing Page/homepage.php"><img src="../../Landing Page/img/cricketlogo.png" alt=""></a>
      </div>
      <div class="collapse navbar-collapse" id="navbar-collapse-man">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="../../Team Information Form/teamInfoForm.php">TEAM</a></li>
          <li><a href="../../Contract Form/contractForm.php">CONTRACT</a></li>
          <li><a href="../../Club Registration Form/clubRegistration.php">CLUB</a></li>
          <li><a href="../../Player Registration Form/playerRegistration.php">PLAYER</a></li>

          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="glyphicon glyphicon-cog"></i></a>
            <ul class="dropdown-menu" role="menu">
              <?php if (isAdmin($conn)) : ?>
                <li><a href="../User/user.php"><i class="fa-solid fa-circle-user"></i>&nbsp; View Users</a></li>
                <li><a href="../Team/team.php"><i class="fa-solid fa-people-group"></i>&nbsp; View Teams</a></li>
                <li><a href="../Contract/contract.php"><i class="fa-solid fa-address-book"></i>&nbsp; View Contracts</a></li>
                <li><a href="../Club/club.php"><i class="fa-solid fa-house-lock"></i>&nbsp; View CLUB</a></li>
                <li><a href="../Player/player.php"><i class="fa-solid fa-circle-info"></i>View Players</a></li>

              <?php endif; ?>
              <li class="divider"></li>
              <li><a href="../Profile/logout.php"><i class="glyphicon glyphicon-log-out"></i>&nbsp; Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <table class="container" id="contract-table">
    <tr>
      <th>Player Id</th>
      <th>First name</th>
      <th>Middle name</th>
      <th>Last name</th>
      <th>Previous club</th>
      <th>Present club</th>
      <th>Total Run</th>
      <th>Total Wicket</th>
      <th>Best Run</th>
      <th>Best Wicket</th>
      <th>Organization</th>
      <th>Degree</th>
      <th>Date of Birth</th>
    </tr>

    <?php
    $conn = mysqli_connect("localhost", "root", "", "cricket");

    $sql = "SELECT playerreg.player_id, playerreg.firstname, playerreg.middlename, playerreg.lastname, previoushistory.clubfrom, previoushistory.clubto, previoushistory.run, previoushistory.wicket, bestperform.run AS bp_run, previoushistory.wicket AS bp_wicket, educations.degree, playerreg.dob, membership.org FROM playerreg , educations, previoushistory, bestperform, membership WHERE (playerreg.player_id = educations.player_id) AND (playerreg.player_id = previoushistory.player_id) AND (playerreg.player_id = bestperform.player_id) AND (playerreg.player_id = membership.player_id)";

    $result = $conn->query($sql);

    $result =  mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
      while ($row = mysqli_fetch_array($result)) {

        echo "<tr><td>" . $row["player_id"] . "</td><td>" . $row["firstname"] . "</td><td>" . $row["middlename"] . "</td><td>" . $row["lastname"] . "</td><td>" . $row["clubfrom"] . "</td><td>"
          . $row["clubto"] . "</td><td>"  . $row["run"] . "</td><td>" . $row["wicket"] . "</td><td>" . $row["bp_run"] . "</td><td>"  . $row["bp_wicket"] . "</td><td>" . $row["org"] . "</td><td>" . $row["degree"] . "</td><td>" . $row["dob"] . "</td></tr>";
      }
      echo "</table>";
    } else {
      echo "No member registerd yet.";
    }
    $conn->close();
    ?>
  </table>



</body>

</html>