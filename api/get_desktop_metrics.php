<?php
/*
  This file queries the database to get the total hours requested and reserved
  for each desktop within a provided date range and returns an HTML markup.
  Dates should be in the form yyyy-mm-dd
  Author: Chris Ancheta
  Date: 2019-10-25
*/

//====================================================================//
// This is for using the script via the command line instead of http  //
//====================================================================//
if (!isset($_SERVER["HTTP_HOST"])) {
    parse_str($argv[1], $_POST); 
}

header("Content-Type: text/html; charset=UTF-8");

if(!include('../connect.php')){
    die('error retrieving connect.php');
}

try {
  $dbh = ConnectDB();
  
  if(isset($_POST['startDate'], $_POST['endDate'])){
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // SQL Procedure that does all the heavy lifting
    $sql = "CALL getDesktopMetrics('$startDate','$endDate');";

    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    // Generate the markup -- class, thead, and tbody are all used for bootstrap4 styling
    $out = '<table class="table table-sm table-striped"><thead><tr>
              <th>Desktop</th>
              <th>Hours Used</th>
              <th>Hours Requested</th>
            </tr></thead><tbody>';
    
    foreach($stmt->fetchAll() as $row) {
      $out .=  "<tr><td>" . $row['name'];
      $out .= "</td><td>" . $row['time_used'] . ' hours';
      $out .= "</td><td>" . ($row['time_requested'] == '' ? $row['time_used'] : $row['time_requested']);
      $out .= ' hours';
      $out .= "</td></tr>";
    };
    $out .= "</tbody></table>";
    echo $out;

  } else {
    // This shouldn't happen. If it does then startDate and/or endDate were not provided
    echo "Something went wrong";
  }
} catch (Exception $e){ // Failed to connect to database
    die($e);
  }
?>