<?php
#inserts a row into the queue table with the user_num, slot_id, dtop_id, and the current wait position in the queue
#author: Cassandra Bailey
#modified: Alex Cross
  if  (!include('../Utils/connect.php')) {
          die('error finding connect file');
  }
  $dbh = ConnectDB();

  $requestLimit = 3;
  if($xml=simplexml_load_file("../Utils/configuration.xml")){
    $requestLimit = $xml->requestlimit;
  }
  
	$actualWait = 0; #hardcoded to skirt outdated use
	$curr = $_POST['curr'];
	$date = $_POST['date'];
	$time = $_POST['time'];
	$build = $_POST['build'];
  $desktop = $_POST['desktop'];
  $note = $_POST['note'];
  
  if($curr <= 0){
    echo "You must select a user to make a request.";
    die();
  }

  $sql = "SELECT num_requests from user where user_num = '$curr' ";
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $userRequests = $stmt->fetch();
  if ($userRequests['num_requests'] >= $requestLimit){
    echo "This user already has ".$requestLimit." requests.";
    die();
  }
  if($build == 0 || $desktop == 0) {
    echo "You must choose a Build and a Desktop to make a request.";
  } else {
    #gets the slot_id for the date and start_time provided
    $sql  = "SELECT slot_id, state FROM timeslot ";
    $sql .= "WHERE date = '$date' AND start_time = '$time'";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $slot = $stmt->fetch();
    $stmt = null;
    // Checks to see if that slot/desktop combo is in the block list
    $sql = "SELECT COUNT(slot_id) FROM maintenance WHERE slot_id = $slot[0] AND dtop_id = $desktop";
    $blocked = $dbh->query($sql)->fetch();
    if($blocked[0] <= 0){   
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
        if($slot[1] == 0){ #First state check
        #inserts the request into the queue				
          $sql  = "INSERT INTO queue (dtop_id, b_num, slot_id, wait_position, user_num, request_time, note) ";
          $sql .= "VALUES ('$desktop', '$build', '$slot[0]', '$actualWait', '$curr',(select now()), '$note')";
          $stmt = $dbh->prepare($sql);
          $stmt->execute();
        
          $sql = "CALL set_last_request($curr);"; #sets user last_request datetime in the db
          $stmt = $dbh->prepare($sql);
          $stmt->execute();
          $row  = "You have joined the queue for desktop " . $desktop . " at " . $time . " on " . $date;
          echo $row;
        }
        if($slot[1] == 1){ #Second State Check -- 2nd round pick
          $sql ="SELECT count(*) from leftover where user_num = '$curr' ";
          $stmt = $dbh->prepare($sql);
          $stmt->execute();
          $eligible = $stmt->fetch();
          if($eligible[0] > 0){
            $sql  = "INSERT INTO queue (dtop_id, b_num, slot_id, wait_position, user_num, request_time) ";
            $sql .= "VALUES ('$desktop', '$build', '$slot[0]', '$actualWait', '$curr',(select now()))";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
          
            $sql = "CALL set_last_request($curr);";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $row  = "You have joined the queue for desktop " . $desktop . " at " . $time . " on " . $date;
            echo $row;
          }else{
            echo "This user is ineligible for second round picks.";
          }
        }
        if($slot[1] == 2){ #3rd state check -- FCFS FFA
          $sql  = "SELECT count(*) FROM reservation WHERE slot_id = '$slot[0]' AND (dtop_id = '$desktop' OR user_num = '$curr') "; #crossmod		
          $stmt = $dbh->prepare($sql);
          $stmt->execute();
          $eligible = $stmt->fetch();
          if($eligible[0] == 0){
          $sql  = "INSERT INTO reservation (dtop_id, b_num, slot_id, user_num) ";
          $sql .= "VALUES ('$desktop', '$build', '$slot[0]', '$curr')";
          $stmt = $dbh->prepare($sql);
          $stmt->execute();
        
          $sql = "CALL set_last_request($curr);";
          $stmt = $dbh->prepare($sql);
          $stmt->execute();
          $row  = "You have successfully reserved " . $desktop . " at " . $time . " on " . $date;
          echo $row;
          }
          else{
            echo "This slot is reserved by someone else or this user has a reservation for a different desktop at this time.";
          }
        }
      }	
      else{
        #echo "You are already in the queue for desktop " . $desktop . " at " . $time . " on " . $date;
        echo "A request for this timeslot already exists for this user.";
      }
    } else {
      echo "Desktop unavailable";
    }
  }
?>