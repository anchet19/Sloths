  
<?php
#finalizes the schedule by finding the min points,
#then moving that user, desktop, and timeslot info
#to the reservation table.
#This file then emails users who received reservations,
#Resets users with negative point values to zero points
#And then subtracts points from users who have not made a recent request
#Author: Alex Cross     Last Modification: 10/31/19

header("Content-Type: application/json");
if(!include('../Utils/connect.php'))
{
    die('error retrieving connect.php');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'Exception.php';
require 'PHPMailer.php'; # PHPMailer files to send emails via an SMTP
require 'SMTP.php';

# Default values for fairness algorithm
$userNum = 0;
$primeMod = 5;
$nonPrimeMod = -1;
$consolation = -2;
$daysIdle = 7;
$idleDeduction = -3;

# Load custom configuration if available
if($xml=simplexml_load_file("../Utils/configuration.xml")){
    $primeMod = $xml->primemod;
    $nonPrimeMod = $xml->nonprime;
    $consolation = $xml->consolation; #see XML for variable descriptions
    $daysIdle = $xml->days_to_idle;
    $idleDeduction = $xml->idle_user;
    #SMTP Settings
    #Did not have SMTP information to hard code; Would advise against
    $smtp_mode = $xml->stmp_mode;
    $smtp_port = $xml->stmp_port;
    $smtp_username = $xml->smtp_username;
    $smtp_password= $xml->stmp_password;
    $smtp_host= $xml->stmp_host;
    $smtp_type= $xml->stmp_type;
    $smtp_outgoing_name= $xml->stmp_outgoing_name;
    $smtp_subject= $xml->stmp_subject;
    $smtp_content= $xml->stmp_content;
}

$dbh = ConnectDB();
$dbh2 = ConnectDB();

  $sql = 'CALL getQueue()'; #finds distinct slot+desktop combinations
  
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  
  foreach($stmt->fetchAll() as $row)
  {
    //vars that go into the SQL input
    $desktop = $row['dtop_id'];
    $slot =  $row['slot_id'];
            
    #find the minimum number of points, count how many people have those points
    $min = "CALL findMinPoints(".$desktop.",".$slot.");";
            
    //find the lowest user point value for a particular desktop/slot combination
            
    //stmt2 is required to ensure first value is grabbed
    $stmt2 = $dbh2->prepare($min);
    $stmt2->execute();
    $result = $stmt2->nextRowset();
    $result = $stmt2->nextRowset();
    $result = $stmt2->fetch();
    $desktop = $result['dtop_id'];
    $slot =  $result['slot_id'];
    $minpoints = $result['@minpoints'];
    $count = $result['@count'];

    echo "desktop: " . $desktop."\n";
    echo "slot: " . $slot ."\n";
    echo "minpoints: " .$minpoints."\n";
    echo "count: " .$count."\n";
            
    
    $min = "SELECT user_num, q.b_num from user join queue q using (user_num) ";
    $min .= "where dtop_id = $desktop and slot_id = $slot and user_points = $minpoints order by user_num";

    $stmt2 = $dbh2->prepare($min);
    $stmt2->execute();

    if($count > 1)
        {
            $win = random_int(1, $count);
            echo "Limit - " . $win . "\n";
            for($i = 0; $i < $win; $i++)
                {
                    $row = $stmt2->fetch(); #iterate the random number of times to get the winners info                   
                }
            $userNum = $row['user_num'];
            $build = $row['b_num'];
        }
    else
        {
            $row = $stmt2->fetch();
            $userNum = $row['user_num'];
            $build = $row['b_num'];
        }       
            
            //SQL Procedure: Copies entry from Queue -> Reservation, then removes the entry from Queue
            $move = "CALL mtrTest(".$userNum.",".$desktop.",".$slot.",". $primeMod . ",
                ".$nonPrimeMod.",".$consolation.",".$build.");";

            $stmt2 = $dbh2->prepare($move);
            $stmt2->execute();          
}

  # Find unique e-mails for blast
  $email = 'select distinct email from user join reservation using (user_num)';

  $stmt2 = $dbh2->prepare($email);
  $stmt2->execute();

  #******** DO NOT DELETE - COMMENTED OUT SO IT DOESNT SPAM EMAILS DURING TESTING ********
/*  if($xml=simplexml_load_file("../Utils/configuration.xml"))
 {
   foreach($stmt2->fetchAll() as $row)
    {
        $email = $row['email'];
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = TRUE;
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;  
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
        $mail->send();
    }
 } */ #******************************************************************************************
    # Sets users with < 0 points to 0
    $move = "CALL fix_points();";
    $stmt2 = $dbh2->prepare($move);
    $stmt2->execute();
    
    # Subtracts points from users without a recent request.
    $move = "CALL check_last_requests($daysIdle, $idleDeduction)";
    $stmt3 = $dbh2->prepare($move);
    $stmt3->execute();

    # Resets all user requests to 0.
    $move = "CALL reset_requests()";
    $stmt3 = $dbh2->prepare($move);
    $stmt3->execute();
    
    $move = "SELECT DAYOFWEEK(now()) ";
    $stmt3 = $dbh2->prepare($move);
    $stmt3->execute();
    $dayOfWeek = $stmt3->fetch();

    if($dayOfWeek[0] == 5){ # if it's thursday, it's first finalization - move state from 0 to 1
        $sql = "CALL state0to1 ";
        $stmt3 = $dbh2->prepare($sql);
        $stmt3->execute();
    }
    if($dayOfWeek[0] == 6){ # if friday, move from state 1 to 2.
        $sql = "CALL state1to2 ";
        $stmt3 = $dbh2->prepare($sql);
        $stmt3->execute();
    }


?>
