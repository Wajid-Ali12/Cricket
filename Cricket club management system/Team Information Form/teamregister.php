<?php
  $db = mysqli_connect('localhost', 'root', '', 'cricket');

  $clubid = '';
  $playerid = '';
  $tfdate = '';
  $eventname = '';
  $teamleaderid = '';
  $playername = '';
  $coachid = '';
  $coachname = '';

  $playerid_01 = '';
  $playername_01 = '';
  $playerid_02 = '';
  $playername_02 = '';
  $playerid_03 = '';
  $playername_03 = '';

  if(isset($_POST['team-reg-btn'])){
    $clubid = mysqli_real_escape_string($db, $_POST['clubid']);
    $playerid = mysqli_real_escape_string($db, $_POST['playerid']);
    $tfdate = mysqli_real_escape_string($db, $_POST['tfdate']);
    $eventname = mysqli_real_escape_string($db, $_POST['eventname']);
    $teamleaderid = mysqli_real_escape_string($db, $_POST['teamleaderid']);
    $playername = mysqli_real_escape_string($db, $_POST['playername']);
    $coachid = mysqli_real_escape_string($db, $_POST['coachid']);
    $coachname = mysqli_real_escape_string($db, $_POST['coachname']);

    $playerid_01 = mysqli_real_escape_string($db, $_POST['playerid_01']);
    $playername_01 = mysqli_real_escape_string($db, $_POST['playername_01']);

    // Inserting team information
    $sql = "INSERT INTO teamreg (clubid, playerid, teamleaderid, tfdate, event_id, playername, coachid, coachname) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssss", $clubid, $playerid, $teamleaderid, $tfdate, $eventname, $playername, $coachid, $coachname);
    $query = mysqli_stmt_execute($stmt);

    if ($query) {
      // Inserting team player information
      $sql_01 = "INSERT INTO teamplayer (clubid, teamleaderid, coachid, playerid, playername) VALUES (?, ?, ?, ?, ?)";
      $stmt_01 = mysqli_prepare($db, $sql_01);
      mysqli_stmt_bind_param($stmt_01, "sssss", $clubid, $teamleaderid, $coachid, $playerid_01, $playername_01);
      $query_01 = mysqli_stmt_execute($stmt_01);

      if ($query_01) {
        echo "<script>
                alert('Team Information submitted successfully!');
                window.location.href='../Landing Page/homepage.php';
              </script>";
      } else {
        echo "<script>
                alert('Team Player Information submission failed. Please check your inputs.');
              </script>";
      }
    } else {
      echo "<script>
              alert('Team Information submission failed. Please check your inputs.');
            </script>";
    }

    // Close prepared statements
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt_01);
  }
?>
