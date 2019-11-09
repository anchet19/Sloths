var lastVisible = "initialFormView";
const username = sessionStorage.getItem('username');

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

function handleChangeUserPassword() {
  // Get the form and format the data
  const form = document.getElementById("changeUserPassword");
  const formattedFormData = new FormData(form);
  return (req,res) => {
    fetch('../api/admin_change_password.php', {
      method: 'POST',
      body: formattedFormData
    });
  }
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

//fetches and populates all of the dropdowns asyncronhously

function fetchDropdownValues() {
  fetch('../api/get_builds.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username,
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
    body: {
      "username": username
    }
  }).then(function (response) {
    response.json().then(function (data) {
      userSelect = document.getElementById("deleteUserSelect");
      userSelect.innerHTML = '';

      userSelect2 = document.getElementById("updateUserSelect");
      userSelect2.innerHTML = '';

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
      });

    });
  });

}
fetchDropdownValues();

//function uses rest api to send a delete build request to backend.
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
    location = "../Views/index"
  })
}