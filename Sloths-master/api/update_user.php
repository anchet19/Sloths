<?php
#Edits the admin boolean of a user. User ID and admin status
#provided by post parameters
#Author: David Serrano (serranod7)

include_once("../Utils/connect.php");
include_once("./get_user_func.php");

try{
  $dbh = ConnectDB();
  if(isset($_POST['user']) && isset($_POST['admin']) && isset($_POST['username'])){
    $username = $_POST['username'];
    $user = $_POST['user'];
    $admin = $_POST['admin'];
    $userData = json_decode(getUser($username));
    if($userData->{"admin"} == 2){
      $sql = "UPDATE user ";
      $sql .= "SET admin = '$admin' ";
      $sql .= "WHERE user_num = '$user'";

      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      
      echo '{"result":true}';
    }else{
      echo '{"result":false}';
    }
  }else{
    echo '{"result":false}';
  }
    
}catch(\Error $e){
  echo json_encode($e->getMessage());
}
?>
