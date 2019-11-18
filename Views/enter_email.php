<?php include('../api/login_logic.php');?>

<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
    <link rel="stylesheet" href="../Styles/desktop.css" />
    <link rel="stylesheet" href="../Styles/login.css" />
  </head>
  <body class="background" background="../Images/Background.png">
    <div class="container">
        <form class="box" action="enter_email.php" method="post">
          <h1>Reset Password</h1>
          <?php include('../api/messages.php'); ?>
          <p class="MultiTextBoxForm" for="email">Enter Your Email</p>
          <input id="email" type="email" name="email">
          <br><br>
          <input type="submit" name="reset-password" value="Submit" />
        </form>
    </div>
  </body>
</html>
