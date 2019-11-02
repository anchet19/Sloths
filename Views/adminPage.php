<?php 
// UI with forms that the administrator can use
// each form is displayed when its corresponding link is clicked
// authors: David Serrano(serranod7), William Geary
// modified: Chris Ancheta, 2019-10-18
?>
<html>

<head>
  <title>Admin Panel </title>
  <script src="../Utils/docCookies.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="../Controllers/index.js"></script>
  <script src="../Controllers/adminPage.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="../Styles/adminPage.css">
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
</head>
<style>
 
</style>

<body>
  <!--specifies a header for the administrator page -->
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
                <a class="dropdown-item " href="viewusers.php">View Users</a>
                <a class="dropdown-item btn" onclick="makeVisible('insertUser')" data-toggle="collapse" href="#collapseOne">Insert User</a>
                <a class="dropdown-item btn" onclick="makeVisible('updateUser')" data-toggle="collapse" href="#collapseOne">Update User</a>
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
                <a class="dropdown-item" href="viewdesktops.php">View Desktops</a>
                <a class="dropdown-item btn" onclick="makeVisible('insertDesktop')" data-toggle="collapse" href="#collapseTwo">Insert Desktop</a>
                <a class="dropdown-item btn" onclick="makeVisible('deleteDesktop')" data-toggle="collapse" href="#collapseTwo">Delete Desktop</a>
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
                <a class="dropdown-item" href="viewbuilds.php">View Builds</a>
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
                <a class="dropdown-item " href="viewreservations.php">View Reservations</a>
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
                <label for="build">Delete Builds</label>
                <select class="form-control" id="deleteBuildSelect" name="build"></select>
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
                <label for="build">Delete Users</label>
                <select class="form-control" id="deleteUserSelect" name="user"></select>
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
                <label for="build">Delete Desktops</label>
                <select class="form-control" id="deleteDesktopSelect" name="Desktop"></select>
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
                <label for="build">Select Build</label>
                <select class="form-control" id="deleteInstallationSelectB" name="build"></select>
              </div>
            </div>
            <div class="form-group row justify-content-center">
              <div class="col-md-5">
                <label for="desktop">Select Desktop</label>
                <select class="form-control" id="deleteInstallationSelectD" name="desktop"></select>
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
            <div class="form-group row justify-content-center">
              <div class="col-md-5">
                <label for="build">Insert Desktops</label>
                <input class="form-control" placeholder="Desktop Name" type="text" id="desktop" name="desktop"></input>
              </div>
            </div>
            <div class="form-group row justify-content-center">
              <div class="col-md-5">
                <button class="form-control btn btn-success" type="button" onclick="doInsertDesktop(this.form)">Insert Desktop</button>
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
                  <input class="form-control type=" text" name="firstName" id="firstName" placeholder="First Name" />
                </div>
                <div class="form-group">
                  <label for="lastName">Last Name</label>
                  <input class="form-control" type="text" name="lastName" id="lastName" placeholder="Last Name" />
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input class="form-control" type="email" name="email" id="email" placeholder="Email"/>
                </div>
                <div class="form-group">
                  <label for="username">Username</label>
                  <input class="form-control" type="text" name="username" id="username" placeholder="Username" />
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input class="form-control" type="password" name="password" id="password" placeholder="Password"/>
                </div>
                <div class="form-group">
                  <label for="newAdmin">Admin</label>
                  <select class="form-control" id="newAdmin">
                    <option value="1">Admin</option>
                    <option value="0">Not Admin</option>
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
                  <select id="insertBuildSelect" class="form-control" type="text" name="build">
                    <option selected>Build Select</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="insertDesktopSelect">Select Desktop</label>
                  <select id="insertDesktopSelect" class="form-control" name="desktop">
                    <option selected>Build Select</option>
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
                  <label for="updateUserSelect">Select User</label>
                  <select id="updateUserSelect" class="form-control" placeholder="Select User" type="text" name="user">
                    <option selected>User Select</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="oldAdmin">Select Desktop</label>
                  <select id="oldAdmin" class="form-control" name="admin">
                    <option value="1">Admin</option>
                    <option value="0">Not Admin</option>
                  </select>
                </div>
                <button class="btn btn-success" type="button" onclick="doUpdateUser(this.form)">Update User</button>
              </form>
            </div>
          </div>
        </div>

        <div id="desktopMetrics" style="display: none">
          <form method="post" action="../api/get_desktop_metrics.php" name="desktopMetricsForm" id="desktopMetricsForm">
            <div class="form-row justify-content-center">
              <div class="col offset-md-1">
                <div class="form-group">
                  <label class="form-label" for="start">Start date:</label>
                  <input class="form-control-sm" type="date" id="startDate" name="startDate" placeholder="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>" > 
                </div>
              </div>
              <div class="col offset-md-1">
                <div class="form-group">
                  <label class="form-label" for="end">End date:</label>
                  <input class="form-control-sm" type="date" id="endDate" name="endDate" placeholder="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>" >
                </div>
              </div>
              <div class="col offset-md-1">
                <label>&nbsp;</label> <!-- Alligns button with form input fields -->
                <input class="btn btn-sm btn-success form-control" type="submit" name="submit" value="Submit">
              </div>
            </div>
          </form>
          <div class="col" id="desktopMetricsTable">
          </div>
        </div>
      </div>
    </div>
    
  </div>
  <script language="javascript">
    function logout() {
        docCookies.removeItem("username");
        docCookies.removeItem("password");
        window.location.href = "login.html";
    };
  </script>

</body>

</html>