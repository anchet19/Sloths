<?php

#when the logout link is clicked in index.html, the page is directed here
#this page destroys the session variables and redirects the user to the login page
#author: Cassandra Bailey


   session_start();
   // Connect to the database

   if (!include('connect.php')) {
      die('error finding connect file');
   }
?>

<?php
   session_destroy();
   header("Location: Views/login.php");
 ?>

