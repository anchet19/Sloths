/*
  Needs proper license disclaimer https://fullcalendar.io/license
  
*/

var startTime, endTime, currDesktop;
var submitted = 0;
var date;
var time;
var installationData = {};
var userData = {};

$(document).ready(function () {
  populateDropdowns(sessionStorage.username);
  BuildCalendar();

  // Transform user dropdown into a Select2 style dropdown
  $('.user-dropdown').select2({
    placeholder: 'Select User',
    width: '75%',
    theme: 'classic'
  })

  // Ensure that any Select2 style dropdowns are accessible within any modals
  if ($.ui && $.ui.dialog && $.ui.dialog.prototype._allowInteraction) {
    var ui_dialog_interaction = $.ui.dialog.prototype._allowInteraction;
    $.ui.dialog.prototype._allowInteraction = function (e) {
      if ($(e.target).closest('.select2-dropdown').length) return true;
      return ui_dialog_interaction.apply(this, arguments);
    };
  }

  //BEGIN : Koala modifications
  // The Modal
  $("#dialog-confirm").dialog({
    resizable: false,
    height: "auto",
    width: 400,
    autoOpen: false,
    modal: true,
    focus: false,
    buttons: [{
      id: "btnReserve",
      text: "Reserve",
      icon: "ui-icon-plus",
      click: function () {
        reserveSlot();
        $(this).dialog("close");
      }
    },
    {
      id: "btnRequest",
      text: "Request",
      icon: "ui-icon-plus",
      click: function () {
        joinQueue();
        $(this).dialog("close");
      }
    },
    {
      id: "btnRelease",
      text: "Release",
      icon: "ui-icon-minus",
      click: function () {
        releaseSlot();
        $(this).dialog("close");
      }
    }
    ]
  });
  //END : Koala modifications

  /**
   * Event handler for the build filter
   */
  $('#Build').change((event) => {
    const dtopSelect = document.getElementById('Desktop');
    const first = dtopSelect.firstChild;
    const view = $('#calendar').fullCalendar('getView');
    if (view.name != 'month') {
      populateDesktopDropdown('Desktop', event.target.value);
    } else {
      if (first.value != 0) {
        const el = $('#Desktop');
        el.prepend($(`<option value="0">-- Select -- </option>`))
        el.val(0).trigger('change');
      }
    }
    $('#calendar').fullCalendar('rerenderEvents');
  })

  /**
   * Event handler for the build select in popup
   */
  $('#buildForm').change((event => {
    const dtopSelect = document.getElementById('desktopForm');
    populateDesktopDropdown('desktopForm', event.target.value);
    event.target.value == 0 ? dtopSelect.disabled = true : dtopSelect.disabled = false;
  }))

  /**
   * Event handler for desktop filter
   */
  $('#Desktop').change((event) => {
    $('#calendar').fullCalendar('rerenderEvents');
  })
});

function colorPrimeRows() {
  const pat = /9am|12pm|3pm/;
  const rows = document.querySelectorAll('.fc-time');
  rows.forEach((row) => {
    const text = row.innerHTML;
    pat.test(text) ? row.style = "background-color: #cccc0040" : false;
  })
}

function colorRegularRows() {
  const pat = /6am|6pm|9pm/;
  const rows = document.querySelectorAll('.fc-time');
  rows.forEach((row) => {
    const text = row.innerHTML;
    pat.test(text) ? row.style = "background-color: #ffffff40" : false;
  })
}

/**
 * Refreshes the calendar to keep the displayed information persistent with the rest api 
 */
function redisplay() {
  $('#calendar').fullCalendar('refetchEvents');
}

/**
 * Checks to see if a desktop has been selected in the dropdown form on index.html
 * if it hasn't, the user will be alerted to choose one
 * Author: Cassandra Bailey
 * @param {HTMLFormElement} form The form submitted by the Desktop drop-down
 */
function checkForDesktop(form) {
  if (form.Desktop.value == "") {
    alert("You must select a desktop");
  }
}

