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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="../Styles/desktop.css">    
    <link rel="stylesheet" type="text/css" href="../Styles/displayTables.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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
          <table>
            <tr>
              <th>Date</th>
              <th>Desktop</th>
              <th>Start Time</th>
              <th>Outcome</th>
              <th>Comment</th>
            </tr>
            <?php
              try{
                $sql = "SELECT t.date, reserve_id, dtop_id, start_time, outcome, comment FROM reservation "
                      . "JOIN user using (user_num) "
                      . "JOIN timeslot t using (slot_id) "
                      . "LEFT JOIN feedback using (reserve_id) "
                      . "WHERE username = 'bill' "
                      . "ORDER BY date DESC;";

                $stmt = $dbh->prepare($sql);
                $stmt->execute();

                foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                  $id = $row['reserve_id'];
                  $outcome = ($row['outcome'] == '') ? "<a class=\"feedback-link\" id=\"$id\" name=\"outcome\" href=\"#\" style=\"color: red\">Needs Feedback</a>" : $row['outcome'];
                  $comment = ($row['comment'] == '') ?  '' : $row['comment'];
                  $out =  "<tr value=\"$id\"><td>" . $row['date'];
                  $out .= "</td><td>" . $row['dtop_id'];
                  $out .= "</td><td>" . $row['start_time'];
                  $out .= "</td><td>" . $outcome;
                  $out .= "</td><td>" . $comment;
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
          <option value="success"> Success </option>
          <option value="software issue"> Software Issue </option>
          <option value="hardware issue"> Hardware Issue </option>
        </select>
        <b><label for="Comment">Comment</label></b>
        <textarea name="comment" id="comment-field" cols="30" rows="5" maxlength="200"></textarea>
        <input id="reservation" type="text" name="reservation" style="display: none"></input>
      </form>
    </div>

    </div>
  </body>
</html>