<?php
include('../Join/register.php');

// Check if the user is an admin
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

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  header("Location: ../Join/join.php");
  exit();
}

// Establish connection (if not already done)
$conn = mysqli_connect("localhost", "root", "", "cricket");
?>
<!DOCTYPE html>
<html>

<head>
  <title>Welcome | Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href="css/style.css">
</head>

<body>



  <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="../Landing Page/homepage.php"><img src="../Landing Page/img/cricketlogo.png" alt=""></a>
      </div>
      <div class="collapse navbar-collapse" id="navbar-collapse-man">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="../Team Information Form/teamInfoForm.php">TEAM</a></li>
          <li><a href="../Contract Form/contractForm.php">CONTRACT</a></li>
          <li><a href="../Club Registration Form/clubRegistration.php">CLUB</a></li>
          <li><a href="../Player Registration Form/playerRegistration.php">PLAYER</a></li>

          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="glyphicon glyphicon-cog"></i></a>
            <ul class="dropdown-menu" role="menu">
              <?php if (isAdmin($conn)) : ?>
                <li><a href="../Admin Panel/User/user.php"><i class="fa-solid fa-circle-user"></i>&nbsp; View Users</a></li>
                <li><a href="../Admin Panel/Team/team.php"><i class="fa-solid fa-people-group"></i>&nbsp; View Teams</a></li>
                <li><a href="../Admin Panel/Contract/contract.php"><i class="fa-solid fa-address-book"></i>&nbsp; View Contracts</a></li>
                <li><a href="../Admin Panel/Club/club.php"><i class="fa-solid fa-house-lock"></i>&nbsp; View CLUB</a></li>
                <li><a href="../Admin Panel/Player/player.php">View Players</a></li>
                <li><a href="../Profile/profile.php"><i class="glyphicon glyphicon-user"></i>&nbsp; Profile</a></li>
              <?php endif; ?>
              <li class="divider"></li>
              <li><a href="../Profile/logout.php"><i class="glyphicon glyphicon-log-out"></i>&nbsp; Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>


  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
      <li data-target="#carousel-example-generic" data-slide-to="1"></li>
      <li data-target="#carousel-example-generic" data-slide-to="2"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item">
        <img src="img/slide/image_01.jpg" alt="pic">
        <div class="carousel-caption">
        </div>
      </div>
      <div class="item active">
        <img src="img/slide/image_02.jpg" alt="pic">
        <div class="carousel-caption">
        </div>
      </div>
      <div class="item">
        <img src="img/slide/image_03.jpg" alt="lol">
        <div class="carousel-caption">
        </div>
      </div>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>

  <!----------- SECOND DIV -------------->

  <div id="all-about-second" class="padding">
    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          <img src="img/cricket3.png" alt="cricket boy">
        </div>
        <div class="col-sm-6 text-center">
          <h2>All About Using Cricket</h2>
          <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
      </div>
    </div>
  </div>


  <!----------- THIRD DIV -------------->

  <div class="padding">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <h4>HEADLINE GOES HERE</h4>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <img src="img/cricket2.png" alt="" class="img-responsive">
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <h4>ANOTHER HEADINGS</h4>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <img src="img/bd.png" alt="" class="img-responsive">
        </div>
      </div>
    </div>
  </div>


  <!----------- FOURTH DIV -------------->

  <div id="fixed">
    <div class="padding">
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <h4>Here's the coll thing about Bootstrap</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore
              magna aliqua.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore
              magna aliqua.</p>
          </div>
          <div class="col-sm-6">
            <img src="img/cricket4.png" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-----------  FOOTER -------------->

  <footer class="container-fluid text-center">
    <div class="row">
      <div class="col-sm-4">
        <h3>Contact US</h3>
        <br>
        <h4>Ghazi University, Dera Ghazi Khan Tel # +92 (64) 9260135 info@gudgk.edu.pk</h4>
      </div>

      <div class="col-sm-4">
        <h3>Connect</h3>
        <a href="#" class="fa fa-facebook"></a>
        <a href="#" class="fa fa-twitter"></a>
        <a href="#" class="fa fa-google"></a>
        <a href="#" class="fa fa-linkedin"></a>
        <a href="#" class="fa fa-youtube"></a>
      </div>

      <div class="col-sm-4">
        <img src="img/slide/footer_01.png" alt="B" class="icon">
      </div>
    </div>
  </footer>

</body>


</html>