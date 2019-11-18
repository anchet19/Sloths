
<?php include('../api/login_logic.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
  <title>Password Reset</title>
  <link rel="stylesheet" href="../Styles/desktop.css" />
  <link rel="stylesheet" href="../Styles/login.css" />
</head>
<body class="background" background="../Images/Background.png">
  <div class="container">
    <form class="box" action="new_password.php" method="post">
      <h1 class="form-title">New password</h1>
      <p class="NNotif">A temporary password has been sent to your email. Please enter it below.</p>
      <!-- form validation messages -->
      <?php include('../api/messages.php'); ?>
      <div class="MultiTextBoxForm">
        <p>Temporary password</p>
        <input id="password1" type="password" name="temp_pass">
        <br>
        <p>New password</p>
        <input id="password2" type="password" name="new_pass">
        <br>
        <p>Confirm new password</p>
        <input id="password3" type="password" name="new_pass_c">
        <br><br>
        <input type="submit" name="new_password" class="login-btn" />
      </div>
    </form>
  </div>
</body>
</html>
