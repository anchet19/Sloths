
<?php
#Backend file to validate user information
#Author: David Serrano (serranod7)

if(!include_once('../Utils/connect.php'))
{
  die('error finding connect file');
}

function validate($username, $password){
  $dbh = ConnectDB();
  $sql = "SELECT COUNT(*) FROM user ";
  $sql .= "WHERE username = '$username'";
  
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $rowcount = $stmt->fetch();
  if(!$rowcount[0] == 0){
    $sql = "SELECT password FROM user WHERE ";
    $sql .= "username = '$username'";
    
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $pwd = $stmt->fetch();
    
    if (password_verify($password, $pwd[0])){
      $sql2 = "UPDATE user SET login_attempts = 0 WHERE username = '$username';";
      $stmt = $dbh->prepare($sql2);
      $stmt->execute();
      return true;
    }
    else {
      $sql2 = "UPDATE user SET login_attempts = (login_attempts + 1) WHERE username = '$username';";
      $stmt = $dbh->prepare($sql2);
      $stmt->execute();
      return false;
    }
  }else{
    return false;
  }
}

function getAttempts($username) {
  $dbh = ConnectDB();
  $sql = "SELECT login_attempts FROM user WHERE username = '$username'";
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetch();
  return $result[0];
}
?>