/**
 * Populates the build and desktop dropdowns based on the users privileges.
 * retrieves installation data using rest request.
 * then the data is used to link the build and desktop dropdowns.
 * Author: David Serrano (serranod7)
 * @param {String} username The current users username
 */
function populateDropdowns(username) {
  fetch('../api/get_installations.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username
    })
  }).then(function (response) {
    response.json().then(function (data) {
      installationData = data;
      // Select boxes for the popup form
      const dialogDtopSel = document.getElementById('desktopForm');
      const dialogBuildSel = document.getElementById('buildForm');
      // Select boxes for the filter
      const builds = $('#Build');
      const desktops = $('#Desktop');
      // Make placholder options and then hydrate with response data
      builds.innerHTML = '';
      desktops.innerHTML = '';
      dialogBuildSel.innerHTML = '';
      dialogDtopSel.innerHTML = '';
      let option;
      data.forEach(function (row) {
        if ($("#Build > option[value='" + row.bID + "']").length < 1) {
          option = document.createElement('option');
          option.text = row.buildName;
          option.value = row.bID;
          builds.append(option);
          console.log(option)
          dialogBuildSel.add(option.cloneNode(true));
        }
        if ($("#Desktop > option[value='" + row.dtopID + "']").length < 1) {
          option = document.createElement('option');
          option.text = row.dtopName;
          option.value = row.dtopID;
          desktops.append(option);
          console.log(option)
          dialogDtopSel.add(option.cloneNode(true));
        }
      });
    });
  });
}


/**
 * Updates the desktop dropdown for a given build id.
 * Author: David Serrano (serranod7)
 * @param {Int} b_num The unique id of the build in the build table
 */
function populateDesktopDropdown(selector, b_num) {
  const dropdown = document.getElementById(selector);
  dropdown.innerHTML = '';
  installationData.forEach(function (row) {
    const option = document.createElement('option');
    if (b_num == row.bID || b_num == 0) {
      option.text = row.dtopName;
      option.value = row.dtopID;
      dropdown.add(option);
    }
  });
}

/**
 * Retrieves user data using rest api
 * Author: David Serrano (serranod7)
 * @param {String} username The current user
 */
function retrieveUser(username) {
  fetch('../api/get_user.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: $.param({
      "username": username
    })
  }).then(function (response) {
    response.json().then(function (data) {
      if (data.validation !== false) {
        welcome = document.getElementById("welcome");
        welcome.innerHTML = "Hello " + data.first_name + "!";
        userData = data;
        const user = {
          username: data.username,
          uid: data.user_num,
          department: data.department_id
        }
        if (userData.admin > 0) {
          const el = document.getElementById('admin-button');
          el.firstChild.innerHTML = userData.admin < 2 ? 'Manager' : 'Admin';
          el.style = "display: block";
        }
        sessionStorage.setItem('userData', JSON.stringify(user))
      } else {
        window.location.href = "../Views/login.html";
      }
    });
  });
}

/**
 * Enters the user request into the queue for the
 * selected desktop, date, and time using the rest api.
 */
function joinQueue() {
  const time = $('#time').val();
  const date = $('#date').val();
  const desktop = $('#desktopForm').val();
  const build = $('#buildForm').val();
  const user = $('#user').val();
  $.ajax({
    type: 'post',
    url: '../api/joinQueue.php',
    data: {
      curr: user,
      time: time,
      date: date,
      desktop: desktop,
      build: build
    },
    success: function (response) {
      redisplay();
      alert(response);
    }
  });
}

/**
 * If a user clicks on the link to bring them to the admin page, this function verifies that the user has admin capabilities
 * if they do, they are directed to adminPageTest.html
 * if they don't, they are alerted to this and stay on index.html
 * author: Cassandra Bailey
 */
function checkForAdmin() {
  const auth = userData.admin;

  if (auth == 2) { // If admin
    window.location.href = "../Views/adminPage.php";
  }
  else if (auth == 1) { // If manager
    window.location.href = "../Views/managerPage.php";
  }
  else { // If user
    alert("You don't have permission to access this page.");
  }
}

/**
 * When the user selects a desktop option from the drop-down menu and clicks the
 * Change Displayed Schedule button, this will store that value in the hidden element
 * so that the calendar can use it to filter it's information
 */
