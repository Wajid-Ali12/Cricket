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
  <title>Team Information</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                <li><a href="../Player/player.php">View Players</a></li>
                <li><a href="../../Profile/profile.php"><i class="glyphicon glyphicon-user"></i>&nbsp; Profile</a></li>
              <?php endif; ?>
              <li class="divider"></li>
              <li><a href="../Profile/logout.php"><i class="glyphicon glyphicon-log-out"></i>&nbsp; Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <table class="container">
    <tr>
      <th>Club id</th>
      <th>Team leader id</th>
      <th>Leader Name</th>
      <th>Player id</th>
      <th>Team F. Date</th>
      <th>Event name/Even id</th>
      <th>Coach id</th>
      <th>Coach name</th>
      <th>Player Name</th>
    </tr>

    <?php
    $conn = mysqli_connect("localhost", "root", "", "cricket");

    $sql = "SELECT teamreg.clubid, teamreg.playerid, teamreg.tfdate, teamreg.event_id, teamreg.teamleaderid, teamreg.playername AS leadername, teamreg.coachid, teamreg.coachname,  teamplayer.playername FROM teamreg, teamplayer WHERE (teamreg.clubid = teamplayer.clubid) AND (teamreg.teamleaderid = teamplayer.teamleaderid)";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {

        echo "<tr><td>" . $row["clubid"] . "</td><td>" . $row["teamleaderid"] . "</td><td>" . $row["leadername"] . "</td><td>" . $row["playerid"] . "</td><td>"
          . $row["tfdate"] . "</td><td>"  . $row["event_id"] . "</td><td>" . $row["coachid"] . "</td><td>" . $row["coachname"] . "</td><td>" . $row["playername"] . "</td></tr>";
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