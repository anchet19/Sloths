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

  if(isset($_POST['filter'])){   
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $filter = $_POST['filter'];
    $department = $_POST['department'];

  
  if($filter == 'userTotals'){

  $sql = "CALL managerUserTotal('$startDate','$endDate', '$department');";

  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $out = '<table class="table table-sm table-striped"><thead><tr>
          <th>Name</th>
          <th>Hours Reserved</th>
          <th>Hours Unfulfilled</th>
        </tr></thead><tbody>';
  
  foreach($stmt->fetchAll() as $row) {
    $out .=  " <tr><td>" . $row['name'];
    $out .= "</td><td>" . $row['reserved'];
    $out .= "</td><td>" . $row['requested'];  
    $out .= "</td></tr>";
  };
  $out .= "</table>";
  echo $out;
}

if($filter == 'Build') {
  $sql = "CALL managerUserBuildTotal('$startDate','$endDate', '$department');";

  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $out = '<table class="table table-sm table-striped"><thead><tr>
          <th>Name</th>
          <th>Build</th>
          <th>Hours Reserved</th>
          <th>Hours Unfulfilled</th>
        </tr></thead><tbody>';
  
  foreach($stmt->fetchAll() as $row) {
    $out .=  " <tr><td>" . $row['name'];
    $out .= "</td><td>" . $row['b_name'];
    $out .= "</td><td>" . $row['reserved'];
    $out .= "</td><td>" . $row['unfulfilled'];
    $out .= "</td></tr>";
  };
  $out .= "</table>";
  echo $out;

}
  }else{
    echo "No filter option was selected.";
  }

?>