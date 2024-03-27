<?php
session_start();
$db = mysqli_connect('localhost', 'root', '', 'cricket');

$clubname = '';
$house = '';
$location = '';
$postcode = '';
$president = '';
$regdate = '';


if (isset($_POST['club-reg-button'])) {
  $clubname = mysqli_real_escape_string($db, $_POST['clubname']);
  $location = mysqli_real_escape_string($db, $_POST['location']);
  $postcode = mysqli_real_escape_string($db, $_POST['postcode']);
  $president = mysqli_real_escape_string($db, $_POST['president']);
  $regdate = mysqli_real_escape_string($db, $_POST['regdate']);

  $sql = "INSERT INTO clubreg (clubname,  location, postcode, president,regdate) VALUES ('$clubname', '$location', '$postcode', '$president','$regdate')";
  mysqli_query($db, $sql);
}
