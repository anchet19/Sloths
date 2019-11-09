

<?php

#this shows a table containing all of the builds and their name and b_num
#database implemented in mySQL by Rico Rivera then transpired to the elvis server via puTTy
#author: Cassandra Bailey


        session_start();					//calls open and read session and saves handlers
        if  (!include('../Utils/connect.php')) {				//checks to see if connected to the database
                die('error finding connect file');		//error message if the system is not connected
        }

        $dbh = ConnectDB();					//connects to mySQL


?>
<html>
<head>
   <title>View Builds</title>
   <link rel="stylesheet" type="text/css" href="../Styles/displayTables.css">
</head>

<body>
<table>
      <tr>
         <?php
            try{
               $sql = "SELECT column_name ";
               $sql .= "FROM information_schema.columns ";
               $sql .= "WHERE table_name = 'build'";
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
            $sql = "SELECT name, b_num ";
            $sql .= "FROM build";

            $stmt = $dbh->prepare($sql);
            $stmt->execute();

            foreach($stmt->fetchAll() as $build) {
               $row =  " <tr><td>" . $build['b_num'];
               $row .= "</td><td>" . $build['name'];
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
