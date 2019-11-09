<?php

#gets the list of users from the database
#returns to client in json format

include_once("../Utils/connect.php");
include_once("./validate_func.php");
include_once("./get_user_func.php");

try{
  $dbh = ConnectDB();
  // if(isset($_POST["username"])){
    $username = 'bill';//$_POST["username"];
    $sql = "SELECT user_num,first_name,last_name,username,email ";
    $sql .= "FROM user ";

    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
  // }

}catch(\Error $e){
  echo $e->getMessage();
}
?>
