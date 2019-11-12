<?php 
/**
 * Inserts a new item into the feedback table of the data
 */
  include_once('../Utils/connect.php');

  $db = ConnectDB();
  if(isset($_POST['outcome'], $_POST['comment'], $_POST['reservation'])){
    $outcome = $_POST['outcome'];
    $comment = $_POST['comment'];
    $reserve_id = $_POST['reservation'];
    $sql = "INSERT INTO feedback (reserve_id, outcome, comment) VALUES('$reserve_id', '$outcome', '$comment');";
    $result = $db->query($sql);
  }
?>