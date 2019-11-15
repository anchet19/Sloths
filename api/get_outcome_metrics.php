<?php
// This is for testing the script via the CLI(command line interface)
/*  if (!isset($_SERVER["HTTP_HOST"])) {
     // script is not interpreted due to some call via http, so it must be called from the commandline
     parse_str($argv[1], $_POST); // use $_POST instead if you want to
   } */
header("Content-Type: text/html; charset=UTF-8");

  if(!include('../Utils/connect.php')){
      die('error retrieving connect.php');
  }
  $dbh = ConnectDB();
    
  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate'];

  $sql = "CALL POSTOutcomeMetrics('$startDate','$endDate');";

  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $out = '<table class="table table-sm table-striped"><thead><tr>
          <th>Desktop</th>
          <th>Outcome</th>
          <th>Hours</th>
        </tr></thead><tbody>';
  
  foreach($stmt->fetchAll() as $row) {
    $out .=  " <tr><td>" . $row['Desktop'];
    $out .= "</td><td>" . $row['Outcome'];
    $out .= "</td><td>" . $row['Hours'];
    $out .= "</td></tr>";
  };
  $out .= "</table>";
  echo $out;
?>