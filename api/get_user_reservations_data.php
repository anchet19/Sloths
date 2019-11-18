<?php

  if  (!include('../Utils/connect.php')) {
    die('error finding connect file');		
  }

  $dbh = ConnectDB();	  //connects to mySQL
  try{
    if(!isset($_POST['username'])){
      exit(400);
    }
    $username = $_POST['username'];
    $max_strlen = 200;
    // Query to get all users reservation information
    // |reserve_id|date|desktop|build|startTime|outcome|comment
    $sql = "SELECT reserve_id as id, t.date, d.name AS desktop, b.name AS build, "
            ."TIME_FORMAT(t.start_time, \"%r\") AS time, outcome, comment FROM reservation "
            ."JOIN user USING (user_num) "
            ."JOIN timeslot t USING (slot_id) "
            ."JOIN desktop d USING (dtop_id) "
            ."JOIN build b using (b_num) "
            ."LEFT JOIN feedback USING(reserve_id) "
            ."where username = '$username' " 
            ."order by date desc, time desc";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    // Get the column headings for hydration
    $headings = array();
    foreach(range(0, $stmt->columnCount() -1) as $col_index){
      $meta = $stmt->getColumnMeta($col_index);
      if ($meta['name'] != 'reserve_id'){
        array_push($headings, strtoupper($meta['name']));
      }
    }
    
    // Prepare the table data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $out = array("headings" => $headings, "data" => $data);
    echo json_encode($out);
  }
  catch(Exception $e){}
?>