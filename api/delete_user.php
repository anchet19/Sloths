<?php

#Takes the user_num from a post request and removes it from
#the user table in the database; it also removes all
#reservations with the user_num
#authors: Cassandra Bailey, David Serrano (serranod7)


include_once("../connect.php");
include_once("./validate_func.php");
include_once("./get_user_func.php");

try{
    $dbh = ConnectDB();
    if(isset($_POST['user']) && isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = $_POST['user'];
        
        if(validate($username, $password)){
            $userData = getUser($username, $password);
            
            if($userData['admin'] == 1){
                
                $sql  = "DELETE FROM reservation WHERE user_num = '$user'";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                $stmt=null;
                $sql = "DELETE FROM user WHERE user_num = $user";
                $stmt = $dbh->prepare($sql);
                $success = $stmt->execute();
                                
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
