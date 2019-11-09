<?php

#a table showing all of the reservations and their user_num, slot_id, and dtop_id
#database implemented in mySQL by Rico Rivera then transpired to the elvis server via puTTy
#IOW: Shows who has which desktop reserved and on what time and date
#author: Cassandra Bailey


        session_start();						//calls open&read session and saves the handlers
        if  (!include('../Utils/connect.php')) {					//checks to see if the system is connected to the database
                die('error finding connect file');			//error message that displays if not
        }

        $dbh = ConnectDB();						//connects to mySQL	


									//beow is the set up of an html webpage
?>
<html>

<head>
	<!-- Inherit the specific CSS for this page -->
	<link rel="stylesheet" type="text/css"href="../Styles/displayTables.css">				
</head>												

<body class="background" background="../Images/Background.png">	
<div class="header">View Reservations</div>										
<table>												
  <tr>
    <th>Reservation ID</th>									
    <th>Date</th>										
    <th>Time</th>										
    <th>Desktop ID</th>										
    <th>Desktop Name</th>									
    <th>User ID</th>										
    <th>First Name</th>										
    <th>Last Name</th>										
  </tr>
  <?php
    try{
      	$sql  = "SELECT reservation.reserve_id AS reserveID, ";					
	$sql .= "reservation.slot_id AS slotID, ";						
      	$sql .= "timeslot.date AS reserveDate, ";						
	$sql .= "timeslot.start_time AS reserveTime, ";						
	$sql .= "desktop.name AS dtopName, ";							
	$sql .= "reservation.dtop_id AS dtop_num, ";						
	$sql .= "reservation.user_num AS userID, ";						
	$sql .= "user.first_name AS firstName, ";						
	$sql .= "user.last_name AS lastName ";							
	$sql .= "FROM reservation ";								
	$sql .= "INNER JOIN user ON ";								
	$sql .= "reservation.user_num = user.user_num ";					
	$sql .= "INNER JOIN desktop ON ";
	$sql .= "reservation.dtop_id = desktop.dtop_id ";					
	$sql .= "INNER JOIN timeslot ON ";
	$sql .= "reservation.slot_id = timeslot.slot_id ";					
	$sql .= "ORDER BY timeslot.date";							

      $stmt = $dbh->prepare($sql);								
      $stmt->execute();										

      foreach($stmt->fetchAll() as $reserve){							
	$row  =  "<tr><td>" . $reserve['reserveID'];						
	$row .= "</td><td>" . $reserve['reserveDate'];						
	$row .= "</td><td>" . $reserve['reserveTime'];						
	$row .= "</td><td>" . $reserve['dtop_num'];						
	$row .= "</td><td>" . $reserve['dtopName'];						
	$row .= "</td><td>" . $reserve['userID'];						
	$row .= "</td><td>" . $reserve['firstName'];						
	$row .= "</td><td>" . $reserve['lastName'];						
	$row .= "</td></tr>";									
	echo $row;										
      }
    }
    catch(Exception $e){}
  ?>
</table>											
</body>												
</html>												
