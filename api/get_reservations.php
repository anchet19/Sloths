<?php
#returns the current reservations in json
#format that full calendar requires
#Author: David Serrano (serranod7)
header("Content-Type: application/json");

if(!include('../Utils/connect.php')){
    die('error retrieving connect.php');
}
$dbh = ConnectDB();

try{
    if (TRUE){
        $sql  = "SELECT reservation.reserve_id AS reserveID, ";
        $sql .= "reservation.slot_id AS slotID, ";
        $sql .= "timeslot.date AS reserveDate, ";
        $sql .= "timeslot.start_time AS reserveTime, ";
        $sql .= "desktop.name AS dtopName, ";
        $sql .= "reservation.dtop_id AS dtop_num, ";
        $sql .= "reservation.user_num AS userID, ";
        $sql .= "user.username AS username, ";
        $sql .= "user.first_name AS firstName, ";
        $sql .= "user.last_name AS lastName ";
        $sql .= "FROM reservation ";
        $sql .= "INNER JOIN user ON ";
        $sql .= "reservation.user_num = user.user_num ";
        $sql .= "INNER JOIN desktop ON ";
        $sql .= "reservation.dtop_id = desktop.dtop_id ";
        $sql .= "INNER JOIN timeslot ON ";
        $sql .= "reservation.slot_id = timeslot.slot_id ";
        $sql .= "ORDER BY timeslot.date";

        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        $out = "";
        
	      foreach($stmt->fetchAll() as $row){
            $out .= '{"id":"'.$row['dtop_num'].'",';
            $out .= '"title":"'.$row['firstName'].' '.$row['lastName'].'",';
            $out .= '"start":"'.$row['reserveDate'].'T'.$row['reserveTime'].'-04:00'.'",';
            $out .= '"end":"'.$row['reserveDate'].'T'.$row['reserveTime'].'-1:00'.'",';
            $out .= '"user":"'.$row['userID'].'",';
            $out .= '"date":"'.$row['reserveDate'].'",';
            $out .= '"time":"'.$row['reserveTime'].'",';
            $out .= '"username":"'.$row['username'].'"},';
            
        }
            
        echo '['.substr($out, 0, -1).']' ;
    }
}
catch(Exception $e){
    die($e);
}


?>
