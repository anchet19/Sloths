<?php

#shows all users and their user_num, first_name, last_name, email, username, and hashed password
#database implemented in mySQL by Rico Rivera then transpired to the elvis server via puTTy
#IOW: shows a list of all users and their information
#author: Cassandra Bailey


        session_start();				//calls open&read session save handlers
        if  (!include('../Utils/connect.php')) {			//checks to see if system is connected to database
                die('error finding connect file');	//error message if not found
        }

        $dbh = ConnectDB();				//connects to mySQL 


/**
*html page set up
**/

?>
<html>
<head>										
   <title>View Users</title>							
   <link rel="stylesheet" type="text/css" href="../Styles/displayTables.css">		
</head>										
<body>									
   <table>									
      <tr>
         <?php
	    try{								
               $sql = "SELECT column_name ";					
               $sql .= "FROM information_schema.columns ";			
               $sql .= "WHERE table_name = 'user'";
               $sql .= "AND TABLE_SCHEMA='baileyc5' and column_name != 'password'";				
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
	    $sql = "SELECT user_num,first_name,last_name,username,email,admin,user_points ";		
	    $sql .= "FROM user ";								
												
	    $stmt = $dbh->prepare($sql);							
	    $stmt->execute();									
												
	    foreach($stmt->fetchAll() as $user) {						
        $row =  " <tr><td>" . $user['user_num'];						
        $row .= "</td><td>" . $user['first_name'];					
        $row .= "</td><td>" . $user['last_name'];					
        $row .= "</td><td>" . $user['username'];						
        $row .= "</td><td>" . $user['email'];						
        //$row .= "</td><td>" . $user['password'];
        $row .= "</td><td>" . $user['admin'];
        $row .= "</td><td>" . $user['user_points'];
        $row .= "</td></tr>"; //testing
	       echo $row; 									
               }
	    $stmt = null;									
            }
	 catch(Exception $e){}
      ?>
   </table>										
</body>												
</html>												
