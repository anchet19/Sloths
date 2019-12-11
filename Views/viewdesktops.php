<?php

#shows a table which contains all of the desktops and the name and dtop_id
#database implemented in mySQL by Rico Rivera then transpired to the elvis server via puTTy
#author: Cassandra Bailey


        session_start();					//opens and reads session and saves handlers
        if  (!include('../Utils/connect.php')) {				//checks if the system is connected to the database
                die('error finding connect file');		//error message if not connected
        }

        $dbh = ConnectDB();					//connects to mySQL	


?>
<html>
<head>
   <title>View Desktops</title>
   <link rel="stylesheet" type="text/css" href="../Styles/displayTables.css">
</head>

<body class="background" background="../Images/Background.png">
<div class="header">View Desktops</div>
   <table>
      <tr>
         <?php
            try{
               $sql = "SELECT column_name ";
               $sql .= "FROM information_schema.columns ";
               $sql .= "WHERE table_name = 'desktop' ";
               $sql .= "AND TABLE_SCHEMA='baileyc5' AND column_name != 'active_bit'";
               $stmt = $dbh->prepare($sql);
               $stmt->execute();

               foreach($stmt->fetchAll() as $columns) {
                  echo " <th>" . $columns['column_name'] . " </th>";
               }
            }
 	    catch(Exception $e){}
         ?>
      </tr>
      <?php
         try{
            $sql = "SELECT name, dtop_id, color ";
            $sql .= "FROM desktop ";

            $stmt = $dbh->prepare($sql);
            $stmt->execute();

            foreach($stmt->fetchAll() as $dtop) {
               $row =  " <tr><td>" . $dtop['dtop_id'];
               $row .= "</td><td>" . $dtop['name'];
               $row .= "</td><td>" . $dtop['color'];
               $row .= "</td></tr>";
               echo $row;
               }
            $stmt = null;
            }
         catch(Exception $e){}
      ?>
   </table>



</body>
</html>


