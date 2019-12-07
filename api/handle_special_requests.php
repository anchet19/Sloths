<?php

#sends a special request to the admins from a manager
#
#author: Alex Cross

session_start();
// Connect to the database

if (!include('../Utils/connect.php')) {
    die('error finding connect file');
}
if(!isset($_POST['id'], $_POST['approved'])) {
    echo "Something Went Wrong";
    die();
  }
$id = $_POST['id'];
$approved = $_POST['approved'];
$dbh = ConnectDB();

$sql = 'CALL handleSpecialRequests('.$id.','.$approved.');';
$stmt = $dbh->prepare($sql);
$stmt->execute();



?>