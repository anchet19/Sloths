
<?php
#Backend file to validate user information
#Author: David Serrano (serranod7)

if(!include_once('../connect.php'))
{
    die('error finding connect file');
}

function validate($username, $password){
    $dbh = ConnectDB();
    $sql = "SELECT COUNT(*) FROM user ";
    $sql .= "WHERE username = '$username'";
    
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rowcount = $stmt->fetch();
    if($rowcount[0] == 0){
        return false;
    }else{
        $sql = "SELECT password FROM user WHERE ";
        $sql .= "username = '$username'";
        
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $pwd = $stmt->fetch();
        
        if (password_verify($password, $pwd[0])){
            return true;
        }
        else{
            return false;
        }        
 
    }
}
?>
