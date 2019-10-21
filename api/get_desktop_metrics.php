<?php
// This is for testing the script via the CLI(command line interface)
if (!isset($_SERVER["HTTP_HOST"])) {
    // script is not interpreted due to some call via http, so it must be called from the commandline
    parse_str($argv[1], $_GET); // use $_POST instead if you want to
  }

  header("Content-Type: application/json");

  if(!include('../connect.php')){
      die('error retrieving connect.php');
  }

  try {
    $dbh = ConnectDB();
    
    if(isset($_GET['startDate'], $_GET['endDate'])){
      $startDate = $_GET['startDate'];
      $endDate = $_GET['endDate'];

      $sql = "CALL getDesktopMetrics('$startDate','$endDate');";

      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $out = '';
     
      foreach($stmt->fetchAll() as $row) {
        $out =  " <tr><td>" . $row['name'];
        $out .= "</td><td>" . $row['time_used'] . ' hours';
        $out .= "</td><td>" . ($row['time_requested'] == '' ? 0 : $row['time_requested']);
        $out .= ' hours';
        $out .= "</td></tr>";
        echo $out;
      };

    } else {
      echo "Something went wrong";
    }
  } catch (Exception $e){
      die($e);
    }

?>