var lastVisible = "initialFormView";
const username = sessionStorage.getItem('username');
fetchDropdownValues();

$(document).ready(function () {

  const desktopMetricsForm = document.getElementById("desktopMetricsForm");
  desktopMetricsForm.addEventListener('submit', function (event) {
    event.preventDefault();
    handleDesktopMetricsSubmit();
  });
  const buildMetricsForm = document.getElementById("buildMetricsForm");
  buildMetricsForm.addEventListener('submit', function (event) {
    event.preventDefault();
    handleBuildMetricsSubmit();
  });
  const outcomeMetricsForm = document.getElementById("outcomeMetricsForm");
  outcomeMetricsForm.addEventListener('submit', function (event) {
    event.preventDefault();
    handleOutcomeMetricsSubmit();
  });
  const newPasswordForm = document.getElementById("newPasswordForm");
  newPasswordForm.addEventListener('submit', (event) =>{
    event.preventDefault();
    handleChangeUserPassword();
  });

  $('#block-desktops').submit((event) => {
    event.preventDefault();
    const fd = new FormData(event.target)
    // Display the key/value pairs
    for (var pair of fd.keys()) {
      console.log(pair);
    }
    fetch('../api/block_desktops.php', {
      method: 'POST',
      body: fd
    }).then((response) => {
      response.text().then((result) => {
        alert(result);
        document.getElementById('block-desktops').reset();
      })
    })
  })
  
  //converts user-dropdown classes to select2 format
  $('.user-dropdown').select2({
    placeholder: "Select User",
    theme: 'classic',
    width: 'resolve'
  });

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
          
          
          privileges.append(checkbox);
          privileges.append(label);
          // append("<br>");
  
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
    // append("<br>");  
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
 * Handles the buildMetricsForm submit event.
 * 
 */
function handleBuildMetricsSubmit(){
  // Get the form
  const buildMetricsForm = document.getElementById("buildMetricsForm");
  // Format the form data -- Content-Type: application/x-www-form-urlencoded
  const formattedFormData = new FormData(buildMetricsForm);
  buildMetricsPostData(formattedFormData);
}

function handleOutcomeMetricsSubmit(){
  // Get the form
  const outcomeMetricsForm = document.getElementById("outcomeMetricsForm");
  // Format the form data -- Content-Type: application/x-www-form-urlencoded
 
  const formattedFormData = new FormData(outcomeMetricsForm);
 /*  $(this.form).find(':radio:checked').each(function () {
    formattedFormData.append(this.name, $(this).val());}); */
  outcomeMetricsPostData(formattedFormData);
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

/**
 * Fetch the Build metrics HTML markup using the javascript Fetch API
 * and update the DOM
 * @param {FormData} formattedFormData The data from the form
 */
async function buildMetricsPostData(formattedFormData) {
  const response = await fetch('../api/get_build_metrics.php', {
    method: 'POST',
    body: formattedFormData
  });
  const data = await response.text();
  document.getElementById("buildMetricsTable").innerHTML = data;
}

async function outcomeMetricsPostData(formattedFormData) {
  const response = await fetch('../api/get_outcome_metrics.php', {
    method: 'POST',
    body: formattedFormData
  });
  const data = await response.text();
  document.getElementById("outcomeMetricsTable").innerHTML = data;
}

/**
 * Handle the form submission for changing a User's password
 * Post data to rest api and alert the response.
 */
async function handleChangeUserPassword() {
  // Get the form and format the data
  const form = document.getElementById("newPasswordForm");
  const formattedFormData = new FormData(form);
  fetch('../api/admin_change_password.php', {
    method: 'POST',
    body: formattedFormData
  }).then((response) => {
    response.json().then((data) =>{
      alert(data.message);
    })
  })
}

/**
 * Hides the currently visible Element and shows the new element
 * 
 * @param {DOM Element} id The id of the Element you want to show
 */
function makeVisible(id) {
  document.getElementById(lastVisible).style.display = "none";
  document.getElementById(id).style.display = "block";
  lastVisible = id;
}

/**
 * Fetches and populates all of the dropdowns asyncronhously
 */
function fetchDropdownValues() {
  /**
   * Populate all the build select dropdowns
   */
  fetch('../api/get_builds.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
  }).then(function (response) {
    response.json().then(function (data) {
      buildSelects = document.getElementsByName("build-select");
      data.forEach(function (row) {
        buildSelects.forEach((element) => {
          const option = document.createElement('option');
          option.text = row.name;
          option.value = row.b_num;
          element.add(option);
        });
      });
    });
  });

  /**
   * Populate all the desktop select dropdowns
   */
  fetch('../api/get_desktops.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
  }).then((response) => {
    response.json().then((data) => {
      desktopSelects = document.querySelectorAll('[name^=desktop-select]')
      data.forEach((row) => {
        desktopSelects.forEach((element) => {
          const option = document.createElement('option');
          option.text = row.name;
          option.value = row.dtop_id;
          element.add(option);
        });
      });
    });
  });

  /**
   * Get all the users from the system and populate all the user select dropdowns
   */
  fetch('../api/get_users.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username
    })
  }).then(function (response) {
    response.json().then(function (data) {
      userSelects = document.getElementsByName("user-select");
      data.forEach(function (row) {
        userSelects.forEach((element) => {
          const option = document.createElement('option');
          option.text = row.username;
          option.value = row.user_num;
          element.add(option);
        });
      });
    });
  });

  /**
 * Populate all the department select dropdowns
 */
  fetch('../api/get_departments.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
  }).then((response) => {
    response.json().then((data) => {
      departmentSelects = document.getElementsByName('department-select');
      data.forEach((row) => {
        departmentSelects.forEach((element) => {
          const option = document.createElement('option');
          option.text = row.name;
          option.value = row.department_id;
          element.add(option);
        });
      });
    });
  });
}

