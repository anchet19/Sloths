
<?php

#gets the installations in database
#returns in json format.
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
            $username = $_POST['username'];
            $dbh = ConnectDB();
	          $sql = "SELECT installation.install_id AS installID, "; 
	          $sql .= "installation.dtop_id AS dtopID, ";
	          $sql .= "desktop.name AS dtopName, ";
	          $sql .= "installation.b_num AS bID, ";
	          $sql .= "build.name AS buildName ";
	          $sql .= "FROM installation ";
	          $sql .= "INNER JOIN desktop ON ";
	          $sql .= "installation.dtop_id=desktop.dtop_id ";
	          $sql .= "INNER JOIN build ON "; 
              $sql .= "installation.b_num=build.b_num ";
              $sql .= "INNER JOIN privilege on desktop.dtop_id = privilege.dtop_id ";
              $sql .= "WHERE privilege.user_num = (SELECT user_num FROM user WHERE username = '$username') ";
	          $sql .= "ORDER BY desktop.name";
            
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            
            echo json_encode($stmt->fetchAll());
        }
        else{
            echo '{"validation1":false}';
        }
    }else{
        echo '{"validation2":false}';
    }
}catch(\Error $e){
    echo $e->getMessage();
};
?>
