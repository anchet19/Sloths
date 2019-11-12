<?php

#provides a table with all of the installations and their b_num and dtop_id
#database implemented in mySQL by Rico Rivera then transpired to the elvis server via puTTy
#IOW: You can see which builds are installed on which desktops
#author: Cassandra Bailey


        session_start();					//calls open and read session and saves handlers
        if  (!include('../Utils/connect.php')) {				//checks to see if the system is connected to the database
                die('error finding connect file');		//error message if not found
        }

        $dbh = ConnectDB();					//connects to mySQL


?>
<html>
<head>
  <link rel="stylesheet" type="text/css"href="../Styles/displayTables.css">
</head>

<body class="background" background="../Images/Background.png">
<div class="header">View Installations</div>
<table>
  <tr>
    <th>Installation ID</th>
    <th>Desktop Name</th>
    <th>Desktop ID</th>
    <th>Build Name</th>
    <th>Build ID</th>
  </tr>

  <?php
    try{
	$sql = "SELECT installation.install_id AS installID, "; 
	$sql .= "installation.dtop_id AS dtopID, ";
	$sql .= "desktop.name AS dtopName, ";
	$sql .= "installation.b_num AS bID, ";
	$sql .= "build.name AS buildName ";
	$sql .= "FROM installation ";
	$sql .= "INNER JOIN desktop ON ";
	$sql .= "installation.dtop_id=desktop.dtop_id ";
	$sql .= "INNER JOIN build ON "; 
	$sql .= "installation.b_num=build.b_num ";
	$sql .= "ORDER BY desktop.name";
     
      $stmt = $dbh->prepare($sql);
      $stmt->execute();

      foreach($stmt->fetchAll() as $install) {
        $row  =  "<tr><td>" . $install['installID'];
	$row .= "</td><td>" . $install['dtopName'];
        $row .= "</td><td>" . $install['dtopID'];
	$row .= "</td><td>" . $install['buildName'];
        $row .= "</td><td>" . $install['bID'];
	$row .= "</td></tr>";
	echo $row;
      }
    }
    catch(Exception $e){};
  ?>
</table>
</body>
</html>