/**
 * Function uses rest api to send a delet build request to the backend
 * @param {HTMLFormElement} form The Form to be submitted
 */
function doDeleteBuild(form) {
  fetch('../api/delete_build.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "build": form.deleteBuildSelect.value
    })

  }).then(function (response) {
    response.json().then(function (data) {
      if (data.result) {
        alert("Build Deleted");
        //fetchDropdownValues(); crossmod
      } else {
        alert("Something Went Wrong");
      }

    });
  });
}

/**
 * Function uses rest api to send a delet desktop request to the backend
 * @param {HTMLFormElement} form The Form to be submitted
 */
function doDeleteDesktop(form) {
  fetch('../api/delete_desktop.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "desktop": form.deleteDesktopSelect.value
    })

  }).then(function (response) {
    response.json().then(function (data) {
      if (data.result) {
        alert("Desktop Deleted");
        //fetchDropdownValues(); crossmod
      } else {
        alert("Something Went Wrong");
      }

    });
  });
}
/**
 * Function uses rest api to send a delet user request to the backend
 * @param {HTMLFormElement} form The Form to be submitted
 */
function doDeleteUser(form) {
  fetch('../api/delete_user.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "user": form.deleteUserSelect.value
    })

  }).then(function (response) {
    response.json().then(function (data) {
      if (data.result) {
        alert("User Deleted");
        //fetchDropdownValues(); crossmod
      } else {
        alert("Something Went Wrong");
      }

    });
  });
}

/**
 * Function uses rest api to send a delet installation request to the backend
 * @param {HTMLFormElement} form The Form to be submitted
 */

function doDeleteInstallation(form) {
  fetch('../api/delete_installation.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
      "build": form.deleteInstallationSelectB.value,
      "desktop": form.deleteInstallationSelectD.value
    })

  }).then(function (response) {
    response.json().then(function (data) {
      if (data.result) {
        alert("Installation Deleted");
        //fetchDropdownValues(); crossmod
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
      "build": form.build.value
    })

  }).then(function (response) {
    response.json().then(function (data) {
      if (data.result) {
        alert("Build Inserted");
        //fetchDropdownValues(); crossmod
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
      "desktop": form.desktop.value,
      "color": form.color.value
    })

  }).then(function (response) {
    response.json().then(function (data) {
      if (data.result) {
        alert("Desktop Inserted");
        //fetchDropdownValues(); crossmod
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
      "desktop": form.insertDesktopSelect.value,
      "build": form.insertBuildSelect.value
    })

  }).then(function (response) {

    response.json().then(function (data) {
      if (data.result) {
        alert("Installation Inserted");
        //fetchDropdownValues(); crossmod
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
      "newUsername": form.username.value,
      "newPassword": form.password.value,
      "firstName": form.firstName.value,
      "lastName": form.lastName.value,
      "email": form.email.value,
      "admin": form.newAdmin.value,
      "department": form.departmentSelect.value,
      "tel": form.usrTel.value
    })
  }).then(function (response) {
    response.json().then(function (data) {
      if (data.result) {
        alert("User Inserted");
        //fetchDropdownValues(); crossmod
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
      "user": form.updateUserSelect.value,
      "admin": form.oldAdmin.value
    })

  }).then(function (response) {
    response.json().then(function (data) {
      if (data.result) {
        alert("User Updated");
        //fetchDropdownValues(); crossmod
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
    location = "../Views/index"
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
      } else {
        alert("Something Went Wrong");
      }

    });
  });
}