<?php
#adds user to database using data provided
#in the post parameters.
#authors: Cassandra Bailey, David Serrano (serranod7)


include_once("../Utils/connect.php");
include_once("./validate_func.php");
include_once("./get_user_func.php");

try{
    $dbh = ConnectDB();
    if(isset($_POST['newUsername']) && isset($_POST['newPassword']) && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['email']) && isset($_POST['admin'])&& isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $newUsername = $_POST['newUsername'];
        $newPassword = $_POST['newPassword'];

        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $admin = $_POST['admin'];
        

    #checks to make sure email entered is in email format        
        if(validate($username, $password) && filter_var($email, FILTER_VALIDATE_EMAIL)){
            $userData = getUser($username, $password);
            
            if($userData['admin'] == 1){
                //hash password
                $hashed_pwd = password_hash($newPassword, PASSWORD_DEFAULT);
   
   
                
                $sql  = "SELECT * FROM user WHERE email = '$email'";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                $currEmail = $stmt->fetch();
                $stmt = null;
                
                $sql  = "SELECT * FROM user WHERE username = '$newUsername'";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                $currUser = $stmt->fetch();
                $stmt = null;
	    #if email already in use, error thrown
                if($currEmail[0] != null){
                     echo '{"result":false}';
                }
	    #if username already in use, error thrown
                else if ($currUser[0] != null){
                     echo '{"result":false}';
                }
                else{
                    $sql = "INSERT INTO user ";
                    $sql .= "(username, first_name, last_name,
      email, password, admin)";
                    $sql .= " VALUES ";
                    $sql .= "('$newUsername', '$firstName',
      '$lastName', '$email', '$hashed_pwd', '$admin')";
                    
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute();   
                    echo '{"result":true}';
                }
            }else{
                echo '{"result":false}';
            }
        }else{
            echo '{"result":false}';
        }
    }else{
        echo '{"result":false}';
    }
    
}catch(\Error $e){
    echo $e->getMessage();
}




?>
