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
  <title>Club Information</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
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
      <th>ID</th>
      <th>Name of club</th>
      <th>Location</th>
      <th>Post code</th>
      <th>President</th>
      <th>Register Date</th>
      <th>Action</th>
    </tr>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteClubId'])) {
      $deleteId = $_POST['deleteClubId'];
      $deleteQuery = "DELETE FROM clubreg WHERE id=$deleteId";

      if ($conn->query($deleteQuery) === TRUE) {
        echo "Record deleted successfully";
      } else {
        echo "Error deleting record: " . $conn->error;
      }
    }

    $sql = "SELECT id, clubname, location, postcode, president, regdate FROM clubreg";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<tr id='row_" . $row["id"] . "'>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["clubname"] . "</td>";
        echo "<td>" . $row["location"] .  "</td>";
        echo "<td>" . $row["postcode"] .  "</td>";
        echo "<td>" . $row["president"] . "</td>";
        echo "<td>" . $row["regdate"] . "</td>";
        echo '<td>';
        echo '<div class="btn-group">';
        echo '<button type="button" class="btn btn-primary btn-sm" onclick="editClub(' . $row["id"] . ', \'' . $row["clubname"] . '\', \'' . $row["location"] . '\', \'' . $row["postcode"] . '\', \'' . $row["president"] . '\', \'' . $row["regdate"] . '\')">Edit</button>';
        echo '<button type="button" class="btn btn-danger btn-sm" onclick="deleteClub(' . $row["id"] . ')">Delete</button>';
        echo '</div>';
        echo '</td>';
        echo "</tr>";
      }
      echo "</table>";
    } else {
      echo "No member registered yet.";
    }

    $conn->close();
    ?>

    <!-- Edit Club Modal -->
    <div id="editClubModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Club</h4>
          </div>
          <div class="modal-body">

            <form id="editClubForm" method="POST">
              <input type="hidden" id="editClubId" name="editClubId">
              <div class="form-group">
                <label for="editClubName">Name of Club:</label>
                <input type="text" class="form-control" id="editClubName" name="editClubName">
              </div>
              <div class="form-group">
                <label for="editClubLocation">Location:</label>
                <input type="text" class="form-control" id="editClubLocation" name="editClubLocation">
              </div>
              <div class="form-group">
                <label for="editClubPostcode">Postcode:</label>
                <input type="text" class="form-control" id="editClubPostcode" name="editClubPostcode">
              </div>
              <div class="form-group">
                <label for="editClubPresident">President:</label>
                <input type="text" class="form-control" id="editClubPresident" name="editClubPresident">
              </div>
              <div class="form-group">
                <label for="editClubRegdate">Register Date:</label>
                <input type="text" class="form-control" id="editClubRegdate" name="editClubRegdate">
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
      function editClub(id, clubname, location, postcode, president, regdate) {
        $('#editClubId').val(id);
        $('#editClubName').val(clubname);
        $('#editClubLocation').val(location);
        $('#editClubPostcode').val(postcode);
        $('#editClubPresident').val(president);
        $('#editClubRegdate').val(regdate);
        $('#editClubModal').modal('show');
      }

      function deleteClub(id) {
        if (confirm("Are you sure you want to delete this club?")) {
          $.ajax({
            type: 'POST',
            url: '<?php echo $_SERVER["PHP_SELF"]; ?>', // Current script
            data: {
              deleteClubId: id
            },
            success: function(response) {
              // Remove the row from the table
              $('#row_' + id).remove();
              alert("Club deleted successfully!"); // User feedback
            },
            error: function(xhr, status, error) {
              console.error(xhr.responseText);
              alert("Error deleting club. Please try again."); // User feedback
            }
          });
        }
        return false; // Prevent form submission
      }
    </script>

</body>

</html>