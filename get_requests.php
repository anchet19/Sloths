<?php
#returns the current reservations in json
#format that full calendar requires
#Author: David Serrano (serranod7)
header("Content-Type: application/json");

if(!include('../connect.php')){
    die('error retrieving connect.php');
}
$dbh = ConnectDB();


try {

  // $slot_id = $_GET['slot_id'];
  // $dtop_id = $_GET['dtop_id'];

  $sql = "SELECT COUNT(queue.qid) AS count, ";
  $sql .= "queue.slot_id AS slotID, ";
  $sql .= "timeslot.date AS reserveDate, ";
  $sql .= "timeslot.start_time AS reserveTime, ";
  $sql .= "desktop.name AS dtopName, ";
  $sql .= "queue.dtop_id AS dtop_num, ";
  $sql .= "queue.user_num AS userID, ";
  $sql .= "user.username AS username, ";
  $sql .= "user.first_name AS firstName, ";
  $sql .= "user.last_name AS lastName ";
  $sql .= "FROM queue ";
  $sql .= "INNER JOIN user ON ";
  $sql .= "queue.user_num = user.user_num ";
  $sql .= "INNER JOIN desktop ON ";
  $sql .= "queue.dtop_id = desktop.dtop_id ";
  $sql .= "INNER JOIN timeslot ON ";
  $sql .= "queue.slot_id = timeslot.slot_id ";
  $sql .= "GROUP BY slotID";
  
  $stmt = $dbh->prepare($sql);
  $stmt->execute();

  $out = '';
  foreach($stmt->fetchAll() as $row){
            $out .= '{"id":"'.$row['dtop_num'].'",';
            $out .= '"title":"Requests: '.$row['count'].'",';
            $out .= '"start":"'.$row['reserveDate'].'T'.$row['reserveTime'].'-04:00'.'",';
            $out .= '"end":"'.$row['reserveDate'].'T'.$row['reserveTime'].'-1:00'.'",';
            $out .= '"user":"'.$row['userID'].'",';
            $out .= '"date":"'.$row['reserveDate'].'",';
            $out .= '"time":"'.$row['reserveTime'].'",';
            $out .= '"name":"'.$row['firstName'].' '.$row['lastName'].'"},';
            
  }

  echo '['.substr($out, 0, -1).']' ;

} catch(Exception $e){
    die($e);
}

?>
