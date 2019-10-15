<?php
#finalizes the schedule by finding the min points,
#then moving that user, desktop, and timeslot info
#to the reservation table.
#Author: Alex Cross
header("Content-Type: application/json");
if(!include('../connect.php')){
    die('error retrieving connect.php');
}
$dbh = ConnectDB();
$dbh2 = ConnectDB();
try {
  // $slot_id = $_GET['slot_id'];
  // $dtop_id = $_GET['dtop_id'];
  $sql = 'CALL getQueue()';
  
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  
  // variables for future mySQL queries
 
  
  
  
  foreach($stmt->fetchAll() as $row){
            //vars that go into the SQL input
            $desktop = $row['dtop_id'];
            $slot =  $row['slot_id'];
            
            //var == SQL Procedure
            $min = "CALL findMinPoints(".$desktop.",".$slot.");";
            
            //echo "begin finding min user \r\n"; //testing only
            
            //find the lowest user point value for a particular desktop/slot combination
            
            //stmt2 is required to ensure first value is grabbed
            $stmt2 = $dbh2->prepare($min);
            $stmt2->execute();
            $result = $stmt2->fetch();
            $minpoints = $result['points'];
            $desktop = $result['dtop_id'];
            $slot =  $result['slot_id']; // Lines 43-44 probably unnecessary
            //SQL Procedure: Copies entry from Queue -> Reservation, then removes the entry from Queue
            $move = "CALL moveToReservation(".$minpoints.",".$desktop.",".$slot.");";
            $stmt2 = $dbh2->prepare($move);
            $stmt2->execute();
            //TODO: Add points for primetime slots, subtract points for non-primetime slots,
            //do something with the remaining queue members
            
           /*  echo $desktop ?? 'DESKTOP NOT FOUND'; //testing purposes - if value is null, outputs Not found
            echo ' ';          
            echo $slot ?? 'SLOT NOT FOUND';
            echo ' ';
            echo $minpoints ?? 'POINTS NOT FOUND';
            echo ' '; */
             
            
  }
  
} catch(Exception $e){
    die($e);
}
?>