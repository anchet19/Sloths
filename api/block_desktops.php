<?php 
/**
 * Given a single date, array of desktop ids, and range of times [start,end) (inclusive both ends)
 * this script will insert an entry into the maintenance table for each possible outcome.
 * 
 * author: Chris Ancheta
 * created: 2019-11-20
 */
  include_once('../Utils/connect.php');
  if(isset($_POST['desktop-select'], $_POST['startTime-select'], 
    $_POST['endTime-select'], $_POST['date-select']))
  {
    $db = ConnectDB();
    $date = $_POST['date-select'];
    $start = $_POST['startTime-select'];
    $end = $_POST['endTime-select'];
    $comment = $_POST['comment'];
    // Get the slot_id's associated with the different date/start_time combinations
    $sql = "SELECT slot_id FROM timeslot WHERE date='$date' AND start_time BETWEEN '$start' AND '$end'";
    $timeslots = $db->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    $sql = '';
    // Insert a new entry for each desktop + slot_id combo
    foreach($_POST['desktop-select'] as $desktop){
      foreach($timeslots as $timeslot){
        $sql = "INSERT INTO maintenance (slot_id, dtop_id) VALUES($timeslot,$desktop)";
        $db->query($sql);
      }  
    }
    echo "Desktops added to the maintenance schedule.";
  } else {
    echo "Error";
  }
?>