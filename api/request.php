<?php

#this file contains queries for reserving a timeslot
#only executed if a different user doesn't have the slot reserved and the current user has not exceeded their max reservations
#for the cycle
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
try{
    $date = $_POST['date'];
    $time = $_POST['time'];
    $desktop = $_POST['desktop'];
    $curr = $_POST['curr'];
    $user = $_POST['user'];


	#retrieves the slot_id for the date and time provided
	
	$sql  = "SELECT slot_id FROM timeslot WHERE date = '$date' AND start_time = '$time'";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();
        $stmt = null;


	
	#selects the dtop_id from the reservation table using the user_num and slot_id provided
	#IOW: this checks to see if the current user has a different desktop reserved at the time they are requesting
	#if they do, they are not permitted to reserve this slot until they release the reservation in the same timeslot

        $sql2  = "SELECT dtop_id FROM queue WHERE user_num = '$curr' AND slot_id = '$row[0]'";
        $stmt = $dbh->prepare($sql2);
        $stmt->execute();

        $reservedDesk = $stmt->fetch();
        $stmt = null;

        if($reservedDesk[0] != null){
                echo "You already have desktop " . $reservedDesk[0] . " requested at this time.";
        }
        else{

		#inserts a row into the queue table with the user_num, dtop_id, and slot_id
		#IOW: reserves the desktop at the specified timeslot for the current user

                $sql3  = "INSERT INTO queue (dtop_id, slot_id, user_num) VALUES ('$desktop', '$row[0]', '$curr')";
                $stmt = $dbh->prepare($sql3);
                $success = $stmt->execute();
            
                echo "Timeslot sucessfully reserved";

        }


}
catch(Exception $e){}
?>
