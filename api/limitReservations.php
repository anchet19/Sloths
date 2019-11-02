
<?php

#makes sure the user requesting a timeslot has not exceeded the allowed number for the week they are requesting in
#author: Cassandra Bailey


        if  (!include('../Utils/connect.php')) {
                die('error finding connect file');
        }

        $dbh = ConnectDB();
?>

<?php

	$formatMon = $_POST['formatMon'];
	$formatSun = $_POST['formatSun'];
	$date = $_POST['date'];
	$user = $_POST['user'];

	
	#cycles for limiting timeslots go from 6AM Monday and end at 9PM Sunday night (inclusive)
	
	#FOR EXAMPLE: 
		#if the timeslot requested is 9 AM on Tuesday and it is represented by slot_id 8

		#the minimum timeslot in this range would be Monday at 6 AM and represented by slot_id 1
		
		#the maximum timeslot in this range would be Sunday at 9 PM and represented by slot_id 42



	#this query gets the minimum slot_id from the list of timeslots where the date falls between the Monday before and
        #the Sunday after; this is possible because slot_ids are in sequential order

	$sql  = "SELECT MIN(slot_id) FROM timeslot WHERE date BETWEEN '$formatMon' AND '$formatSun'";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$minSlot = $stmt->fetch();
	$stmt = null;	



	#this query gets the maximum slot_id from the list of timeslots where the date falls between the Monday before and
	#the Sunday after

	$sql  = "SELECT MAX(slot_id) FROM timeslot WHERE date BETWEEN '$formatMon' AND '$formatSun'";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$maxSlot = $stmt->fetch();
	$stmt = null;



	#this query gets the number of reservation the current user has within the selected cycle
	#if they have 3 already, they are not allowed to reserve another and 0 will be returned
	#if they have 0-2, they are allowed to reserve another and 1 will be returned
	
	$sql  = "SELECT COUNT(*) FROM reservation WHERE user_num = '$user' ";
	$sql .= "AND slot_id >= '$minSlot[0]' AND slot_id <= '$maxSlot[0]'";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$count = $stmt->fetch();

	if($count[0] == null || $count[0] < 3){
		echo "1";
	}
	else{
		echo "0";
	}
?>
