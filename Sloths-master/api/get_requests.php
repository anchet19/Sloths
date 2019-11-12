<?php
#returns the current requests in json
#format that full calendar requires
#Author: Chris Ancheta
#Modified 2019-10-04

header("Content-Type: application/json");

if(!include('../Utils/connect.php')){
    die('error retrieving connect.php');
}
$dbh = ConnectDB();


try {

  # Query the database grouping timeslots of the same time and date together to count
  # how many there are for display.
  # 
  $sql = "SELECT queue.slot_id AS slotID,\n"
    . "timeslot.date AS reserveDate,\n"
    . "timeslot.start_time AS reserveTime,\n"
    . "desktop.name AS dtopName,\n"
    . "queue.dtop_id AS dtop_num,\n"
    . "queue.user_num AS userID,\n"
    . "queue.b_num AS build_num,\n"
    . "build.name AS buildName,\n"
    . "GROUP_CONCAT(user.first_name, \" \", user.last_name) AS names,\n"
    . "GROUP_CONCAT(user.username) users,\n"
    . "COUNT(queue.slot_id) slotcount\n"
    . "FROM queue\n"
    . "INNER JOIN user ON\n"
    . "queue.user_num = user.user_num\n"
    . "INNER JOIN desktop ON\n"
    . "queue.dtop_id = desktop.dtop_id\n"
    . "INNER JOIN timeslot ON\n"
    . "queue.slot_id = timeslot.slot_id\n"
    . "INNER JOIN build ON\n"
    . "queue.b_num = build.b_num\n"
    . "GROUP BY slotID, dtop_num";
  
  $stmt = $dbh->prepare($sql);
  $stmt->execute();

  # Transforms the queried data into a JSON string to be used as a fullcalendar Event Object
  $out = '';
  foreach($stmt->fetchAll() as $row){
            $out .= '{"id":"'.$row['dtop_num'].'",';
            $out .= '"buildName":"'.$row['buildName'].'",';
            $out .= '"title":"Requests: '.$row['slotcount'].'",';
            $out .= '"start":"'.$row['reserveDate'].'T'.$row['reserveTime'].'-04:00'.'",';
            $out .= '"end":"'.$row['reserveDate'].'T'.$row['reserveTime'].'-1:00'.'",';
            $out .= '"user":"'.$row['userID'].'",';
            $out .= '"buildID":"'.$row['build_num'].'",';
            $out .= '"names":"'.$row['names'].'",';
            $out .= '"usernames":"'.$row['users'].'",';
            $out .= '"date":"'.$row['reserveDate'].'",';
            $out .= '"time":"'.$row['reserveTime'].'",';
            $out .= '"className":"request"},';       
  }

  echo '['.substr($out, 0, -1).']' ;

} catch(Exception $e){
    die($e);
}

?>
