<?php
#inserts a row into the queue table with the user_num, slot_id, dtop_id, and the current wait position in the queue
#author: Cassandra Bailey
#modified: Alex Cross
  if  (!include('../Utils/connect.php')) {
          die('error finding connect file');
  }
  $dbh = ConnectDB();

	$actualWait = 0; #hardcoded to skirt outdated use
	$curr = $_POST['curr'];
	$date = $_POST['date'];
	$time = $_POST['time'];
	$build = $_POST['build'];
	$desktop = $_POST['desktop'];
	
	#gets the slot_id for the date and start_time provided
	$sql  = "SELECT slot_id FROM timeslot ";
	$sql .= "WHERE date = '$date' AND start_time = '$time'";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$slot = $stmt->fetch();
	$stmt = null;

  /**
   * Checks to see if the user already has a request for that timeslot.
   * If not, add them to the queue
   */
	$sql = "SELECT count(*) as count from queue where slot_id = '$slot[0]' AND user_num = '$curr'";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$currPos = $stmt->fetch();
  $stmt = null;
  
	if($currPos[0] == 0){
		#inserts the request into the queue				
    $sql  = "INSERT INTO queue (dtop_id, b_num, slot_id, wait_position, user_num, request_time) ";
    $sql .= "VALUES ('$desktop', '$build', '$slot[0]', '$actualWait', '$curr',(select now()))";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    
    $sql = "CALL set_last_request($curr);";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $row  = "You have joined the queue for desktop " . $desktop . " at " . $time . " on " . $date;

    echo $row;
	}
	else{
		#echo "You are already in the queue for desktop " . $desktop . " at " . $time . " on " . $date;
		echo "A request for this timeslot already exists for this user.";
	}
?>