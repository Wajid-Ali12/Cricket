<?php
include('register.php');
?>

<!DOCTYPE html>
<html>

<head>
  <title>Cricket Management System</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>

<body>
  <div class="main">

    <input type="checkbox" id="chk" aria-hidden="true">

    <div class="signup">
      <form id="register-form" action="join.php" method="post">
        <label for="chk" aria-hidden="true">Sign up</label>
        <input type="text" name="username" id="username" placeholder="Username" required>
        <input type="email" name="email" id="email" placeholder="Email Address" required>
        <input type="password" name="password_1" id="password" placeholder="Password" required>
        <input type="password" name="password_2" id="confirm-password" placeholder="Confirm Password" required>
        <button type="submit" name="register-button" id="register-button">Sign up</button>
      </form>
    </div>

    <div class="login">
      <form id="login-form" action="join.php" method="post">
        <label for="chk" aria-hidden="true">Login</label>
        <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="" required>
        <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" required>
        <button type="submit" name="login-button" id="login-button">Login</button>
      </form>
    </div>


  </div>
</body>

</html>