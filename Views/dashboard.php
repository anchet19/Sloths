<?php
  /**
   * This page is the user dashboard, where they can view past reservations
   * and provide feedback about the results of their tests.
   * 
   * author: Chris Ancheta
   * date: 2019-10-29
   */
session_start();					                    //calls open and read session and saves handlers
if  (!include('../Utils/connect.php')) {				    //checks to see if connected to the database
  die('error finding connect file');		//error message if the system is not connected
}
$dbh = ConnectDB();	  //connects to mySQL
?>

<html>
  <head>   
    <link rel="stylesheet" type="text/css" href="../Styles/desktop.css">    
    <link rel="stylesheet" type="text/css" href="../Styles/displayTables.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"></script>
    
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Dependencies for the table -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-footable/3.1.6/footable.core.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-footable/3.1.6/footable.core.standalone.min.css"></link>
    <script src='../Controllers/dashboard.js'></script>
  </head>

  <body>
    <div class="header"></div>
      <div class="topnav">
        <ul>
          <li><a href="index">Calendar</a></li>
          <li><a class="logout" onclick="logout()">Logout</a></li>
        </ul>
    </div>
    
    <div class="container">
      <div class="row no-gutter">
        <div class="col">

          <table style="table-layout: fixed; width: 100%">
            <tr id="table-header">
              <th>Date</th>
              <th>Desktop</th>
              <th>Start Time</th>
              <th>Outcome</th>
              <th>Comment</th>
            </tr>
            <?php
              try{
                $username = $_SESSION['username'];
                $max_strlen = 200;
                $sql = "SELECT t.date, reserve_id, dtop_id, start_time, outcome, comment FROM reservation "
                      . "JOIN user using (user_num) "
                      . "JOIN timeslot t using (slot_id) "
                      . "LEFT JOIN feedback using (reserve_id) "
                      . "WHERE username = '$username' "
                      . "ORDER BY date DESC, start_time DESC;";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                  $id = $row['reserve_id'];
                  $outcome = ($row['outcome'] == '') ? "<a class=\"feedback-link\" id=\"$id\" name=\"outcome\" href=\"#\" style=\"color: red\">Needs Feedback</a>" : $row['outcome'];
                  $comment = $row['comment'];
                  $length = strlen($comment);
                  $commentHtml = null;
                  if($length > $max_strlen){
                    $commentHtml = "<p href=\"#\" class=\"comment-tip\" title=\"$comment\" style=\"word-wrap: break-word\">" . substr($comment, 0, $max_strlen) . "...</p>";
                  } else if($length == 0) {
                    $commentHtml = '';
                  } else {
                    $commentHtml = $comment;
                  }
                  $out =  "<tr value=\"$id\"><td>" . $row['date'];
                  $out .= "</td><td>" . $row['dtop_id'];
                  $out .= "</td><td>" . $row['start_time'];
                  $out .= "</td><td>" . $outcome;
                  $out .= "</td><td style=\"word-wrap: break-word\">" . $commentHtml;
                  $out .= "</td></<tr>";
                  echo $out;
                }
                $stmt = null;
              }
              catch(Exception $e){}
            ?>
          </table>
        </div>
      </div>
    </div>
    <div id="dialog" title="Feedback Form" style="display: none">
      <form id="feedback-form" action="dashboard.php" method="post">
        <b><label for="outcome">Outcome</label></b>
        <select class="dialog-select" id="outcome-select" name="outcome">
        </select>
        <b><label for="Comment">Comment</label></b>
        <textarea name="comment" id="comment-field" cols="30" rows="5" maxlength="200"></textarea>
        <input id="reservation" type="text" name="reservation" style="display: none"></input>
      </form>
    </div>  
   </div>
   <div class="footer" disabled>
      <script language="javascript">
      console.log(sessionStorage.username)
        function logout() {
            sessionStorage.clear();
            window.location.href = "login";
          }
      </script>
   </div>
  </body>
</html>