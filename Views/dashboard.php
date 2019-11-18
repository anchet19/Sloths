<?php
  /**
   * This page is the user dashboard, where they can view past reservations
   * and provide feedback about the results of their tests.
   * 
   * author: Chris Ancheta
   * date: 2019-10-29
   */
session_start();
if(!isset($_SESSION['username'])){
  header("Location: ./404page");
}

include_once('./changepwd.php');
?>

<html>
  <head>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="../Styles/desktop.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"></script>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"></script>



    <!-- Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src='https://momentjs.com/downloads/moment.min.js'></script>
    <script src='../Controllers/dashboard.js'></script>
    
  </head>

  <body>
    <div class="header">My Dashboard</div>
      <div class="topnav">
        <ul>
          <li><a href="index">Calendar</a></li>
          <li><a onclick="makeVisible('reservation-container')">My Reservations</a></li>
          <li><a onclick="makeVisible('change-pwd')">Change Password</a></li>
          </li>
          <li><a class="logout" onclick="logout()">Logout</a></li>
        </ul>
    </div>
    
    <div class="container">
      <div id="main-row" class="row">
        <div class="col" id="reservation-container" style="display: none">
          <table class="dataTable" id="reservation-table" style="table-layout: fixed; width: 100%">
            <thead id="table-head"></thead>
            <tbody id="table-body"></tbody>
          </table>
        </div>
        <div  id="change-pwd" style="display: none">
          <form class="box" id="newPwd-form" action="dashboard.php" method="post">
              <h4 class="form-title">Change Password</h4>
              <?php include('../api/messages.php'); ?>
              <div class='form-group'>
                <label for="username"> Username </label>
                <input type="text" id="username" name="user_name" value="<?php echo $_SESSION['username']?>">
              </div>
              <div class='form-group'>
                <label for="old-pwd"> Current Password </label>
                <input type="password" id="old-pwd" name="old_password">
              </div>
              <div class='form-group'>
                <label for="new-pwd"> New Password </label>
                <input type="password" id="new-pwd" name="new_password">
              </div>
              <div class='form-group'>
                <label for="new-pwd-c"> Confirm New Password </label>
                <input type="password" id="new-pwd-c" name="confirm_pwd">
              </div>
              <input type="submit" name="change_pwd" value="Submit">
            </form>
        </div> 
      </div>
    </div>
    <div class="dialog" id="feedback-dialog" title="Enter Feedback" style="display: none">
      <form id="feedback-form" action="dashboard.php" method="post">
        <b><label for="outcome-select">Outcome</label></b>
        <select class="dialog-select" id="outcome-select" name="outcome">
        </select>
        <b><label for="comment-field">Comment</label></b>
        <textarea name="comment" id="comment-field" cols="30" rows="5" maxlength="200"></textarea>
        <input id="reservation" type="text" name="reservation" style="display: none"></input>
      </form>
    </div>
    <div class="dialog" id="comment-dialog" title="Comments" style="display: none">
      <p id="comment-dialog-body" style="word-wrap: break-word"></p>
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
