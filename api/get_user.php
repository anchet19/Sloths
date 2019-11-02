
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
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (validate($username, $password)){
            echo json_encode(getUser($username,$password));
        }else{
            echo '{"validation":false}';
        }
    }else{
        echo '{"validation":false}';
    }
} catch(\Error $e){
    echo $e->getMessage();
}


?>
