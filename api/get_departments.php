
<?php
#gets the list of departmetns from the database
#returns to client in json format
#Author: Jared Tebbi

if(!include_once('../Utils/connect.php'))
{
    die('error finding connect file');
}
try {
  $dbh = ConnectDB();
  $sql = "SELECT * FROM department ORDER BY name";
  
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  
  echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}catch(\Error $e){
    echo $e->getMessage();
};
?> 