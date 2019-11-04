<?php
#Inserts a build into database using name from
#post parameters
#authors: Cassandra Bailey, David Serrano (serranod7)



include_once("../Utils/connect.php");
include_once("./validate_func.php");
include_once("./get_user_func.php");

try{
    $dbh = ConnectDB();
    if(isset($_POST['build']) && isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $name = $_POST['build'];
        
        if(validate($username, $password)){
            $userData = getUser($username, $password);
            
            if($userData['admin'] == 2){
                $sql = "INSERT INTO build (name) VALUES('$name')";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                
                echo '{"result":true}';
            }else{
                echo '{"result":false}';
            }
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
