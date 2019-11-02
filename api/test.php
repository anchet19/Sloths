
<?php
#finalizes the schedule by finding the min points,
#then moving that user, desktop, and timeslot info
#to the reservation table.
#This file then emails users who received reservations,
#Resets users with negative point values to zero points
#And then subtracts points from users who have not made a recent request
#Author: Alex Cross

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'Exception.php';
require 'PHPMailer.php'; # PHPMailer files to send emails via an SMTP
require 'SMTP.php';

//Testing counters
$u13 =0;
$u14 =0;
$u16 =0;
$u28 =0;
$u30 =0;
$u32 =0;

# Default values for fairness algorithm
$primeMod = 5;
$nonPrimeMod = -1;
$consolation = -2;
$daysIdle = 7;
$idleDeduction = -3;

# Load custom configuration if available

if($xml=simplexml_load_file("../Utils/configuration.xml")){
    $primeMod = $xml->primemod;
    $nonPrimeMod = $xml->nonprime;
    $consolation = $xml->consolation;
    $daysIdle = $xml->days_to_idle;
    $idleDeduction = $xml->idle_user;

    #Did not have SMTP information to hard code; Would advise against
    $smtp_mode = $xml->stmp_mode;
    $smtp_port = $xml->stmp_port;
    $smtp_username = $xml->smtp_username;
    $stmp_password= $xml->stmp_password;
    $stmp_host= $xml->stmp_host;
    $stmp_type= $xml->stmp_type;
    $stmp_outgoing_name= $xml->stmp_outgoing_name;
    $stmp_subject= $xml->stmp_subject;
    $stmp_content= $xml->stmp_content;
}


header("Content-Type: application/json");
if(!include('../Utils/connect.php'))
{
    die('error retrieving connect.php');
}

$dbh = ConnectDB();
$dbh2 = ConnectDB();

for($j=0;$j<1000;$j++){

    $move = "CALL multitest()";
    $stmt = $dbh2->prepare($move);
    if(!$stmt->execute())
        echo "Failed to set test\n";
    

  $sql = 'CALL getQueue()';
  
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  
  // variables for future mySQL queries
 
  
  
  
  foreach($stmt->fetchAll() as $row)
  {
            //vars that go into the SQL input
            $desktop = $row['dtop_id'];
            $slot =  $row['slot_id'];
            
            //var == SQL Procedure
            $min = "CALL findMinPoints(".$desktop.",".$slot.");";
            
            //find the lowest user point value for a particular desktop/slot combination
            
            //stmt is required to ensure first value is grabbed
            $stmt = $dbh2->prepare($min);
            $stmt->execute();
            $result = $stmt->nextRowset();
            $result = $stmt->nextRowset(); // goes to the third table of the procedure
            $result = $stmt->fetch();
            $desktop = $result['dtop_id'];
            $slot =  $result['slot_id'];
            $minpoints = $result['@minpoints'];
            $count = $result['@count'];
            
            

            /* echo "desktop: " . $desktop."\n";
            echo "slot: " . $slot ."\n";
            echo "minpoints: " .$minpoints."\n";
            echo "count: " .$count."\n"; */
            

            $min = "SELECT user_num from user join queue using (user_num) ";
            $min .= "where dtop_id = $desktop and slot_id = $slot and user_points = $minpoints order by user_num;";
            $stmt = $dbh2->prepare($min);
            $stmt->execute();

            $limit = rand(1, $count);
            //echo "Limit - " . $limit . "\n";
            for($i = 0; $i < $limit;$i++){
               $row = $stmt->fetch();
            }
             $userNum = $row['user_num'];

            // echo "*** WINNER : $userNum \n\n";

             switch($userNum){
                 case 13: $u13++; break;
                 case 14: $u14++; break;
                 case 16: $u16++; break;
                 case 28: $u28++; break;
                 case 30: $u30++; break;
                 case 32: $u32++; break;
             }

            //SQL Procedure: Copies entry from Queue -> Reservation, then removes the entry from Queue
            $move = "CALL mtrTest(".$userNum.",".$desktop.",".$slot.",". $primeMod . ",
                ".$nonPrimeMod.",".$consolation.");";

            $stmt = $dbh2->prepare($move);
            $stmt->execute();          
}
    
  # Find unique e-mails for blast
  $email = 'select distinct email from user join reservation using (user_num)';

  $stmt = $dbh2->prepare($email);
  $stmt->execute();

/* if($xml=simplexml_load_file("../Utils/configuration.xml")){
   foreach($stmt->fetchAll() as $row)
  {
    $email = $row['email'];
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port     = 587;  
    $mail->Username = "asrcscheduler@gmail.com";
    $mail->Password = "!QAZ1qazlizard";
    $mail->Host     = "smtp.gmail.com";
    $mail->Mailer   = "smtp";
    $mail->SetFrom("asrcscheduler@gmail.com", "Schedule Genie");
    $mail->AddReplyTo("No-reply", "PHPPot");
    $mail->AddAddress($email);
    $mail->Subject = "New Schedule Has Been Finalized (Do Not Reply)";
    $mail->WordWrap   = 80;
    $content = "<b>This e-mail is a notification that you have been selected for testing time slot(s) for the upcoming week.</b>"; 
    $mail->MsgHTML($content);
    $mail->IsHTML(true);
        if(!$mail->Send()) 
            echo "Problem sending email.";
        else 
        echo "email sent.\n";
    }
}  */

    // Sets users with < 0 points to 0
    $move = "CALL fix_points();";
    $stmt = $dbh2->exec($move);
    
    // Subtracts points from users without a recent request.
    $move = "CALL check_last_requests($daysIdle, $idleDeduction)";
    $stmt3 = $dbh2->exec($move);;

    # Resets all user requests to 0.
    $move = "CALL reset_requests()";
    $stmt3 = $dbh2->exec($move);

    
 
}
    
echo "User 13 wins: ".$u13 ."\n";
echo "User 14 wins: ".$u14 ."\n";
echo "User 16 wins: ".$u16 ."\n";
echo "User 28 wins: ".$u28 ."\n";
echo "User 30 wins: ".$u30 ."\n";
echo "User 32 wins: ".$u32 ."\n";

?>