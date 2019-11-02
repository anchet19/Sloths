
<?php

#gets the list of users from the database
#returns to client in json formar

include_once("../Utils/connect.php");
include_once("./validate_func.php");
include_once("./get_user_func.php");

try{
    $dbh = ConnectDB();
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if(validate($username, $password)){
            $userData = getUser($username, $password);
            
            if($userData['admin'] == 1){
                $sql = "SELECT user_num,first_name,last_name,username,email ";
                $sql .= "FROM user ";
   
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                echo json_encode($stmt->fetchAll());
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
