
<?php
#Returns the validated information from validate_func.php to 
#the front end in json format
#Author: David Serrano (serranod7)

if(!include_once('./validate_func.php'))
{
    die('error finding validate_func file');
}

try{
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (validate($username, $password)){
            echo '{"validation":true}';
        }else{
            echo '{"validation":false}';
        }
       
    }
    else{
        echo '{"validation":false}';
    }
} catch(\Error $e){
    echo $e->getMessage();
}

?>
