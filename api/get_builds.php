
<?php

#gets the list of builds from the database returns to
#client in json format.
#Author: David Serrano (serranod7)


if(!include_once('./validate_func.php'))
{
    die('error finding validate file');
}

if(!include_once('../Utils/connect.php'))
{
    die('error finding connect file');
}


try{
    if(isset($_POST['username']) && isset($_POST['password'])){

        if(validate($_POST['username'], $_POST['password'])){
    
            $dbh = ConnectDB();
	          $sql = "SELECT * FROM build ";
            
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            
            echo json_encode($stmt->fetchAll());
        }
        else{
            echo '{"validation":false}';
        }
    }else{
        echo '{"validation":false}';
    }
}catch(\Error $e){
    echo $e->getMessage();
};
?>
