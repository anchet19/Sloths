
<?php
#Edits the admin boolean of a user. User ID and admin status
#provided by post parameters
#Author: David Serrano (serranod7)



include_once("../Utils/connect.php");
include_once("./validate_func.php");
include_once("./get_user_func.php");

try{
    $dbh = ConnectDB();
    if(isset($_POST['user']) && isset($_POST['admin'])&& isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = $_POST['user'];
        $admin = $_POST['admin'];

        
        if(validate($username, $password) ){
            $userData = getUser($username, $password);
            
            if($userData['admin'] == 2){
                $sql = "UPDATE user ";
                $sql .= "SET admin = '$admin' ";
                $sql .= "WHERE user_num = '$user'";

                $stmt = $dbh->prepare($sql);
                $val = $stmt->execute();
                
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
