<?php

#gets the list of users from the database
#returns to client in json format

include_once("../Utils/connect.php");
include_once("./validate_func.php");
include_once("./get_user_func.php");

try{
  $dbh = ConnectDB();
  if(isset($_POST['username'])){
    $username = $_POST['username'];
    if(isset($_POST['department'])){
      $department = $_POST['department'];
    }
    $userData = json_decode(getUser($username));
    
    if($userData->{'admin'} > 0){
      if($userData->{'admin'} == 2){
      $sql = "SELECT user_num,first_name,last_name,username,email ";
      $sql .= "FROM user ORDER BY username ";
      }
      else{
      $sql = "SELECT user_num,first_name,last_name,username,email ";
      $sql .= "FROM user WHERE department_id = ".$department." ORDER BY username ";
      }
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
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
