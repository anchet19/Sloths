<?php 
/**
 * Query the database to get information needed for display on the calendar of 
 * Desktop systems on the maintenance (block) list.
 * 
 * author: Chris Ancheta
 * created: 2019-11-20
 */

  include_once('../Utils/connect.php');
  $db = ConnectDB();

  // Table returned is in this form
  // |name|dtop_id|date|start_time|comment|buildID(comma delimited string of b_nums)
  $sql = "SELECT desktop.name as dtopName, maintenance.dtop_id as id, "
      . "timeslot.date as date, timeslot.start_time as time, maintenance.comment, "
      . "GROUP_CONCAT(installation.b_num) as buildID "
      . "FROM maintenance "
      . "INNER JOIN desktop ON maintenance.dtop_id = desktop.dtop_id "
      . "INNER JOIN timeslot ON maintenance.slot_id = timeslot.slot_id "
      . "JOIN installation ON maintenance.dtop_id = installation.dtop_id "
      . "GROUP BY maintenance.slot_id";

  $result = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  $out = array();
  // Add the necessary fields for FullCalendar to display the event
  foreach($result as $row) {
    $row += ['end' => $row['date'].'T'.$row['time'].'-01:00'];
    $row += ['start' => $row['date'].'T'.$row['time'].'-01:00'];
    $row += ['title' => 'BLOCKED'];
    array_push($out, $row);
  }
  echo json_encode($out);
?>