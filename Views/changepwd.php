<?php
# Allows the user to change password to a new one given they 
# are already a user. To change the passowrd, the user must provide the username,password,and new password.
# Authors:Eric DeAngelis,Riddhi Patel, Kyle Kaminski

if  (!include('connect.php')) {
    die('error finding connect file');
}
$dbh = ConnectDB();
try{
	  if(isset($_POST['submit'])){
		    $username = $_POST['user_name'];
		    $currentpwd = $_POST['old_password'];
		    $newpwd = $_POST['new_password'];
		    $confirmedpwd = $_POST['confirm_pwd'];
		    
		    if($username == "" || $currentpwd == "" || $newpwd == "" ||$confirmedpwd == ""){
			      echo "All fields are required.";
		    }elseif ($newpwd != $confirmedpwd) {
			      echo "Passwords do not match.";
		    }else{
			
		    #Checks to see if the username is in the list; if 0, it does not occur and is invalid		
		    	$sql  = "SELECT COUNT(*) FROM user ";
        		$sql .= "WHERE username = '$username'";
        		$stmt = $dbh->prepare($sql);
	      		$stmt->execute();
        		$rowcount = $stmt->fetch();
        		$stmt = null;
        		if($rowcount[0] == 0){
            			echo '<p><font color="red">Invalid username.</font></p>';
        		}
        		else{
            		    #checks to see if the current password is correct
            			$sql  = "SELECT password FROM user WHERE username = '$username'";
            			$stmt = $dbh->prepare($sql);
            			$stmt->execute();
            			$pwd = $stmt->fetch();
            			$stmt = null;
            
            			//De - hash old password
            			$check_hashedpwd = password_verify($currentpwd, $pwd[0]);
            
            			if($check_hashedpwd == false){
                			echo '<p><font color="red">Invalid Password.</font></p>';
			      	}elseif($check_hashedpwd == true){
		       		  //Hash password
                			$hashed_newpwd = password_hash($newpwd,PASSWORD_DEFAULT);
                			
				    #if the old password was correct, the password is updated to the new one
                			$sql = "UPDATE user SET password='$hashed_newpwd' WHERE username='$username'";
               				$stmt = $dbh->prepare($sql);
                			$stmt->execute();
                			echo "<a href= 'login.php' >Go back to login page</a>";
					$stmt = null;
            			}
		    	}		
		}  
	}
}
catch(Exception $e)
{
    echo 'error';
}
?>
<html>
    <head>
    </head>
    <body>
        <form action="" method="post">
            <h1>Change Password</h1>
            <br>
            Username:
            <input type="text" name="user_name">
            <br><br>
            Current Password:
            <input type="password" name="old_password">
            <br><br>
            New Password:
            <input type="password" name="new_password">
            <br><br>
            Confirm New Password:
            <input type="password" name="confirm_pwd">
            <br><br>
            <input type="submit" name="submit" value="Submit">
        <style>
	    body{
            margin:0;
            padding:0;
	    background:#2e398f;}

  	   input[type=submit] {padding:5px 15px; background:white; 
	   border:0 
           none;
           cursor:pointer;
           -webkit-border-radius: 5px;
           border-radius: 5px; }
	   
 	   .user_name{
		color:white;
        </style>
       
        </form>
    </body>
</html>

