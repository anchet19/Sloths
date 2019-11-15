
<?php

#gets the list of builds from the database returns to
#client in json format.
#Author: David Serrano (serranod7)


if(!include_once('./validate_func.php'))
{
    die('error finding validate file');
}

if(!include_once('../Utils/connect.php'))
{
    die('error finding connect file');
}


try {
  $dbh = ConnectDB();
  $sql = "SELECT * FROM build where active_bit = 1 ORDER BY name"; #crossmod
  
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  
  echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}catch(\Error $e){
    echo $e->getMessage();
};
?>
