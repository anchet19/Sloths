
<?php
#Adds a desktop to the database. uses desktop name
#from post parameters
#authors: Cassandra Bailey


include_once("../Utils/connect.php");
include_once("./validate_func.php");
include_once("./get_user_func.php");

try{
  $dbh = ConnectDB();
  if(isset($_POST['desktop'], $_POST['username'], $_POST['color'])){
    $username = $_POST['username'];
    $name = $_POST['desktop'];
    $color = $_POST['color'];
    $userData = json_decode(getUser($username));
          
    if($userData->{'admin'} == 2){
      $sql = "INSERT INTO desktop (name, color) VALUES('$name', '$color')";
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
