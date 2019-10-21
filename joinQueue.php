
<?php

#inserts a row into the queue table with the user_num, slot_id, dtop_id, and the current wait position in the queue
#author: Cassandra Bailey

        if  (!include('connect.php')) {
                die('error finding connect file');
        }

        $dbh = ConnectDB();
?>

<?php

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


		#gets the largest wait position value for the desktop in the timeslot provided

		$sql  = "SELECT MAX(wait_position) FROM queue WHERE slot_id = '$slot[0]' AND dtop_id = '$desktop'";
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
		$maxWait = $stmt->fetch();
		$stmt = null;
		

		#if the query returns null, then no one is in the queue for the desktop in the timeslot provided
		#the first wait position value is automatically set to 0
	
		if($maxWait[0] == null){
			$actualWait = 0;
		}


		#if the query does not return null, then one or more users are already in the queue for the desktop in the 
		#timeslot provided; the current user will be inserted at the back of the queue with a wait position 1 greater
		#than the maximum wait position

		else{
			$actualWait = $maxWait[0] + 1;
		}


		#checks to see how many people are waiting in the queue for the desktop at the timeslot provided
		#used in the echo statement 

		$sql  = "SELECT COUNT(*) FROM queue WHERE slot_id = '$slot[0]' AND dtop_id = '$desktop'";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                $numInLine = $stmt->fetch();
                $stmt = null;
		
		
		#inserts the reservation in the queue				

                $sql  = "INSERT INTO queue (dtop_id, slot_id, user_num, wait_position) ";
                $sql .= "VALUES ('$desktop', '$slot[0]', '$curr', '$actualWait')";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();


                $row  = "You have joined the queue for desktop " . $desktop . " at " . $time . " on " . $date;
                echo $row;
	}
	else{
		echo "You are already in the queue for desktop " . $desktop . " at " . $time . " on " . $date;
	}
?>
