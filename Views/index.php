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
    <link rel="stylesheet" href="../Styles/desktop.css">

    <!-- CSS specifically for this page. -->
    <link rel="stylesheet" type="text/css" href="../Styles/mystyle.css">

    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.css' />
    <style>
        .fc-time-grid .fc-slats td {
            height: 5.5em;
            border-bottom: 0
        }
    </style>

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

    <script src="../Controllers/index.js"></script>
</head>

<body class="background">
    <div class="header">ASRC Federal Desktop Scheduler</div>
    <div class="topnav">
        <ul>
            <li id="admin-button"><a onclick="checkForAdmin();">Admin</a></li>
            <li><a href="managerPage.php">Manager</a></li> <!-- Update Me! -->
            <li><a href="dashboard.php">My Dashboard</a></li>
            <li><a href="legend.html">Legend</a></li>
            <li><a class="helppage" href="helppage.html">Help Page</a></li>
            <li><a class="logout" onclick="logout()">Logout</a></li>
        </ul>
    </div>

    <div class="row">
        <div class="column_entry">
            <form>
                <div id="welcome"></div>
                <br>
                <br> Currently Displayed Schedule:
                <hidden type="text" id="demo" name="demo" disabled> </hidden>
                <input class="currDesk" type="text" id="currDesktop" disabled></input>
                <br>
                <br>

                <br> Build:

                <select  id="Build" name="Builds">
                </select>

                <br> Desktop:

                <select  id="Desktop" name="Desktop" disabled>

                </select>

                <br>
                <br>

                <button id="changeDesktop" type="button" onclick="setDesktop(); submitFunction();">Change Displayed
                    Schedule </button>
                <br> <br> <br> <br> <br>
            </form>
            <!-- //TODO: HIDDEN UNTIL DIALOG IS COMPLETED (Nasser)-->

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
        <label>Date : <input type="text" id="date" disabled></label>
        <label>Time : <input type="text" id="time" name="time" disabled></label>
        <label>Build : <select type="text" id="buildForm" name="build" ></select></label>
        <label>Desktop : <select type="text" id="desktopForm" name="desktop" disabled></select></label>
        <input type="text" id="dtopID" style="display: none" disabled>
        <label>Reserved by : <input type="text" id="reservedBy" name="reservedBy" disabled></label>
        <input id="user" type="text" style="display: none" disabled></input>
        <!-- <p><span class="ui-icon ui-icon-info" style="float:left; margin:12px 12px 20px 0;"></span>What would you like to do with this time?</p> -->
    </div>
    <!-- END: KOALA MODIFICATIONS -->
</body>
</html>
