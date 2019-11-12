<?php

#Takes the user_num from a post request and removes it from
#the user table in the database; it also removes all
#reservations with the user_num
#authors: Cassandra Bailey, David Serrano (serranod7)


include_once("../Utils/connect.php");
include_once("./validate_func.php");
include_once("./get_user_func.php");

try{
  $dbh = ConnectDB();
  if(isset($_POST['user']) && isset($_POST['username'])){
    $username = $_POST['username'];
    $user = $_POST['user'];  
    $userData = json_decode(getUser($username));
          
    if($userData->{'admin'} == 2){
      $sql  = "DELETE FROM reservation WHERE user_num = '$user'";
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $stmt=null;
      $sql = "DELETE FROM user WHERE user_num = $user";
      $stmt = $dbh->prepare($sql);
      $success = $stmt->execute();
                      
      echo '{"result":true}';
    }else{
      echo '{"result":false}';
    }
  }else{
    echo '{"result":false}';
  }
}catch(\Error $e){
    echo $e->getMessage();
}




?>
