<?php
header("Content-Type: text/html; charset=UTF-8");

  if(!include('../Utils/connect.php')){
      die('error retrieving connect.php');
  }
  $dbh = ConnectDB(); 

  $sql = "select sr_id, t.date, t.start_time as start, d.name as dtop, b.name as build, concat(u.first_name,' ',u.last_name) as user, concat(u2.first_name,' ',u2.last_name) as manager ";
  $sql .= "from special_requests sr ";
  $sql .= "join desktop d using (dtop_id) " ;
  $sql .= "join user u using (user_num) ";
  $sql .= "join user u2 on u2.user_num = manager_id ";
  $sql .= "join build b using (b_num) ";
  $sql .= "join timeslot t using (slot_id); ";

  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $out = '<table class="table table-sm table-striped"><thead><tr>          
          <th>Date</th>
          <th>Start</th>
          <th>Desktop</th>
          <th>Build</th>
          <th>User</th>
          <th>Manager</th>
          <th></th>
        </tr></thead><tbody>';

  foreach($stmt->fetchAll() as $row) {    
    $out .= "</td><td>" .$row['date'];
    $out .= "</td><td>" .$row['start'];
    $out .= "</td><td>" .$row['dtop'];
    $out .= "</td><td>" .$row['build'];
    $out .= "</td><td>" .$row['user'];
    $out .= "</td><td>" .$row['manager'];
    $out .= "</td><td style='display: none'>".$row['sr_id'];
    $out .= "</td><td><button type=button class='accReq'>Accept</button><button type=button class='denReq'>Deny</button>";  
    $out .= "</td></tr>";
  };
  $out .= "</table>";
  echo $out;

?>