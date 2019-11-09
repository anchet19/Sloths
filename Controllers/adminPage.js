var lastVisible = "initialFormView";
var username = docCookies.getItem("username");
var password = docCookies.getItem("password");

retrieveUser(username, password);
/**
 * Waits for the page to be ready and then sets a new event listener for
 * the desktopMetricsForm submission, preventing a redirect.
 */
$(document).ready(function () {

  const desktopMetricsForm = document.getElementById("desktopMetricsForm");
  desktopMetricsForm.addEventListener('submit', function (event) {
    event.preventDefault();
    handleDesktopMetricsSubmit();
  })
  fetchDropdownValues();
  //converts user-dropdown classes to select2 format
  $('.user-dropdown').select2();
  // occurs when user is selected from the select2 dropdown
  $('.user-dropdown').on('select2:select', function (e) {
    var data = e.params.data;
    fetch('../api/get_user_privileges', {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: $.param({
        "user" : data.text
      })
  
    }).then(function (response) {
      response.json().then(function (data) {
        privileges = document.getElementById("privilegesCheckbox");
        privileges.innerHTML = ''; 
  
        let checkbox;
        let label;
        data.forEach(function (row) {          
          
          checkbox = document.createElement('input');
          label = document.createElement('label');
          checkbox.setAttribute("type", "checkbox");
          
          checkbox.id = row.name + "checkbox";
          label.setAttribute("for", checkbox.id);
          label.textContent = row.name;
           if(row.allowed == 1){
            checkbox.checked = true;
          }
          
          
          label.text = row.name;
          checkbox.value = row.name;
          checkbox.name = "privCheckbox";
          
          privileges.append(label);
          privileges.append(checkbox);  
  
        });
        if(!$('#privbut').length){
        privButton = document.getElementById("privSubmit");
        newButton = document.createElement('button');
        newButton.textContent = "Submit";
        newButton.setAttribute("id","privbut")
        newButton.setAttribute("class","btn btn-success");
        newButton.setAttribute("onclick", "savePrivileges(document.getElementById('privForm') )");
        privButton.append(newButton);
        }
      });
    });
    
    
});
  
})

/**
 * Handles the desktopMetricsForm submit event.
 * 
 */
function handleDesktopMetricsSubmit(){
  // Get the form
  const desktopMetricsForm = document.getElementById("desktopMetricsForm");
  // Format the form data -- Content-Type: application/x-www-form-urlencoded
  const formattedFormData = new FormData(desktopMetricsForm);
  desktopMetricsPostData(formattedFormData);
}

/**
 * Fetch the Desktop metrics HTML markup using the javascript Fetch API
 * and update the DOM
 * @param {FormData} formattedFormData The data from the form
 */
async function desktopMetricsPostData(formattedFormData) {
  const response = await fetch('../api/get_desktop_metrics.php', {
    method: 'POST',
    body: formattedFormData
  });
  const data = await response.text();
  document.getElementById("desktopMetricsTable").innerHTML = data;
}

// Markups
const DeleteBuildMarkup = `
        <div display="none" id="deleteBuild" style="display: block">
          <form>
            Build: <select id="deleteBuildSelect" name="build"></select>
            <br> <br>
            <button type="button" onclick="doDeleteBuild(this.form)">Delete Build</button>
          </form>
        </div>
      `

//changes the visibility state of a form

function makeVisible(id) {
  document.getElementById(lastVisible).style.display = "none";
  document.getElementById(id).style.display = "block";
  lastVisible = id;
}

//fetches and populates all of the dropdowns asyncronhously

function fetchDropdownValues() {
  fetch('../api/get_builds.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "password": password
    })

  }).then(function (response) {
    response.json().then(function (data) {
      buildSelect = document.getElementById("deleteBuildSelect");
      buildSelect.innerHTML = '';

      installationSelectB = document.getElementById("deleteInstallationSelectB");
      installationSelectB.innerHTML = '';

      insertBuildSelect = document.getElementById("insertBuildSelect");
      insertBuildSelect.innerHTML = '';


      let option;
      data.forEach(function (row) {
        option = document.createElement('option');
        option.text = row.name;
        option.value = row.b_num;
        buildSelect.add(option);

        option = document.createElement('option');
        option.text = row.name;
        option.value = row.b_num;
        installationSelectB.add(option);


        option = document.createElement('option');
        option.text = row.name;
        option.value = row.b_num;
        insertBuildSelect.add(option);

      });

    });
  });
  fetch('../api/get_desktops.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "password": password
    })

  }).then(function (response) {
    response.json().then(function (data) {
      desktopSelect = document.getElementById("deleteDesktopSelect");
      desktopSelect.innerHTML = '';

      installationSelectD = document.getElementById("deleteInstallationSelectD");
      installationSelectD.innerHTML = '';

      insertDesktopSelect = document.getElementById("insertDesktopSelect");
      insertDesktopSelect.innerHTML = '';

      let option;
      data.forEach(function (row) {
        option = document.createElement('option');
        option.text = row.name;
        option.value = row.dtop_id;
        desktopSelect.add(option);

        option = document.createElement('option');
        option.text = row.name;
        option.value = row.dtop_id;
        installationSelectD.add(option);

        option = document.createElement('option');
        option.text = row.name;
        option.value = row.dtop_id;
        insertDesktopSelect.add(option);


      });

    });
  });
  fetch('../api/get_users.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "password": password
    })

  }).then(function (response) {
    response.json().then(function (data) {
      userSelect = document.getElementById("deleteUserSelect");
      userSelect.innerHTML = '';

      userSelect2 = document.getElementById("updateUserSelect");
      userSelect2.innerHTML = '';

      userSelect3 = document.getElementById("users");
      userSelect3.innerHTML = '';
      
      let option;
      data.forEach(function (row) {
        option = document.createElement('option');
        option.text = row.username;
        option.value = row.user_num;
        userSelect.add(option);

        option = document.createElement('option');
        option.text = row.username;
        option.value = row.user_num;
        userSelect2.add(option);

         option = document.createElement('option');
         option.text = row.username;
         option.value = row.user_num;
         userSelect3.add(option);
      });

    });
  });

}


