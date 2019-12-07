<?php 
session_start();
if(!isset($_SESSION['username'])){
  header("Location: ./404page");
}
?>

<!DOCTYPE html>

<!--entry point of application
Authors: Team Elephants, Team Sloths-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ASRC Federal Desktop Scheduler</title>

    <!-- Highest level of CSS, applies to all pages of the software. -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/desktop.css">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.css' />
        <!-- CSS specifically for this page. -->
    <link rel="stylesheet" type="text/css" href="../Styles/mystyle.css">

    

    <!-- BEGIN: KOALA MODIFICATIONS -->
    <link rel='stylesheet'
        href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css' />
    <!-- END: KOALA MODIFICATIONS -->

    <link rel='stylesheet'
        href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar-scheduler/1.9.4/scheduler.min.css' />

    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <!-- BEGIN: KOALA MODIFICATIONS -->
    <script type="text/javascript"
        src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <!-- END: KOALA MODIFICATIONS -->

    <script src='https://momentjs.com/downloads/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar-scheduler/1.9.4/scheduler.min.js'></script>

    <!-- Select2 select boxes -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
    
    

    <script src="../Controllers/index.js"></script>
</head>

<body class="background">
    <div class="header">ASRC Federal Desktop Scheduler</div>
    <div class="topnav">
        <ul>
            <li id="admin-button" style="display: none"><a onclick="checkForAdmin();"></a></li>
            <li><a class="nav-btn" href="dashboard.php">My Dashboard</a></li>
            <li><a class="nav-btn" href="legend.html">Legend</a></li>
            <li><a class="helppage nav-btn" href="helppage.html">Help Page</a></li>
            <li><a class="logout nav-btn" onclick="logout()">Logout</a></li>
        </ul>
    </div>
        <div class="row">
        <div class="column_entry">
            <form>
                <div id="welcome"></div>
                <br>
                <br>
              <div style="display: none"> 
                <p class="MultiTextBoxForm">Currently Displayed Schedule:</p>
                <hidden type="text" id="demo" name="demo" disabled> </hidden>
                <input class="currDesk" type="text" id="currDesktop" disabled></input>
              </div>
                <p class="MultiTextBoxForm">Build:</p>

                <select id="Build" name="Builds">
                </select>
                <p class="MultiTextBoxForm">Desktop:</p>
                <select id="Desktop" name="Desktop" ></select>
                <br>
                <br>
                <button style="display: none" id="changeDesktop" type="button" onclick="setDesktop(); submitFunction();">Change Displayed Schedule </button>
            </form>
        </div>

        <div class="column_content" id='calendar'></div>
    </div>


    <div class="footer">
        <!-- <p>Team Elephants 2019, Team Sloths 2019</p> -->
        <script language="javascript">
          sessionStorage.setItem('username', '<?php echo $_SESSION["username"]?>')
          function logout() {
            sessionStorage.clear();
            window.location.href = "login";
          }
          retrieveUser(sessionStorage.username);
        </script>
    </div>

    <!-- BEGIN: KOALA MODIFICATIONS -->
    <div align="left" id="dialog-confirm" title="Time Reservation" style="display:none">
      <div class="form-group row">
        <label for="date" class="col-sm-2 col-form-label">Date:</label>
        <div class="col-sm-10">
          <input type="text" id="date" disabled>
        </div>
      </div>
      <div class="form-group row">
        <label for="time" class="col-sm-2 col-form-label">Time:</label>
        <div class="col-sm-10">
          <input type="text" id="time" name="time" disabled>
        </div>
      </div>
      <div class="form-group row">
          <label for="buildForm" class="col-sm-2 col-form-label">Build:</label>
          <div class="col-sm-4">
            <select type="text" id="buildForm" name="build" required></select>
          </div>
          <label for="desktopForm" class="col-sm-2 col-form-label" style="padding-left: 0">Desktop:</label>
          <div class="col-sm-4">
            <select type="text" id="desktopForm" name="desktop" required disabled></select>
          </div>
      </div>
      <div class="form-group row" style="display: none">
        <label for="reservedBy" class="col-sm-2 col-form-label">Reserved by:</label>
        <div class="col-sm-10">
          <input type="text" id="reservedBy" name="reservedBy" disabled>
        </div>
      </div>
      <div class="form-group row" style="display: none">
         <label for="user" class="col-sm-2 col-form-label">User:</label>
        <div class="col-sm-10">
          <select class="user-dropdown" id="user" type="text" required></select>
        </div>
      </div>
      <div class="form-group row">
         <label for="notes" class="col-sm-2 col-form-label">Notes:</label>
        <div class="col-sm-10">
          <textarea class="form-control" id="notes" rows="3" maxLength="100" style="cursor: text; resize: none;" fixed ></textarea>
        </div>
      </div>
        <!-- <input type="text" id="dtopID" style="display: none" disabled> -->
        <!-- <p><span class="ui-icon ui-icon-info" style="float:left; margin:12px 12px 20px 0;"></span>What would you like to do with this time?</p> -->
    </div>
    <!-- END: KOALA MODIFICATIONS -->
    <div id="tooltip" title="Info Display" style="display: none">
      <table><thead><tr><th>User</th><th>Desktop</th><th>Build</th></tr></thead></table>
    </div>
</body>
</html>
