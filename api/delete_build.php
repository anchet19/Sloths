<?php

#Takes the build_num from post request and removes it from
#the build table in the database; it also removes all
#installations with the dtop_id
#Authors: Cassandra Bailey, David Serrano (serranod7)



include_once("../Utils/connect.php");
include_once("./validate_func.php");
include_once("./get_user_func.php");

try{
    $dbh = ConnectDB();
    if(isset($_POST['build']) && isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $b_num = $_POST['build'];
        
        if(validate($username, $password)){
            $userData = getUser($username, $password);
            
            if($userData['admin'] == 1){
                $sql = "DELETE FROM installation WHERE b_num = $b_num";
                
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                
                $sql2 = "DELETE FROM build WHERE b_num = $b_num";
                $stmt = $dbh->prepare($sql2);
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
