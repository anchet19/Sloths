<?php
  /**
   * This page is the user dashboard, where they can view past reservations
   * and provide feedback about the results of their tests.
   * 
   * author: Chris Ancheta
   * date: 2019-10-29
   */
session_start();					                    //calls open and read session and saves handlers

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
    <script src='https://momentjs.com/downloads/moment.min.js'></script>
  </head>

  <body>
    <div class="header">My Dashboard</div>
      <div class="topnav">
        <ul>
          <li><a href="index">Calendar</a></li>
          <li><a id="actions-btn" href='#'> Actions </a></li>
          <li><a class="logout" onclick="logout()">Logout</a></li>
        </ul>
    </div>
    
    <div class="container">
      <div class="row no-gutter">
        <div class="col">
          <table class="table" id="reservation-table" style="table-layout: fixed; width: 100%">
            <tr class="table-header" id="table-header"></tr>
          </table>
        </div>
      </div>
    </div>
    <div id="dialog" title="Feedback Form" style="display: none">
      <form id="feedback-form" action="dashboard.php" method="post">
        <h4 class="dialog-title">Enter Feedback</h4>
        <b><label for="outcome-select">Outcome</label></b>
        <select class="dialog-select" id="outcome-select" name="outcome">
        </select>
        <b><label for="comment-field">Comment</label></b>
        <textarea name="comment" id="comment-field" cols="30" rows="5" maxlength="200"></textarea>
        <input id="reservation" type="text" name="reservation" style="display: none"></input>
      </form>
    </div>  
   </div>
   <div class="footer" disabled>
      <script language="javascript">
        sessionStorage.setItem('username', '<?php echo $_SESSION["username"]?>')
        function logout() {
            sessionStorage.clear();
            window.location.href = "login";
          }
      </script>
   </div>
  </body>
</html>
