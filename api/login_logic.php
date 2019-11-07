<?php 
if(!include_once('../Utils/connect.php')){
    die('error finding connect file');
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'Exception.php';
require 'PHPMailer.php'; # PHPMailer files to send emails via an SMTP
require 'SMTP.php';

session_start();
$errors = [];
$user_id = "";
// connect to database
$db = ConnectDB();

/*
  Accept email of user whose password is to be reset
  Send email to user to reset their password
*/
if (isset($_POST['reset-password'])) {
  $email = $_POST['email'];
  // ensure that the user exists on our system
  $sql = "SELECT COUNT(*) FROM user WHERE email='$email'";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $result =  $stmt->fetch();
  $stmt = null;

  if (empty($email)) {
    array_push($errors, "Your email is required");
  }else if($result[0] <= 0) {
    array_push($errors, "Sorry, no user exists on our system with that email");
  }
  // generate a unique random token of length 100
  $token = bin2hex(random_bytes(50));

  if (count($errors) == 0) {
    // store token in the password-reset database table against the user's email
    $sql = "INSERT INTO password_reset(email, token) VALUES ('$email', '$token')";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $stmt = null;

    // Send email to user with the token in a link they can click on
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;  
    $mail->Username = "asrcscheduler@gmail.com";
    $mail->Password = "!QAZ1qazlizard";
    $mail->Host     = "smtp.gmail.com";
    $mail->Mailer   = "smtp";
    $mail->SetFrom("asrcscheduler@gmail.com", "Schedule Genie");
    $mail->AddReplyTo("No-reply", "PHPPot");
    $mail->AddAddress($email);
    $mail->Subject = "Reset your password";
    $mail->WordWrap   = 80;
    $content = "Hi there, click on this <a href=\"localnew_password.php?token=" . $token . "\">link</a> to reset your password";
    $mail->MsgHTML($content);
    $mail->IsHTML(true);
    $mail->send();
    header('location: pending.php?email=' . $email);
  }
}

// ENTER A NEW PASSWORD
if (isset($_POST['new_password'])) {
  $new_pass = $_POST['new_pass'];
  $new_pass_c = $_POST['new_pass_c'];

  // Grab token that came from the email link
  $token = $_SESSION['token'];
  if (empty($new_pass) || empty($new_pass_c)) array_push($errors, "Password is required");
  if ($new_pass !== $new_pass_c) array_push($errors, "Passwords do not match");
  if (count($errors) == 0) {
    // select email address of user from the password_reset table 
    $sql = "SELECT email FROM password_reset WHERE token='$token' LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetch();
    $email = $results[0];
    $stmt = null;

    if ($email) {
      $new_pass = password_hash($new_pass,PASSWORD_DEFAULT);
      $sql = "UPDATE user SET password='$new_pass' WHERE email='$email'";
      $stmt = $db->prepare($sql);
      $stmt->execute();
      $results = $stmt->fetch();
      header('location: index.html');
    }
  }
}
?>