<?php 
// UI with forms that the administrator can use
// each form is displayed when its corresponding link is clicked
// authors: David Serrano(serranod7), William Geary
// modified: Chris Ancheta, 2019-10-18

session_start();
if(!isset($_SESSION['username'])){
  header("Location: ./404page");
}
?> 
<html>

<head>
  <title>Admin Panel </title>
  <script src="../Utils/docCookies.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!-- <script src="../Controllers/index.js"></script> -->
  <script src="../Controllers/adminPage.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <!-- Highest level of CSS, applies to all pages of the software. -->
    <link rel="stylesheet" href="../Styles/desktop.css">
    
    <!-- CSS specifically for this page. -->
    <link rel="stylesheet" href="../Styles/adminPage.css">
  
    <!-- CSS specifically for this drawing in-page tables. -->
    <link rel="stylesheet" href="../Styles/displayTables.css">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
</head>
<style>
 
</style>

  <!--specifies a header for the administrator page -->
  <body class="background" >
    <div class="header">Admin Page</div>
    <div class="topnav">
        <ul>
          <li><a class="calendar-button" href="index">Calendar</a></li>
          <li><a class="logout" onclick="logout()">Logout</a></li>
        </ul>
    </div>

  <div class="container">
    <div class="row no-gutters mt-2">
      <!--div container for the side menu -->
      <div class="col-md-3">
        <div id="accordion">
          <div class="card">
            <div class="card-header bg-dark" id="headingOne">
              <h5 class="mb-0"></h5>
              <button id="menu-button" class="btn btn-sm btn-block" data-toggle="collapse" data-target="#collapseOne"
                aria-controls="collapseOne">
                Manage Users
              </button>
              </h5>
            </div>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
              <div class="card-body center">
                <a class="dropdown-item btn" href="viewusers.php">View Users</a>
                <a class="dropdown-item btn" onclick="makeVisible('insertUser')" data-toggle="collapse" href="#collapseOne">Insert User</a>
                <a class="dropdown-item btn" onclick="makeVisible('updateUser')" data-toggle="collapse" href="#collapseOne">Update User</a>
                <a class="dropdown-item btn" onclick="makeVisible('changeUserPassword')" data-toggle="collapse" href="#collapseOne">Change User Password</a>
                <a class="dropdown-item btn" onclick="makeVisible('userPermissions')" data-toggle="collapse" href="#collapseOne">Edit User Privileges</a>
                <a class="dropdown-item btn" onclick="makeVisible('deleteUser')" data-toggle="collapse" href="#collapseOne">Delete User</a>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header bg-dark" id="headingTwo">
              <h5 class="mb-0">
                <button id="menu-button" class="btn btn-sm btn-block" data-toggle="collapse" data-target="#collapseTwo"
                  aria-controls="collapseTwo">
                  Manage Desktops
                </button>
              </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body center">
                <a class="dropdown-item btn" href="viewdesktops.php">View Desktops</a>
                <a class="dropdown-item btn" onclick="makeVisible('insertDesktop')" data-toggle="collapse" href="#collapseTwo">Insert Desktop</a>
                <a class="dropdown-item btn" onclick="makeVisible('deleteDesktop')" data-toggle="collapse" href="#collapseTwo">Delete Desktop</a>
                <a class="dropdown-item btn" onclick="makeVisible('blockDesktops')" data-toggle="collapse" href="#collapseTwo">Block Desktops</a>

              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header bg-dark" id="headingThree">
              <h5 class="mb-0">
                <button id="menu-button" class="btn btn-sm btn-block" data-toggle="collapse"
                  data-target="#collapseThree" aria-controls="collapseThree">
                  Manage Builds
                </button>
              </h5>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
              <div class="card-body center">
                <a class="dropdown-item btn" href="viewbuilds.php">View Builds</a>
                <a class="dropdown-item btn" onclick="makeVisible('insertBuild')" data-toggle="collapse" href="#collapseThree">Insert Build</a>
                <a class="dropdown-item btn" onclick="makeVisible('deleteBuild')" data-toggle="collapse" href="#collapseThree">Delete Build</a>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header bg-dark" id="headingFour">
              <h5 class="mb-0">
                <button id="menu-button" class="btn btn-sm btn-block" data-toggle="collapse" data-target="#collapseFour"
                  aria-controls="collapseFour">
                  Manage Installations
                </button>
              </h5>
            </div>
            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
              <div class="card-body ">
                <a class="dropdown-item btn" href="viewinstallations.php">View Installations</a>
                <a class="dropdown-item btn" onclick="makeVisible('insertInstallation')" data-toggle="collapse" href="#collapseFour">Insert Installation</a>
                <a class="dropdown-item btn" onclick="makeVisible('deleteInstallation')" data-toggle="collapse" href="#collapseFour">Delete Installation</a>
                
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header bg-dark" id="headingFive">
              <h5 class="mb-0">
                <button id="menu-button" class="btn btn-sm btn-block" data-toggle="collapse" data-target="#collapseFive"
                  aria-controls="collapseFive">
                  Manage Reservations
                </button>
              </h5>
            </div>
            <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
              <div class="card-body center">
                <a class="dropdown-item btn" href="viewreservations.php">View Reservations</a>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header bg-dark" id="headingSix">
              <h5 class="mb-0">
                <button id="menu-button" class="btn btn-sm btn-block" data-toggle="collapse" data-target="#collapseSix"
                  aria-controls="collapseSix">
                  Metrics
                </button>
              </h5>
            </div>
            <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
              <div class="card-body center">
                <a class="dropdown-item btn" onclick="makeVisible('desktopMetrics')" data-toggle="collapse" href="#collapseSix">Desktop Metrics</a>
                <a class="dropdown-item btn" onclick="makeVisible('buildMetrics')" data-toggle="collapse" href="#collapseSix">Build Metrics</a>
                <a class="dropdown-item btn" onclick="makeVisible('outcomeMetrics')" data-toggle="collapse" href="#collapseSix">Outcome Metrics</a>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <!-- Put the callback to the schedule function in onClick -->
            <button type="reset" id="submit-schedule" onclick="createSchedule()" class="btn btn-primary btn-block">Submit
              Schedule</button>
          </div>
        </div>
      </div>

      <!-- Container for the Form Display-->
      <div class="col-md-8 offset-md-1 border border-dark">
        <!-- Empty for initial display-->
        <div id="initialFormView" style="display: block"></div>
        <!--specifies which Build an admin would like to delete -->
        <div display="none" id="deleteBuild" style="display: none">
          <form>
            <div class="form-group row justify-content-center">
              <div class="col-md-4">
                <label for="deleteBuildSelect">Delete Builds</label>
                <select class="form-control" id="deleteBuildSelect" name="build-select"></select>
              </div>
            </div>
            <div class="form-group row justify-content-center">
              <div class="col-md-4">
                <button class="form-control btn btn-danger" type="button" onclick="doDeleteBuild(this.form)">Delete</button>
              </div>
            </div>
          </form>
        </div>
        <!--specifies which User the admin would like to delete  -->
        <div id="deleteUser" style="display: none">
          <form>
            <div class="form-group row justify-content-center">
              <div class="col-md-4">
                <label for="deleteUserSelect">Select User Users
                  <select class="user-dropdown" id="deleteUserSelect" style="width: 100%" name="user-select"><option></option></select>
                </label>
              </div>
            </div>
            <div class="form-group row justify-content-center">
              <div class="col-md-4">
                <button class="form-control btn btn-danger" type="button" onclick="doDeleteUser(this.form)">Delete</button>
              </div>
            </div>
          </form>
        </div>
        <!--specifies which desktop the admin would like to delete -->
        <div id="deleteDesktop" style="display: none">
          <form>
            <div class="form-group row justify-content-center">
              <div class="col-md-5">
                <label for="deleteDesktopSelect">Delete Desktops</label>
                <select class="form-control" id="deleteDesktopSelect" name="desktop-select"><option></option></select>
              </div>
            </div>
            <div class="form-group row justify-content-center">
              <div class="col-md-4">
                <button class="form-control btn btn-danger" type="button" onclick="doDeleteDesktop(this.form)">Delete</button>
              </div>
            </div>
          </form>
        </div>
        <!--specifies which intsallation an admin would like to delete -->
        <!-- TO-DO LIST INSTALLATIONS AND ADD BUTTONS TO REMOVE-->
        <div id="deleteInstallation" style="display: none">
          <form>
            <div class="form-group row justify-content-center">
              <div class="col-md-5">
                <label for="deleteInstallationSelectB">Select Build</label>
                <select class="form-control" id="deleteInstallationSelectB" name="build-select"><option></option></select>
              </div>

              <div class="col-md-5">
                <label for="deleteInstallationSelectD">Select Desktop</label>
                <select class="form-control" id="deleteInstallationSelectD" name="desktop-select"><option></option></select>
              </div>
            </div>
            <div class="form-group row justify-content-center">
              <div class="col-md-4">
                <button class="form-control btn btn-danger" type="button" onclick="doDeleteInstallation(this.form)">Delete</button>
              </div>
            </div>
          </form>
        </div>

        <div id="insertBuild" style="display: none">
          <form>
            <div class="form-group row justify-content-center">
              <div class="col-md-4">
                <label for="build">Insert Builds</label>
                <input class="form-control" placeholder="Build Name" type="text" id="build" name="build"></input>
              </div>
            </div>
            <div class="form-group row justify-content-center">
              <div class="col-md-4">
                <button class="form-control btn btn-success" type="button" onclick="doInsertBuild(this.form)">Insert Build</button>
              </div>
            </div>
          </form>
        </div>

        <div id="insertDesktop" style="display: none">
          <form>
            <div class="form-row justify-content-center">
              <div class="form-group">
                <label for="desktop">Insert Desktops</label>
                <input class="form-control" placeholder="Desktop Name" type="text" id="desktop" name="desktop"></input>
                <label for="dtopColor">Choose Color</label>
                <input class="form-control" id="dtopColor" name="color" type="color" style="height: 3em; padding: 4">
                <label>&nbsp;
                  <button class="form-control btn btn-success" type="button" onclick="doInsertDesktop(this.form)">Insert Desktop</button>
                </label>
              </div>
            </div>
            <div class="form-group row justify-content-center">
              <div class="col-md-5">
              </div>
            </div>
          </form>
        </div>

        <div id="insertUser" style="display: none">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <form>
                <div class="form-group">
                  <label for="firstName">First Name</label>
                  <input class="form-control" type="text-sm"  name="firstName" id="firstName" placeholder="First Name" />
                </div>
                <div class="form-group">
                  <label for="lastName">Last Name</label>
                  <input class="form-control" type="text-sm"  name="lastName" id="lastName" placeholder="Last Name" />
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input class="form-control" type="email" name="email" id="email" placeholder="Email"/>
                </div>
                <div class="form-group">
                  <label for="username">Username</label>
                  <input class="form-control" type="text-sm" name="username" id="username" placeholder="Username" />
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input class="form-control" type="password" name="password" id="password" placeholder="Password"/>
                </div>
                <div class="form-group">
                  <label for="newAdmin">Authorization Level</label>
                  <select class="form-control-sm" id="newAdmin">
                    <option value="0">User</option>
                    <option value="1">Manager</option>
                    <option value="2">Admin</option>         
                  </select>
                </div>
                <button class="btn btn-block btn-success" onsubmit="" onclick="doInsertUser(this.form)">Add User</button>
              </form>
            </div>
          </div>
        </div>

        <div id="insertInstallation" style="display: none">
          <div class="row justify-content-center">
            <div class="col-md-4">
              <form>
                <div class="form-group">
                  <label for="insertBuildSelect">Select Build</label>
                  <select id="insertBuildSelect" class="form-control" type="text" name="build-select">
                    <option></option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="insertDesktopSelect">Select Desktop</label>
                  <select id="insertDesktopSelect" class="form-control" name="desktop-select">
                    <option></option>
                  </select>
                </div>
                <button class="btn btn-success" type="button" onclick="doInsertInstallation(this.form)">Insert
                  Installation</button>
              </form>
            </div>
          </div>
        </div>

        <div id="updateUser" style="display: none">
          <div class="row justify-content-center">
            <div class="col-md-4">
              <form>
                <div class="form-group">
                  <label for="updateUserSelect">Select User
                    <select id="updateUserSelect" class="user-dropdown" placeholder="Select User" style="width: 100%" type="text" name="user-select">
                      <option></option>
                    </select>
                  </label>
                </div>
                <div class="form-group">
                  <label for="oldAdmin">Authorization Level</label>
                  <select id="oldAdmin" class="form-control" name="admin">
                    <option value="0">User</option>
                    <option value="1">Manager</option>
                    <option value="2">Admin</option>                
                  </select>
                </div>
                <button class="btn btn-success" type="button" onclick="doUpdateUser(this.form)">Update User</button>
              </form>
            </div>
          </div>
        </div>

        <div id="changeUserPassword" style="display: none">
          <div class="row justify-content-center">
            <div class="col-md-4">
              <form id="newPasswordForm" method="post">
                <div class="form-group">
                  <label for="changePasswordSelect">Select User
                    <select id="changePasswordSelect" class="user-dropdown" placeholder="Select User" style="width: 100%" type="text" name="user-select">
                      <option></option>
                    </select>
                  </label>
                </div>
                <div class="form-group" >
                  <label for="newPassword">New Password</label>
                  <input id="newPassword" class="form-control" name="newPassword" />
                  <label for="newPassword_confirm">Confirm Password</label>
                  <input id="newPassword_confirm" class="form-control" name="newPassword_confirm" />
                </div>
                <input class="btn btn-success" name="admin_change_password" type="submit" />
              </form>
            </div>
          </div>
        </div> <!-- end change password -->
        <div id="userPermissions" style="display: none">
          <div class="row justify-content-center">
            <div class="col-md-4">
              <div class="form-group">
                <form id="privForm">
                  <label for="users"> Select User
                    <select class="user-dropdown" id="users" name="user-select" placeholder="Enter a Username" style="width: 100%"><option></option></select>
                  </label>
              </div>
                  <div id="privilegesCheckbox" class="col offset-md-1"></div>        
                </form>
              <div id="privSubmit" class="col offset-md-1"></div>
            </div>              
          </div>
        </div> <!-- end userPermissions -->

        <div id="desktopMetrics" style="display: none">
          <form method="post" action="../api/get_desktop_metrics.php" name="desktopMetricsForm" id="desktopMetricsForm">
            <div class="form-row justify-content-center">
              <div class="col offset-md-1">
                <div class="form-group">
                  <label class="form-label MultiTextBoxForm" for="startDate">Start date:</label>
                  <input class="form-control-sm" type="date" id="startDate" name="startDate" placeholder="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>" > 
                </div>
              </div>
              <div class="col offset-md-1">
                <div class="form-group">
                  <label class="form-label MultiTextBoxForm" for="endDate">End date:</label>
                  <input class="form-control-sm" type="date" id="endDate" name="endDate" placeholder="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>" >
                </div>
              </div>
              <div class="col offset-md-1">
                <label>&nbsp;</label> <!-- Alligns button with form input fields -->
                <input class="btn btn-sm form-control btn-success " type="submit" name="submit" value="Submit">
              </div>
            </div>
          </form>
          <div class="col" id="desktopMetricsTable">
          </div>
        </div> <!-- end desktopMetrics -->
        <!-- Start buildMetrics -->
        <div id="buildMetrics" style="display: none">
          <form method="post" action="./adminPage.php" name="buildMetricsForm" id="buildMetricsForm">
            <div class="form-row justify-content-center">
              <div class="col offset-md-1">
                <div class="form-group">
                  <label class="form-label MultiTextBoxForm" for="startDate">Start date:</label>
                  <br>
                  <input class="form-control-sm" type="date" id="startDate" name="startDate" placeholder="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>" > 
                </div>
                <br>
              </div>
              <div class="col offset-md-1">
                <div class="form-group">
                  <label class="form-label MultiTextBoxForm" for="endDate">End date:</label>
                  <br>
                  <input class="form-control-sm" type="date" id="endDate" name="endDate" placeholder="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>" >
                </div>
                <br>
              </div>
              <div class="col offset-md-1">
                <label>&nbsp;</label> <!-- Alligns button with form input fields -->
                <input class="btn btn-sm form-control btn-success " type="submit" name="submit" value="Submit">
              </div>
            </div>
          </form>
          <div class="col" id="buildMetricsTable">
          </div>
        </div> <!-- End buildMetrics -->
        <!-- Start outcome metrics -->
        <div id="outcomeMetrics" style="display: none">
          <form method="post" action="../api/get_outcome_metrics.php" name="outcomeMetricsForm" id="outcomeMetricsForm">
            <div class="form-row justify-content-center">
              <div id="outcome-group">
              
                <div class="form-group">
                  <fieldset id="filter">                  
                    <input type="radio" name="filter" id="filter" value="desktop"> By Desktop </input>
                    <input type="radio" name="filter" id="filter" value="department">By Department </input>
                  </fieldset>
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
                    <input class="btn btn-sm form-control btn-success " type="submit" name="submit" value="Submit" id="outcomeSubmit">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="col" id="outcomeMetricsTable">
          </div> <!-- End outcome metrics -->
          <div id="blockDesktops" style="display: none">
            <div class="row justify-content-center">
              <form id="block-desktops" action="adminPage.php" method="post">
                <div class="form-row justify-content-center">
                  <label class="form-label" for="date-select" >Date:</label>
                  <input class="form-control" id="date-select" name="date-select" type="date" 
                    value="<?php echo date('Y-m-d')?>" pattern="\d{4}-\d{2}-\d{2}" style="text-align: center" required>
                </div>
                <div class="form-row">
                  <label class="form-label" for="startTime-select" >Start:
                    <input class="form-control" id="startTime-select" name="startTime-select" type="time" 
                      value="06:00:00" min="06:00:00" max="21:00:00" step="10800" required>
                  </label>
                  <label class="form-label" for="endTime-select" >End:
                    <input class="form-control" id="endTime-select" name="endTime-select" type="time" 
                      value="20:59:59" min="05:59:59" max="20:59:59" step="10800" required>
                  </label>
                </div>
                <div class="form-row justify-content-center">
                  <label>Desktops:</label>
                  <select class="form-control-sm" id="dtop-select" name="desktop-select[]" multiple="true" 
                    size="auto" style="text-align: center" required></select>
                </div>
                <div class="form-row justify-content-center">
                  <label>Comment:</label>
                  <textarea class="form-control-sm" id="comment" name="comment" rows="4" maxLength="200" style="cursor: text"></textarea>
                </div>
                <br><br>
                <input class="btn btn-sm form-control btn-success" name="blockDtop-submit" value="submit" type="submit">
              </form>
            </div>
          </div>
        </div> <!-- End container for form display -->

        

  <script language="javascript">
    sessionStorage.setItem('username', '<?php echo $_SESSION["username"]?>')
    function logout() {
      sessionStorage.clear();
      window.location.href = "login";
    }
  </script>

</body>

</html>