<?php
/**
 * Queries the database to get all the possible emuerated outcome values
 * of the feedback table and returns them as an array of strings in JSON format.
 * This is used to poplate the popup in the dashboard page when providing feedback.
 * 
 * author: Chris Ancheta
 * date: 2019-11-12
 */
  include('../Utils/connect.php');
  $db = ConnectDB();

  $sql = "SHOW COLUMNS FROM `feedback` LIKE 'outcome'";
  $result = $db->query($sql);
  $row = $result->fetch();
  $type = $row['Type'];
  preg_match('/enum\((.*)\)$/', $type, $matches);
  $vals = explode(',', $matches[1]);
  $headers = [];
  foreach($vals as $val){
    $trimVal = trim($val, "'");
    array_push($headers, $trimVal);
  }
  echo json_encode($headers);
?>