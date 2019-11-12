
<?php
#Returns the validated information from validate_func.php to 
#the front end in json format
#Author: David Serrano (serranod7)

if(!include_once('./validate_func.php'))
{
  die('error finding validate_func file');
}

if(!include_once('../Utils/connect.php'))
{
  die('error finding connect file');
}


try{
    if(isset($_POST['username']) && isset($_POST['password'])){
      $username = $_POST['username'];
      $password = $_POST['password'];
      $dbh = ConnectDB();
      $sql = "SELECT login_attempts FROM user WHERE username = '" . $username . "';";
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $attempts = $stmt->fetch();
      if($attempts[0] < 10){
        if (validate($username, $password)){
          echo '{"validation":true}';
        }else{
          $attempts = getAttempts($username);
            echo '{"validation": false, "attempts": ' . $attempts . '}';
        }
      } else {
        echo '{"message": "Account locked. <a href=\"enter_email.php\">Click here to reset your password</a>"}';
      }
    }
    else{
      echo '{"validation":false}';
    }
} catch(\Error $e){
    echo $e->getMessage();
}

?>