function setDesktop() {
  desktop = document.getElementById("Desktop");
  document.getElementById("demo").value = desktop.value;
  document.getElementById("currDesktop").value = desktop.options[desktop.selectedIndex].text;
  $("#calendar").fullCalendar("rerenderEvents");
}

/**
 * Called when an admin clicks the "Reserve" button on the popup dialog.
 * Sends a POST request to the database server.
 */
function reserveSlot() {
  const user = $('#user').val();
  const desktop = $('#desktopForm').val();
  const build = $('#buildForm').val();
  const time = $('#time').val();
  const date = $('#date').val();
  $.ajax({
    type: 'post',
    url: '../api/admin_reserve.php',
    data: {
      curr: user,
      time: time,
      date: date,
      desktop: desktop,
      build: build,
    },
    success: function (result) {
      redisplay();
      alert(result);
      // console.log("release.php -- success!!" + result); //Koala
    },
    error: function (result) {
      console.log(result); //Koala
    },
    failure: function (result) {
      console.log(result);
    }
  });
}

/**
 * Allows the user to release a request or a reservation belonging to them
 * Reservations can only be released if current time < start time of reservation
 * 
 * author: Casandra Bailey
 * modified: Chris Ancheta
 */
function releaseSlot() {
  // Get the information from the modal
  const time = $('#time').val();
  const date = $('#date').val();
  const desktop = $('#desktopForm').val();
  const user = $('#user').val();
  const reservedBy = $('#reservedBy').val();
  const dateTime = moment(date + " " + time);
  const now = moment();
  // Check to see what type of event has been selected
  let eventType = '';
  if (reservedBy === '') {
    eventType = 'request';
  }
  else if (now < dateTime) {
    eventType = 'reservation';
  }
  else {
    eventType = '';
  }
  // if the current user does not have the selected timeslot requested ore reserved
  // they are not permitted to release it
  if (user != userData.user_num && userData.admin != 2) {
    $("#dialog-confirm").dialog("close"); // Koala
    alert("You cannot release a timeslot which you don't have reserved.");
  }
  else if (eventType === '') {
    $("#dialog-confirm").dialog("close"); // Koala
    alert('Cannot release a reservation that has already begun');
  }
  //if the current user has the timeslot requested, release.php will be executed and the request
  // will be removed from the queue table in the database
  else {
    $.ajax({
      type: 'post',
      url: '../api/release.php',
      data: {
        curr: user,
        time: time,
        date: date,
        desktop: desktop,
        eventType: eventType
      },
      success: function (result) {
        redisplay();
        alert(result);
        // console.log("release.php -- success!!" + result); //Koala
      },
      error: function (result) {
        console.log(result); //Koala
      },
      failure: function (result) {
        console.log(result);
      }
    });
  }
}

/**
 * When desktop chosen from dropdown and Submit button clicked, the desktop name will be displayed
 * author: Cassandra Bailey
 */
function submitFunction() {
  var x = document.getElementById("demo").value;
  if (x != "") {
    submitted = 1;
  }
  redisplay();
}

/**
 * Not totally sure what's going on here. Needs a deeper dive and maybe a refactor...
 * 
 * @param {Moment object} start The start time of the time-slot that was clicked on
 * @param {Moment object} end The end time of the time-slot that was clicked on
 */
function checkForInfoDisplay(start, end) {
  if (userData.admin == 2) {
    fetch('../api/get_users.php', {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: $.param({
        "username": sessionStorage.username
      })
    }).then(function (response) {
      response.json().then(function (data) {
        const userSelect = document.getElementById("user");
        userSelect.innerHTML = '';
        data.forEach(function (row) {
          const option = document.createElement('option');
          option.text = row.username;
          option.value = row.user_num;
          userSelect.add(option);
        });
        userSelect.options.selectedIndex = -1;
        userSelect.parentNode.style = "display: block";
      });
    });
  } else {
    document.getElementById('btnReserve').style = "display: none";
    const el = document.getElementById("user")
    const option = document.createElement("option");
    option.value = userData.user_num;
    el.add(option);
    el.options.selectedIndex = 0;
  }

  const startDateTime = start.format().split('T');
  const endDateTime = end.format().split('T');
  // hydrate the dialog elements
  document.getElementById("date").value = startDateTime[0];
  document.getElementById("time").value = startDateTime[1];
  $('#buildForm').val(document.getElementById('Build').value).trigger('change');
  $('#desktopForm').val(document.getElementById('Desktop').value).trigger('change');
  document.getElementById("reservedBy").value = "";
}

