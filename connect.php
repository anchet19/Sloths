<?php

ConnectDB();

// ConnectDB() - takes no arguments, returns database handle
// USAGE: $dbh = ConnectDB();
function ConnectDB() {

   /*** mysql server info ***/
    $hostname = '127.0.0.1';  // Local host, i.e. running on elvis
    $username = 'baileyc5';           // Your MySQL Username goes here
    $password = 'cassrocks';           // Your MySQL Password goes here
    $dbname   = 'baileyc5';           // For elvis, your MySQL Username is repeated here

   try {
       $dbh = new PDO("mysql:host=$hostname;dbname=$dbname",
                      $username, $password);
    }
    catch(PDOException $e) {
        die ('PDO error in "ConnectDB()": ' . $e->getMessage() );
    }

    return $dbh; 
}

?>
