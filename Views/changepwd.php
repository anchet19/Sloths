<?php
# Allows the user to change password to a new one given they 
# are already a user. To change the passowrd, the user must provide the username,password,and new password.
# Authors:Eric DeAngelis,Riddhi Patel, Kyle Kaminski

if  (!include('../Utils/connect.php')) {
    die('error finding connect file');
}
$dbh = ConnectDB();
$errors = [];
try{
  if(isset($_POST['change_pwd'])){
    $username = $_POST['user_name'];
    $currentpwd = $_POST['old_password'];
    $newpwd = $_POST['new_password'];
    $confirmedpwd = $_POST['confirm_pwd'];
    
    if($username == "" || $currentpwd == "" || $newpwd == "" ||$confirmedpwd == ""){
      array_push($errors, "All fields are required.");
    }
    elseif($username != $_SESSION['username']){
      array_push($errors, "Invalid username");
    }
    elseif($newpwd != $confirmedpwd) {
      array_push($errors, "Passwords do not match.");
    }
    else {
      #Checks to see if the username is in the list; if 0, it does not occur and is invalid		
      $sql  = "SELECT COUNT(*) FROM user ";
      $sql .= "WHERE username = '$username'";
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $rowcount = $stmt->fetch();
      $stmt = null;
      if($rowcount[0] == 0){
        array_push($errors, "Invalid Username or Password.");
      }
      else{
        #checks to see if the current password is correct
        $sql  = "SELECT password FROM user WHERE username = '$username'";
        $pwd = $dbh->query($sql);
        $stmt = null;

        //De - hash old password
        $check_hashedpwd = password_verify($currentpwd, $pwd);
  
        if(!$check_hashedpwd && $check_hashedpwd){
          array_push($errors, "Invalid Username or Password.");
        }
        else {
          //Hash password
          $hashed_newpwd = password_hash($newpwd,PASSWORD_DEFAULT);
          #if the old password was correct, the password is updated to the new one
          $sql = "UPDATE user SET password='$hashed_newpwd' WHERE username='$username'";
          $stmt = $dbh->prepare($sql);
          $stmt->execute();
          header('location: dashboard');
          $stmt = null;
        }
      }		
    }  
	}
}
catch(Exception $e)
{
    echo 'error';
}
?>

