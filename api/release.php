
<?php


#this file contains queries for releasing an already reserved timeslot
#author: Cassandra Bailey

session_start();
// Connect to the database

if (!include('../Utils/connect.php')) {
    die('error finding connect file');
}
// Conn
$dbh = ConnectDB();
// Data from the form
$curr = $_POST['curr'];
$time = $_POST['time'];
$date = $_POST['date'];
$desktop = $_POST['desktop'];
$eventType = $_POST['eventType'];

// Default values in case config doesn't load properly
$primeMod = 5;
$nonPrimeMod = -1;
$prime_start = '09:00:00'; // 9am
$prime_end = '14:00:00';  // 6pm
$release_nonprime = 0;
$release_prime = 0;

// Include the config file and set the default values
if($xml=simplexml_load_file("../Utils/configuration.xml")){
  $primeMod = $xml->primemod;
  $nonPrimeMod = $xml->nonprime;
  $prime_start = new DateTime($xml->prime_start);
  $prime_end = new DateTime($xml->prime_end);
  $release_nonprime = $xml->$release_nonprime;
  $release_prime = $xml->$release_prime;
}

#this query gets the slot_id for the time and date selected

$sql  = "SELECT slot_id FROM timeslot ";
$sql .= "WHERE date = '$date' ";
$sql .= "AND start_time = '$time'";

$stmt = $dbh->prepare($sql);
$stmt->execute();
$slot = $stmt->fetch();
$stmt = null;

// Delete request from queue table
if($eventType == 'request'){
	$sql2  = "DELETE FROM queue ";
  $sql2 .= "WHERE user_num = '$curr' ";
  $sql2 .= "AND dtop_id = '$desktop' ";
  $sql2 .= "AND slot_id = '$slot[0]'";

  $stmt = $dbh->prepare($sql2);
  $stmt->execute();
  $stmt = null;

  // Decrement the request count
  $sql = "UPDATE user SET num_requests = num_requests - 1 WHERE user_num = $curr";
  $dbh->query($sql);

}
// Delete reservation from reservation table
// and refund half the points the reservation cost to the user
else {
  // Delete the reservation
  $sql2  = "DELETE FROM reservation ";
  $sql2 .= "WHERE user_num = '$curr' ";
  $sql2 .= "AND dtop_id = '$desktop' ";
  $sql2 .= "AND slot_id = '$slot[0]'";
  $stmt = $dbh->prepare($sql2);
  $stmt->execute();
  $stmt = null;
// REFUND THE POINTS
  // Figure out if the reservation was during prime-time or not and decide how to adjust the points. 
  // Non prime is reset to their points before winning the reservation
  // Prime reduces by half the point penalty for winning the reservation (integer division)
  $startTime = new DateTime($time);
  $points = (($startTime >= $prime_start) && ($startTime < $prime_end)) 
    ? (int)($primeMod / 2) + $release_prime : ($nonPrimeMod) + $release_nonprime;
  
  // Adjust the users points
  $sql3 = "UPDATE user SET user_points = (user_points - $points) WHERE user_num = '$curr'";
  $stmt = $dbh->prepare($sql3);
  $stmt->execute();
  }
	echo "You have released desktop " . $desktop . " at " . $time . " on " . $date;
?>
