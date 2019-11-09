<?php
#Takes privilege selections from the client and saves them to the database
#Author: Alex Cross



include_once("../Utils/connect.php");

    $dbh = ConnectDB();
    if(isset($_POST['user']) && isset($_POST['checkboxes'])){
        $user = $_POST['user'];
        $checkboxes = json_decode($_POST['checkboxes']); #decode stringified json into php array

        $sql = "CALL delete_user_privileges($user)"; #delete all user privileges from database
        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        foreach($checkboxes as $item){ #iterate through array - run sql procedure for each item into the database
            $sql = "CALL create_user_privilege('$item', $user) ";
            $stmt = $dbh->prepare($sql);
            $var = $stmt->execute();
            
        }
        echo '{"result":true}';     #json response to create succeed/fail popup
    }else{
            echo '{"result":false}';
        }

?>
