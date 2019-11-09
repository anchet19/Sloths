
<?php
#gets user information from the database
#returns data in json format to client
#Author: David Serrano (serranod7)
if(!include_once('./validate_func.php')){
    die('error finding validate_func file');
}
if(!include_once('./get_user_func.php')){
    die('error finding user_func file');
}

try{
  // if(isset($_POST['username'])){
    $username = 'bill' ;//$_POST['username'];
    echo getUser($username);
//   }else{
//    echo '{"validation":false}';
//  }
} catch(\Error $e){
  echo $e->getMessage();
}
?>
