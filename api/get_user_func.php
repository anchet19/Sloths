
<?php
#provides user data to other backend files
#Author: David Serrano (serranod7)

if(!include_once('./validate_func.php')){
    die('error finding validate_func file');
}
if(!include_once('../Utils/connect.php')){
    die('error finding connect file');
}

function getUser($username){
  $dbh = ConnectDB();
  $sql = "SELECT user_num, first_name, last_name, ";
  $sql .= "username, email, admin, department_id FROM user ";
  $sql .= "WHERE username='$username'";
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  return json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}

?>
