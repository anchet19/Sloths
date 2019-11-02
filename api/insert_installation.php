

<?php
#Adds an installation to database using desktop ID and
#and build ID from post parameters
#authors: Cassandra Bailey, David Serrano (serranod7)


include_once("../connect.php");
include_once("./validate_func.php");
include_once("./get_user_func.php");

try{
    $dbh = ConnectDB();
    if(isset($_POST['build']) && isset($_POST['desktop']) && isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $build = $_POST['build'];
        $desktop = $_POST['desktop'];
        
        if(validate($username, $password)){
            $userData = getUser($username, $password);
            
            if($userData['admin'] == 1){
                $sql = "INSERT INTO installation ";
                $sql .= "(dtop_id, b_num) ";
                $sql .= "VALUES ";
                $sql .= "('$desktop', '$build')";
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
