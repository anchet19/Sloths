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
  <thead>
    <tr>
      <th scope="col">Desktop</th>
      <th scope="col" style="width:20%">Monday</th>
      <th scope="col" style="width:20%">Tuesday</th>
      <th scope="col" style="width:20%">Wednesday</th>
      <th scope="col"style="width:20%">Thursday</th>
      <th scope="col" style="width:20%">Friday</th>
      <!-- <th scope="col">Saturday</th>
      <th scope="col">Sunday</th> -->
    </tr>
  </thead>
  <tbody>
<?php
    // Find the start(Monday) and End(Friday) dates for the current week
    $dt_min = new DateTime("last saturday");
    $dt_min->modify('+2 day');  // Monday of current week
    $dt_max = clone($dt_min);
    $dt_max->modify('+4 days'); // Friday of current week
    $start = $dt_min->format('Y-m-d');
    $end = $dt_max->format('Y-m-d');
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

      $result = $dbh->query($sql)->fetchAll(PDO::FETCH_ASSOC);
      $newResult = [];
      
      // Create a new nested array formation to match the data representation
      // { Desktop: { 
      //     color:
      //     date1: [{Time, Name},...]
      //     date2: [{Time, Name},...]
      //     ... }
      // }
      foreach($result as $row) {
        $newResult[$row['dtopName']][0] = $row['dtopColor'];
        $newResult[$row['dtopName']][$row['reserveDate']][]= array_slice($row, 2,2);
      }
      foreach($newResult as $dtop => $data) {
        $markup = sprintf('<tr style=" background-color: %s"><th style="color: %s"><h2>%s</h2></th>', $data[0], $data[0], $dtop);
        for($i = 0; $i < 5; $i++){
          $markup .= '<td style="padding: 0">';
          foreach(array_slice($data, 1) as $key => $value) {
            $day = new DateTime($key);
            $diff = date_diff($day, $dt_min)->d;
            if($diff == $i) {
              $markup .= '<table>';
              for($k = 0; $k < count($value); $k++ )
              {
                $time = new DateTime($value[$k]['reserveTime']);
                $markup .= sprintf('<tr><td style="width: 25%%">%s</td><td style="width: 10px">%s</td></tr>',
                            /* $value[$k]['reserveTime']*/$time->format('g a'), $value[$k]['name']);
              }
              $markup .= '</table>';
            }
          }
          $markup .= '</td>';
        }
        $markup .= '</tr>';
        echo $markup;
      }
    }
    catch(Exception $e){}
  ?>
</table>											
</body>												
</html>												