//function uses rest api to send a delete build request to backend.
function doDeleteBuild(form) {
  fetch('../api/delete_build.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "password": password,
      "build": form.deleteBuildSelect.value
    })

  }).then(function (response) {
    response.json().then(function (data) {
      if (data.result) {
        alert("Build Deleted");
        fetchDropdownValues();
      } else {
        alert("Something Went Wrong");
      }

    });
  });
}

//function uses rest api to send a delete desktop request to backend.
function doDeleteDesktop(form) {
  fetch('../api/delete_desktop.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "password": password,
      "desktop": form.deleteDesktopSelect.value
    })

  }).then(function (response) {
    response.json().then(function (data) {
      if (data.result) {
        alert("Desktop Deleted");
        fetchDropdownValues();
      } else {
        alert("Something Went Wrong");
      }

    });
  });
}
//function uses rest api to send a delete user request to backend.
function doDeleteUser(form) {
  fetch('../api/delete_user.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "password": password,
      "user": form.deleteUserSelect.value
    })

  }).then(function (response) {
    response.json().then(function (data) {
      if (data.result) {
        alert("User Deleted");
        fetchDropdownValues();
      } else {
        alert("Something Went Wrong");
      }

    });
  });
}

//function uses rest api to send a delete installation request to backend.

function doDeleteInstallation(form) {
  fetch('../api/delete_installation.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "password": password,
      "build": form.deleteInstallationSelectB.value,
      "desktop": form.deleteInstallationSelectD.value
    })

  }).then(function (response) {
    response.json().then(function (data) {
      if (data.result) {
        alert("Installation Deleted");
        fetchDropdownValues();
      } else {
        alert("Something Went Wrong");
      }

    });
  });
}

//function uses rest api to send a insert build request to backend.

function doInsertBuild(form) {
  fetch('../api/insert_build.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "password": password,
      "build": form.build.value
    })

  }).then(function (response) {
    response.json().then(function (data) {
      if (data.result) {
        alert("Build Inserted");
        fetchDropdownValues();
      } else {
        alert("Something Went Wrong");
      }

    });
  });
}

//function uses rest api to send a insert desktop request to backend.
function doInsertDesktop(form) {
  fetch('../api/insert_desktop.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "password": password,
      "desktop": form.desktop.value
    })

  }).then(function (response) {
    response.json().then(function (data) {
      if (data.result) {
        alert("Desktop Inserted");
        fetchDropdownValues();
      } else {
        alert("Something Went Wrong");
      }

    });
  });
}
//function uses rest api to send a insert installation request to backend.
function doInsertInstallation(form) {
  fetch('../api/insert_installation.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "password": password,
      "desktop": form.insertDesktopSelect.value,
      "build": form.insertBuildSelect.value
    })

  }).then(function (response) {

    response.json().then(function (data) {
      if (data.result) {
        alert("Installation Inserted");
        fetchDropdownValues();
      } else {
        alert("Something Went Wrong");
      }

    });
  });
}
//function uses rest api to send a insert user request to backend.
function doInsertUser(form) {
  fetch('../api/insert_user.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "password": password,
      "newUsername": form.username.value,
      "newPassword": form.password.value,
      "firstName": form.firstName.value,
      "lastName": form.lastName.value,
      "email": form.email.value,
      "admin": form.newAdmin.value
    })

  }).then(function (response) {

    response.json().then(function (data) {
      if (data.result) {
        alert("User Inserted");
        fetchDropdownValues();
      } else {
        alert("Something Went Wrong");
      }

    });
  });
}
//function uses rest api to send a update user request to backend.
function doUpdateUser(form) {
  fetch('../api/update_user.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "password": password,
      "user": form.updateUserSelect.value,
      "admin": form.oldAdmin.value
    })

  }).then(function (response) {

    response.json().then(function (data) {
      if (data.result) {
        alert("User Updated");
        fetchDropdownValues();
      } else {
        alert("Something Went Wrong");
      }

    });
  });
}

// Runs the sql procedure to create the weekly schedule and then redirects to the calendar page
function createSchedule() {
  fetch("../api/finalize_schedule.php", {
    method: "GET",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
  }).then((response) => {
    location = "../Views/index.html"
  })
}

// handles the checkbox form for Edit User Privileges
function savePrivileges(form) {
  checkBoxArr =  $("input[name='privCheckbox']:checked").map(function(){
    return this.value;
  }).get();
  fetch('../api/save_privileges.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "user": form.users.value,
      "checkboxes": JSON.stringify(checkBoxArr)
    })

  }).then(function (response) {

    response.json().then(function (data) {
      if (data.result) {
        alert("User Privileges Saved");
        fetchDropdownValues();
      } else {
        alert("Something Went Wrong");
      }

    });
  });
}


