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
    <link rel="stylesheet" type="text/css" href="../Styles/displayTables.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="../Utils/docCookies.js"></script>
    <link rel="stylesheet" href="../Styles/accordion.css"></link>
  </head>

  <body>
    <div class="header">
      <div class="row">
        <div class="col">
          <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link" href="index.html">Calendar</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" style="cursor: pointer" onclick="logout()">Logout</a>
                </li>
              </ul>
            </div>
          </nav>
        </div>
      </div>
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
          $sql = "SELECT t.date, dtop_id, start_time, outcome, comment FROM reservation "
                . "JOIN user using (user_num) "
                . "JOIN timeslot t using (slot_id) "
                . "LEFT JOIN feedback using (reserve_id) "
                . "WHERE username = 'bill' "
                . "ORDER BY date DESC;";

          $stmt = $dbh->prepare($sql);
          $stmt->execute();

          foreach($stmt->fetchAll() as $row) {
            $outcome = ($row['outcome'] == '') ? "<a name='outcome' href='#' style='color: red'>Needs Feedback</a>" : $row['outcome'];
            $comment = ($row['comment'] == '') ?  '' : $row['comment'];
            $out =  " <tr><td>" . $row['date'];
            $out .= "</td><td>" . $row['dtop_id'];
            $out .= "</td><td>" . $row['start_time'];
            $out .= "</td><td>" . $outcome;
            $out .= "</td><td>" . $comment;
            $out .= "</td></tr>";
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
  </body>
  
</html>