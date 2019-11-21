<?php 
session_start();
if(!isset($_SESSION['username'])){
  header("Location: ./404page");
}
?>

<!DOCTYPE html>

<!-- UI with forms that the manager can use
 each form is displayed when its corresponding link is 
 clicked -->
<!-- Author: Kyle Kaminski -->

<html lang="en">

<head>
    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="../Styles/desktop.css">
    <link rel="stylesheet" type="text/css" href="../Styles/manager.css">
    <link rel="stylesheet" type="text/css" href="../Styles/displayTables.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"></script>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"></script>

    

    <!-- Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src='https://momentjs.com/downloads/moment.min.js'></script>
    <script src='../Controllers/manager.js'></script>

    <title>Manager Options</title>
</head>

<body class="background" background="../Images/Background.png">
    <div class="header">Manager Options</div>
    <div class="topnav">
        <ul class="top">
            <li><a href="index">Calendar</a><a id="metricsButton" onclick="makeVisible('metrics-container')">Metrics</a></li>
            <li><a class="logout" onclick="logout()">Logout</a></li>
        </ul>
    </div>

    <!-- Delete this when we start working on this page. -->
    <div>
    <!-- insert hidden div -->
        <div class="col" id="metrics-container" style="display: none">
        <form method="post" name="managerUserForm" id="managerUserForm">
            <div class="form-row justify-content-center">
              <div id="outcome-group">              
                <div class="form-group">                                    
                    <input type="radio" name="filter" id="filter" value="userTotals"> User Totals </input>
                    <input type="radio" name="filter" id="filter" value="Build"> By Build </input> <br>                  
                  <label class="form-label MultiTextBoxForm" for="startDate">Start date:</label>
                  <br>
                  <input class="form-control-sm" type="date" id="startDate" name="startDate" placeholder="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>" >
                  <br>
                  <label class="form-label MultiTextBoxForm" id= endDateLabel for="endDate">End date:</label>
                  <br>
                  <input class="form-control-sm" type="date" id="endDate" name="endDate" placeholder="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>" >
                  <br>
                  <div class="col offset-md-1">
                    <label>&nbsp;</label> <!-- Alligns button with form input fields -->
                    <br>
                    <input class="btn btn-sm form-control btn-success " type="button" name="button" value="Submit" id="managerUserSubmit" onclick="handleUserMetricsSubmit()">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="col" id="managerUserMetricsTable">
          </div>
        </div>
        <p class="WIP">Under Construction.
            <br></br>
            Expected updates coming soon.
        </p>
    </div>
</body>

</html>