<?php

#Takes the dtop_id from post request and removes it from
#the desktop table in the database; it also removes all 
#reservations and installation with the dtop_id
#authors: Cassandra Bailey, David Serrano (serranod7)


include_once("../Utils/connect.php");
include_once("./validate_func.php");
include_once("./get_user_func.php");

try{
  $dbh = ConnectDB();
  if(isset($_POST['desktop']) && isset($_POST['username'])){
    $username = $_POST['username'];
    $desktop = $_POST['desktop'];
    $userData = json_decode(getUser($username));
          
    if($userData->{'admin'} == 2){
        $sql  = "DELETE FROM installation WHERE dtop_id = $desktop";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $stmt = null;
        
        $sql2 = "UPDATE desktop SET active_bit = 0 WHERE dtop_id = $desktop"; #crossmod
        $stmt = $dbh->prepare($sql2);
        $stmt->execute();
        
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
