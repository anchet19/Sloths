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
    if(isset($_POST['desktop']) && isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $desktop = $_POST['desktop'];
        
        if(validate($username, $password)){
            $userData = getUser($username, $password);
            
            if($userData['admin'] == 1){
                $sql  = "DELETE FROM installation WHERE dtop_id = $desktop";
   
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                $stmt = null;
                
                $sql  = "DELETE FROM reservation WHERE dtop_id = $desktop";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                $stmt = null;
                
                $sql2 = "DELETE FROM desktop WHERE dtop_id = $desktop";
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
