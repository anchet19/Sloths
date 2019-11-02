
<?php


#this file contains queries for releasing an already reserved timeslot
#author: Cassandra Bailey

session_start();
// Connect to the database

if (!include('../Utils/connect.php')) {
if (!include('connect.php')) {
    die('error finding connect file');
}

$dbh = ConnectDB();
?>

<?php
	$curr = $_POST['curr'];
	$time = $_POST['time'];
	$date = $_POST['date'];
	$desktop = $_POST['desktop'];


	#this query gets the slot_id for the time and date selected

	$sql  = "SELECT slot_id FROM timeslot ";
	$sql .= "WHERE date = '$date' ";
	$sql .= "AND start_time = '$time'";

	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$slot = $stmt->fetch();
	$stmt = null;



	#this query removes the row from queue containing the provided slot_id, dtop_id, and user_num

	$sql2  = "DELETE FROM queue ";
        $sql2 .= "WHERE user_num = '$curr' ";
        $sql2 .= "AND dtop_id = '$desktop' ";
        $sql2 .= "AND slot_id = '$slot[0]'";

        $stmt = $dbh->prepare($sql2);
        $stmt->execute();
        $stmt = null;



	#This query is used to check if someone is waiting in the queue for the specified timeslot
	
	// $sql  = "SELECT MIN(wait_position) FROM queue ";
	// $sql .= "WHERE slot_id = '$slot[0]' AND dtop_id = '$desktop'";

	// $stmt = $dbh->prepare($sql);
	// $stmt->execute();
	// $waitPos = $stmt->fetch();
	// $stmt = null;



	#if the minimum wait position for the desktop and slot_id isn't null, then there is someone waiting in the queue

	// if(waitPos[0] != null){


		#retrieves the user_num from the queue for the provided slot_id and dtop_id
		#In Other Words: this retrieves the user who is first in line for this timeslot

		// $sql  = "SELECT user_num FROM queue WHERE dtop_id = '$desktop' ";
		// $sql .= "AND slot_id = '$slot[0]' AND wait_position = '$waitPos[0]'";
		// $stmt = $dbh->prepare($sql);
		// $stmt->execute();
		// $waitUser = $stmt->fetch();
		// $stmt = null;
		

		
		#inserts a row in the reservation table with the first user from the queue and the provided slot_id and dtop_id
		#IOW: automatically reserves the timeslot for the first person in the queue		

		// $sql  = "INSERT INTO reservation (dtop_id, slot_id, user_num) ";
		// $sql .= "VALUES ('$desktop', '$slot[0]', '$waitUser[0]')";
		// $stmt = $dbh->prepare($sql);
		// $stmt->execute();
		// $stmt = null;


		#deletes the row from the queue with the user and the provided dtop_id and slot_id
		#IOW: removes the person from the queue because they have successfully reserved the timeslot

		// $sql  = "DELETE FROM queue WHERE dtop_id = '$desktop' AND slot_id = '$slot[0]' ";
		// $sql .= "AND user_num = '$waitUser[0]' AND wait_position = '$waitPos[0]'";
		// $stmt = $dbh->prepare($sql);
		// $stmt->execute();
	// }

	echo "You have released desktop " . $desktop . " at " . $time . " on " . $date;
?>
