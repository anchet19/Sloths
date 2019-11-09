<?php
#gets the user privileges from the database
#returns to client in json format
#Author: Alex Cross
include_once("../Utils/connect.php");
$dbh = ConnectDB();
if(isset($_POST['user'])){
    $user = $_POST['user'];
    $sql = "select d.name, 1 as allowed ";
    $sql .= "from privilege p ";
    $sql .= "join desktop d using (dtop_id) ";
    $sql .= "join user using (user_num) ";
    $sql .= "where username = '$user' "; # Get all desktops, marking where user has privilege
    $sql .= "UNION ";
    $sql .= "select d2.name, 0 ";
    $sql .= "from desktop d2 ";
    $sql .= "where d2.dtop_id NOT IN (select dtop_id from privilege ";
	$sql .= "join user using (user_num) where username = '$user') ";
    $sql .= "ORDER BY name; ";   
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    echo json_encode($stmt->fetchAll());
    }else{
                echo '{"result":false}';
            }
?>