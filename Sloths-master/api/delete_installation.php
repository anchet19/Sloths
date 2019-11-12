<?php

#takes the b_num and dtop_id values from post request
#, the installation between the two
#is removed - row with the corresponding dtop_id and b_num
#authors: Cassandra Bailey, David Serrano (serranod7)


include_once("../Utils/connect.php");
include_once("./validate_func.php");
include_once("./get_user_func.php");

try{
  $dbh = ConnectDB();
  if(isset($_POST['build']) && isset($_POST['username']) && isset($_POST['desktop'])){
    $username = $_POST['username'];
    $build = $_POST['build'];
    $desktop = $_POST['desktop'];
      

    $userData = json_decode(getUser($username));
          
    if($userData->{'admin'} == 2){
        $build = $_POST['build'];
        $desktop = $_POST['desktop'];

        $sql  = "DELETE FROM installation ";
        $sql .= "WHERE dtop_id = $desktop ";
        $sql .= "AND b_num = $build";
        
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
    echo $e->getMessage();
}
?>
