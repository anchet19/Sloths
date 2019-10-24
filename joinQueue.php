
<?php

#inserts a row into the queue table with the user_num, slot_id, dtop_id, and the current wait position in the queue
#author: Cassandra Bailey
#modified: Alex Cross

        if  (!include('connect.php')) {
                die('error finding connect file');
        }

        $dbh = ConnectDB();
/* ?>

<?php */


	$actualWait = 0;
	$curr = $_POST['curr'];
	$date = $_POST['date'];
	$time = $_POST['time'];
	$desktop = $_POST['desktop'];

	
	#gets the slot_id for the date and start_time provided

	$sql  = "SELECT slot_id FROM timeslot ";
	$sql .= "WHERE date = '$date' AND start_time = '$time'";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$slot = $stmt->fetch();
	$stmt = null;
	

	#gets the wait_position for the user, timeslot, and desktop provided
	#if null, the user is not currently in the queue and is able to join
	#if not null, the user is already in the queue for the desktop in that timeslot and cannot join the queue again	

	$sql =  "SELECT wait_position FROM queue WHERE dtop_id = '$desktop' ";
	$sql .= "AND slot_id = '$slot[0]' AND user_num = '$curr'";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$currPos = $stmt->fetch();
	$stmt = null;

	if($currPos[0] == null){
		
		
		#inserts the reservation in the queue				

                $sql  = "INSERT INTO queue (dtop_id, slot_id, wait_position, user_num,request_time) ";
                $sql .= "VALUES ('$desktop', '$slot[0]', '$actualWait', '$curr',(select now()))";
                $stmt = $dbh->prepare($sql);
				$stmt->execute();
				
				$sql = "CALL set_last_request($curr);";
				$stmt = $dbh->prepare($sql);
				$stmt->execute();


                $row  = "You have joined the queue for desktop " . $desktop . " at " . $time . " on " . $date;
                //$row .= ". Number of people in front of you: " . $numInLine;
                echo $row;
	}
	else{
		echo "You are already in the queue for desktop " . $desktop . " at " . $time . " on " . $date;
	}
?>
