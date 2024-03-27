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
        <a class="navbar-brand" href="../adminpanel.php"><img src="../../Landing Page/img/cricketlogo.png" alt=""></a>
      </div>
      <div class="collapse navbar-collapse" id="navbar-collapse-man">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="../User/user.php">User</a></li>
          <li><a href="../Team/team.php">TEAM</a></li>
          <li><a href="../Contract/contract.php">CONTRACT</a></li>
          <li><a class="active" href="club.php">CLUB</a></li>
          <li><a href="../Player/player.php">PLAYER</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <table class="container">
    <tr>
      <th>Id</th>
      <th>Name of club</th>
      <th>Post code</th>
      <th>President</th>
      <th>Register Date</th>
      <th>Action</th>
    </tr>

    <?php
    $conn = mysqli_connect("localhost", "root", "", "cricket");

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editClubId'])) {
      $id = $_POST['editClubId'];
      $clubname = $_POST['editClubName'];
      $location = $_POST['editClubLocation'];
      $postcode = $_POST['editClubPostcode'];
      $president = $_POST['editClubPresident'];
      $regdate = $_POST['editClubRegdate'];

      $sql = "UPDATE clubreg SET clubname='$clubname', location='$location', postcode='$postcode', president='$president', regdate='$regdate' WHERE id=$id";

      if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
      } else {
        echo "Error updating record: " . $conn->error;
      }
    }

    $sql = "SELECT id, clubname, location, postcode, president, regdate FROM clubreg";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["clubname"] . "</td>";
        echo "<td>" . $row["location"] . $row["postcode"] . "</td>";
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
          // Send AJAX request to delete the club
          $.ajax({
            type: 'POST',
            url: '<?php echo $_SERVER["PHP_SELF"]; ?>',
            data: {
              deleteClubId: id
            },
            success: function(response) {
              // Remove the row from the table
              $('#row_' + id).remove();
            },
            error: function(xhr, status, error) {
              console.error(xhr.responseText);
            }
          });
        }
      }
    </script>

</body>

</html>