<?php 
  if(!include_once('../api/login_logic.php')){
    die('Error finding login_logic.php file');
  }
?>

<!DOCTYPE html5>
<!-- HTML form for how the login is setup. 
Authors:Eric DeAngelis, David Serrano (serranod7). 
modified: Chris Ancheta, Kyle Kaminski -->

<html lang="en" dir="ltr">
  <head>
    <meta chartset="utf-8" />
    <title>ASRC Desktop Scheduler Login</title>

    <!-- Highest level of CSS, applies to all pages of the software. -->
    <link rel="stylesheet" href="../Styles/desktop.css" />

    <!-- CSS specifically for this page. -->
    <link rel="stylesheet" href="../Styles/login.css" />

    <script src="../Utils/docCookies.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  </head>
  <body class="background" background="../Images/Background.png">
    <form class="box" action="login.php" method="post">
      <!-- <img src="Images\asrc_logo.jpg" class="img" alt="ASRC"> -->
      <!-- Image without link -->
      <a href="https://www.asrcfederal.com/"
        ><img src="../Images/asrc_logo.jpg" title="ASRC" class="img" alt="ASRC"
      /></a>
      <h1 class="form-title">ASRC Desktop Scheduler Login</h1>
		<!-- form validation messages -->
      <?php include('../api/messages.php'); ?>
      <div class="MultiTextBoxForm">
        <p>Username</p>
        <input type="text" value="" name="username">
        <p>Password</p>
        <input type="password" name="password">
      </div> 
      <br>
        <input type="submit" name="login_user" class="login-btn" />
      <p><a href="enter_email.php">Forgot your password?</a></p>
      <p class="Reservation">Don't want to Login? <a href="../Views/viewreservations.php">View This Week's Schedule</a> here.</p>
    </form>
  </body>
</html>
