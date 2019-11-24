<?php 
/**
 * This file handles all of the login and password reset functions of the application. 
 * The password reset feature operates via a SMTP mail server. 
 * Currently the values for that are hard coded into the system 
 * but they can easily be populated using configuration.xml
 * 
 * Author: Chris Ancheta
 * created: 2019-11-8
 */
session_start();

if(!include_once('../Utils/connect.php')){
    die('error finding connect file');
}
if(!include_once('validate_func.php'))
{
  die('error finding validate_func file');
}

// Include the necessary files for the SMTP mail server
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'Exception.php';
require 'PHPMailer.php'; # PHPMailer files to send emails via an SMTP
require 'SMTP.php';

// 
$errors = [];

// connect to database
$db = ConnectDB();
if(!$db){
  die('Could not connect: ' . mysql_error());
}

// LOG USER IN
if (isset($_POST['login_user'])) {
  // Get username and password from login form
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  // validate form
  if (empty($username)) array_push($errors, "Username is required");
  if (empty($password)) array_push($errors, "Password is required");
  

  // if no error in form, check number of failed login attempts and lock if > 5 
  // otherwise log user in
  if (count($errors) == 0) {
    $attempts = getAttempts($username);
    if($attempts < 5) {
      if (validate($username, $password)) {
        // Reset login attempts back to zero
        $sql = "UPDATE user SET login_attempts = 0 WHERE username = '$username'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        // Remove all password reset tokens for this user (if any)
        clearResetTokens($username);
        // Store username in session variable for easy access.
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: index');
      } else {
        array_push($errors, "Invalid Username or Password");
      }
    } else {
      array_push($errors, 'Account Locked. <a href="enter_email">Click to reset your password.</a>');
    }
  }
}

/*
  Accept email of user whose password is to be reset
  Send email with temp password for user to reset their password
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
  // generate a unique random token of length 16
  $token = bin2hex(random_bytes(8));

  if (count($errors) == 0) {
    // store token in the password-reset database table against the user's email
    $sql = "INSERT INTO password_reset(email, token) VALUES ('$email', '$token')";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $stmt = null;

    // Send email to user with their temporary password using PHPMailer
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
    $content = "Hi there, from the Test Scheduling Suite team! Here is your temporary password: " . $token ;
    $mail->MsgHTML($content);
    $mail->IsHTML(true);
    $mail->send();
    header('location: new_password');
  }
}

// ENTER A NEW PASSWORD
if (isset($_POST['new_password'])) {
  $temp_pass = $_POST['temp_pass'];
  $new_pass = $_POST['new_pass'];
  $new_pass_c = $_POST['new_pass_c'];

  if (empty($temp_pass) || empty($new_pass) || empty($new_pass_c)) array_push($errors, "Password is required");
  if ($new_pass !== $new_pass_c) array_push($errors, "Passwords do not match");
  if (count($errors) == 0) {
    // Get the email address where the temp_password matches the token in password_reset table
    $sql = "SELECT email FROM password_reset WHERE token='$temp_pass' LIMIT 1";
    $result = $db->query($sql)->fetch();
    $email = $result['email'];

    $sql = "SELECT username FROM user WHERE email = '$email'";
    $result = $db->query($sql)->fetch();
    $username = $result['username'];

    if ($email) {
      $new_pass = password_hash($new_pass,PASSWORD_DEFAULT);
      $sql = "UPDATE user SET password='$new_pass', login_attempts = 0 WHERE email='$email[0]'";
      $stmt = $db->prepare($sql);
      $stmt->execute();
      clearResetTokens($username);
      $_SESSION['username'] = $username;
      $_SESSION['success'] = "You are now logged in";
      header('location: index');
    }
  }
}

/**
 * Get the number of failed login attempts for the current username trying to log in.
 * 
 * @param {String} username The user.
 * @return {Int} The number of failed attempts. Null if username doesn't exist.
 */
function getAttempts($username) {
  $db = ConnectDB();
  $sql = "SELECT login_attempts FROM user WHERE username = '$username'";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetch();
  return $result[0];
}

/**
 * Removes any reset tokens in the password_reset database for the provided user
 * on successful login or on new password creation.
 * 
 * @param {String} username The user
 */
function clearResetTokens($username){
  $db = ConnectDB();
  $sql = "SELECT email FROM user WHERE username = '$username'";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetch();
  $email = $result['email'];
  $sql = "DELETE FROM password_reset WHERE email = '$email'";
  $stmt = $db->prepare($sql);
  $stmt->execute();
}
?>