/**
 * Populates the user select box in the popup dialog for admins
 * 
 * @param {String uNames} A comma delimited string of names (first last, first last...)
 * @param {String uIds} A comma delimited string of user ids
 */
function populateUserSelect(uNames, uIds) {
  const names = uNames.split(',');
  const ids = uIds.split(',');
  const el = document.getElementById("user");
  el.parentNode.style = "display: block"
  // Clear the options list and populate with new data
  el.innerHTML = '';
  for (let i = 0; i < names.length; i++) {
    const option = document.createElement('option');
    option.text = names[i];
    option.value = ids[i];
    el.add(option);
  }
  // Default select the first option
  el.options.selectedIndex = 0;
}

/**
 * Creates the calendar display and the popup modal for requesting / releasing
 */
function BuildCalendar() {
  // The calendar
  $('#calendar').fullCalendar({
    eventSources: [{
      url: '../api/get_requests',
      type: 'GET',
      color: 'pink', // Other Users' Requested
      borderColor: 'black',
      textColor: 'black',
      success: function (data) {
        // console.log('requests loaded');
        $('#calendar').fullCalendar('rerenderEvents');
      },
      error: function (data) {
        console.log(data);
      },
      failure: function (data) {
        console.log(data);
      }
    },
    {
      url: '../api/get_reservations',
      type: 'GET',
      color: 'darkred', // Other Users' Finalized
      borderColor: 'black',
      textColor: 'white',
      className: ["reservation"],
      success: function (data) {
        // console.log('reservations loaded');
        $('#calendar').fullCalendar('rerenderEvents');
      },
      error: function (data) {
        console.log(data);
      },
      failure: function (data) {
        console.log(data);
      }
    },
    {
      url: '../api/get_blocklist',
      type: 'GET',
      color: 'black', // Other Users' Finalized
      borderColor: 'black',
      textColor: 'white',
      className: ["blocked"],
      success: function (data) {

        $('#calendar').fullCalendar('rerenderEvents');
      },
      error: function (data) {
        console.log(data);
      },
      failure: function (data) {
        console.log(data);
      }
    }],
    minTime: "06:00:00",
    maxTime: "24:00:00",
    firstHour: "06:00:00",
    schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
    timezone: false,
    defaultView: 'agendaWeek',
    navLinks: true, // can click day/week names to navigate views
    unselectAuto: false,
    selectable: true,
    selectHelper: false,
    editable: false,
    eventLimit: true, // allow "more" link when too many events
    allDaySlot: false,
    slotDuration: "03:00:00",
    contentHeight: 'auto',
    eventDurationEditable: false, //prevents event from being resize
    agendaEventMinHeight: "10px",
    slotEventOverlap: false,
    firstDay: 1,
    header: {
      left: 'prev,next,today',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
    },
    defaultDate: moment().format('YYYY-MM-DD'),
    selectAllow: function (selectInfo) {
      var duration = moment.duration(selectInfo.end.diff(selectInfo.start));
      console.log(selectInfo.start.format() + ":::" + selectInfo.end.format());
      if (duration.asHours() > 3) {
        $('#calendar').fullCalendar('unselect');
        return false;
      }
      return true;
    }, // TO-DO: merge logic for this and eventclick into a single function call
    select: function (start, end, _, view) {
      $('#buildForm').parent().show();
      const startOfWeek = moment().startOf('week');
      const endOfNextWeek = moment().endOf('week').add(2, 'weeks');
      const activeStart = moment(view.start);
      const activeEnd = moment(view.end);
      const now = moment().utc(-5)  // The time right now using UTC -5hours.
      // Calendar slots are non-responsive if the current date range in view is outside
      // of the normal Request or Free-For-All periods unless the current user is an Admin.
      if ((userData.admin == 2) || (now < start && activeStart >= startOfWeek && activeEnd <= endOfNextWeek)) {
        checkForInfoDisplay(start, end);
        $("#dialog-confirm").dialog("open"); // Shows the Reservation Dialog Box
      }
    },
    eventClick: function (event, element) {
      document.getElementById('reservedBy').value = (event.className == 'request') ? '' : event.title;
      document.getElementById('date').value = event.date;
      document.getElementById('time').value = event.time;
      // document.getElementById('dtopID').value = event.id;
      if (event.title != 'BLOCKED') {
        if (event.className[0] != 'request' && userData.admin != 2 && !event.user.includes(userData.user_num)) {
          return false;
        }
        $('#buildForm').val(event.buildID).trigger('change');
        $('#desktopForm').val(event.id).trigger('change');
        if (userData.admin == 2) {
          (event.names) ? populateUserSelect(event.names, event.user) : populateUserSelect(event.title, event.user);
        }
        //BEGIN : Koala modifications 
        $("#dialog-confirm").dialog("open"); // Shows the Reservation Dialog Box
        //END : Koala modifications
      }
    },
    eventRender: function (event, element, view) {
      // Set size of font depending upon the view
      if (view.name === 'month') {
        element.css("font-size", "0.85em")
      } else {
        element.css("font-size", "1em");
      }
      // Style blocked slots
      if (event.title === 'BLOCKED') {
        element.css("cursor", "not-allowed");
        element.css("border-color", "black")
        view.name === 'month' ? false : element.css("height", "5.4em");
      }
      // Color the events differently if it belongs to the current user
      if (event.className[0] === 'request') {
        if (event.usernames.includes(userData.username)) {
          element.css("background-color", "lightgreen") // Your Request
          element.css("border-color", "black")
          element.css("cursor", "pointer")
        }
      } else {
        // view.name === 'month' ? false : element.css("height", "5.4em");
        if (event.username === userData.username) {
          element.css("background-color", "green") // Your Final
          element.css("color", "white")
          element.css("border-color", "black")
          element.css("cursor", "pointer")
        }
      }
      // Filter the results based on the value of the drop-downs
      const desktop = document.getElementById('Desktop').value;
      const build = document.getElementById('Build').value;
      if (desktop > 1 && build > 1) {
        return ((desktop == event.id && event.buildID.includes(build)) ||
          (desktop == event.id && event.title === 'BLOCKED')) ? element : false;
      }
      else if (desktop > 1 && build < 1) {
        return (desktop == event.id) ? element : false;
      }
      else if (!desktop < 1 && build > 1) {
        return (event.buildID.includes(build)) ? element : false;
      }
      else {
        return element;
      }
    },
    // Callback for any time the view is changed. Here it is used to conditionally change filter capabilities
    viewRender: function (view) {
      colorPrimeRows(); // Highlight "Prime Time" row headers. (9am-3pm)
      colorRegularRows(); // Highlight "Non Prime Time" row headers. (6am, 6pm-9pm)
      const buildSelect = $('#Build');
      const buildPlaceholder = $('#Build > option[value="0"]');
      const dtopSelect = $('Desktop');
      const dtopPlaceholder = $('#Desktop > option[value="0"]');
      if (view.name === 'month') {
        if (buildPlaceholder.length == 0) {
          buildSelect.prepend('<option value="0">-- Select --</option>');
          buildSelect.val(0).trigger('change');
        }
        if (dtopPlaceholder.length == 0) {
          dtopSelect.prepend('<option value="0">-- Select --</option>');
          dtopSelect.val(0).trigger('change');
        }
      } else {
        if (buildPlaceholder.length != 0) {
          buildSelect.find('option:first').remove();
          buildSelect.trigger('change');
        }
        if (dtopPlaceholder.length != 0) {
          dtopSelect.find('option:first').remove();
          dtopSelect.trigger('change');
        }
      }
    }
  });
} // BuildCalendar