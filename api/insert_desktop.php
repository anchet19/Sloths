
<?php
#Adds a desktop to the database. uses desktop name
#from post parameters
#authors: Cassandra Bailey


include_once("../Utils/connect.php");
include_once("./validate_func.php");
include_once("./get_user_func.php");

try{
    $dbh = ConnectDB();
    if(isset($_POST['desktop']) && isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $name = $_POST['desktop'];
        
        if(validate($username, $password)){
            $userData = getUser($username, $password);
            
            if($userData['admin'] == 2){
                $sql = "INSERT INTO desktop (name) VALUES('$name')";
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
