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
    <script src='../Utils/logout.js'></script>

    <title>Manager Options</title>
</head>

<body>
  <div class="header">Manager Options</div>
  <div class="topnav">
      <ul class="top">
          <li><a href="index">Calendar</a><a id="metricsButton" onclick="makeVisible('metrics-container')">Metrics</a></li>
          <li><a class="logout" onclick="logout()">Logout</a></li>
      </ul>
  </div>
  <!-- insert hidden div -->
  <div class="container" id="metrics-container" style="display: none">
    <div class="row row-center form-container">
      <form method="post" name="managerUserForm" id="managerUserForm">
          <div class="form-row">
            <div class="form-group">        
              <input type="radio" name="filter" id="filter" value="userTotals"> User Totals </input>
              <input type="radio" name="filter" id="filter" value="Build"> By Build </input>
            </div>
            <div class="form-group">        
              <label class="form-label MultiTextBoxForm" for="startDate">Start date:</label>
              <input class="form-control-sm" type="date" id="startDate" name="startDate" placeholder="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>" >
            </div>
            <div class="form-group">        
              <label class="form-label MultiTextBoxForm" id= endDateLabel for="endDate">End date:</label>
              <input class="form-control-sm" type="date" id="endDate" name="endDate" placeholder="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>" >
            </div>
            <div class="form-group">        
              <input type="button" name="submit" value="Submit" id="managerUserSubmit" onclick="handleUserMetricsSubmit()">
            </div>
          </div>         
        </form>
    </div>
    <br>
    <div class="row" id="managerUserMetricsTable"></div>
  </div>
</body>

</html>