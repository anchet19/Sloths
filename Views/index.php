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
    <script>
        $(document).ready(BuildCalendar());
    </script>
</head>

<body class="background">
    <div class="header">ASRC Federal Desktop Scheduler</div>
    <div class="topnav">
        <ul>
            <li id="admin-button"><a onclick="checkForAdmin();">My Options</a></li>
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
                <br> 
                <p class="MultiTextBoxForm">Currently Displayed Schedule:</p>
                <hidden type="text" id="demo" name="demo" disabled> </hidden>
                <input class="currDesk" type="text" id="currDesktop" disabled></input>
                <p class="MultiTextBoxForm">Build:</p>
                <select id="Build" name="Builds" onchange="populateDropdown(this.value);">
                </select>
                <p class="MultiTextBoxForm">Desktop:</p>
                <select id="Desktop" name="Desktop"></select>
                <br>
                <br>
                <button id="changeDesktop" type="button" onclick="setDesktop(); submitFunction();">Change Displayed Schedule </button>
            </form>
            <!-- //TODO: HIDDEN UNTIL DIALOG IS COMPLETED (Nasser)-->
            <div class="rel" style="display: none;">
                <form method='post'>
                    Selected Time Slot Information: Date:
                    <input type="text" id="date2" name="date2">
                    <br> Time:
                    <input type="text" id="time2" name="time2">
                    <br> Desktop:
                    <input type="text" id="desktopForm2" name="desktop">
                    <hidden id="desktop"> </hidden>
                    <br> Build:
                    <input type="text" id="buildForm2" name = "build">
                    <hidden id="build"> </hidden>
                    <br> Reserved By:
                    <input type="text" id="reservedBy2" name="reservedBy2">
                    <br>
                    <input type="hidden" id="user" name="user" value="">
                    <button type="button" name="request2" id="request2" onclick="requestSlot();">Request</button>
                    <button type="button" name="release2" id="release2" onclick="releaseSlot();">Release</button>
                </form>
            </div>

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
          populateDropdowns(sessionStorage.username);
          retrieveUser(sessionStorage.username);
        </script>
    </div>

    <!-- BEGIN: KOALA MODIFICATIONS -->
    <div align="left" id="dialog-confirm" title="Time Reservation" style="display:none">
        <label>Date : <input type="text" id="date" disabled></label>
        <label>Time : <input type="text" id="time" name="time" disabled></label>
        <label>Desktop : <input type="text" id="desktopForm" name="desktop" disabled></label>
        <label>Build : <input type="text" id="buildForm" name="build" disabled></label>
        <label>Available : <input type="text" id="available" disabled></label>
        <label>Reserved by : <input type="text" id="reservedBy" name="reservedBy" disabled></label>
        <!-- <p><span class="ui-icon ui-icon-info" style="float:left; margin:12px 12px 20px 0;"></span>What would you like to do with this time?</p> -->
    </div>
    <!-- END: KOALA MODIFICATIONS -->
</body>

</html>
