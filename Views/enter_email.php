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
      <div>
        <form class="box" action="enter_email.php" method="post">
          <h1>Reset Password</h1>
          <a href="https://www.asrcfederal.com/">
            <img src="../Images/asrc_logo.jpg" title="ASRC" class="img" alt="ASRC"/>
          </a>
          <br><br>
          <?php include('../api/messages.php'); ?>
          <br>
          <font for="email">Enter Email</font>
          <input id="email" type="email" name="email">
          <br>
          <br>
          <input type="submit" name="reset-password" value="Submit" />
        </form>
      </div>
    </div>
  </body>
</html>
