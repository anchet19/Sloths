/*
  Needs proper license disclaimer https://fullcalendar.io/license
  
*/

var startTime, endTime, currDesktop;
var submitted = 0;
var date;
var time;
var installationData = {};
var userData = {};

/**
 * Refreshes the calendar to keep the displayed information persistent with the the rest api 
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
  }).then(function(response) {
    response.json().then(function(data) {
      installationData = data;
      builds = document.getElementById("Build");
      builds.innerHTML = '';
      desktops = document.getElementById("Desktop");
      desktops.innerHTML = '';

      let option;
      data.forEach(function (row) {
        if ($("#Build option[value='" + row.bID + "']").length < 1) {
          option = document.createElement('option');
          option.text = row.buildName;
          option.value = row.bID;
          builds.add(option);
        }
        builds.value = option.value;
      });

      data.forEach(function (row) {
        if (builds.value == row.bID) {
          option = document.createElement('option');
          option.text = row.dtopName;
          option.value = row.dtopID;
          desktops.add(option);
        }
      });

      $("#changeDesktop").click();
    });
  });
}


/**
 * Updates the desktop dropdown for a given build id.
 * Author: David Serrano (serranod7)
 * @param {String} b_num The unique id of the build in the build table
 */
function populateDropdown(b_num) {
  dropdown = document.getElementById("Desktop");
  let option;
  dropdown.innerHTML = '';
  installationData.forEach(function (row) {
    if (b_num == row.bID) {
      option = document.createElement('option');
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
  var time = $('#time').val();
  var date = $('#date').val();
  var desktop = $('#desktop').val();
  var build = $('#Build').val();

  $.ajax({
    type: 'post',
    url: '../api/joinQueue.php',
    data: {
      curr: userData.user_num,
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

  if (auth == 2) {
    window.location.href = "../Views/adminPage.php";
  }
  // Uncomment once Manager page is created
  // else if(auth >= 1){
  //   window.location.href = "../Views/managerPage.php";
  // }
  else {
    alert("You don't have permission to access this page.");
  }
}

/**
 * When the user selects a desktop option from the drop-down menu and clicks the
 * Change Displayed Schedule button, this will store that value in the hidden element
 * so that the calendar can use it to filter it's information
 */
function setDesktop() {
  desktopID = document.getElementById("Desktop").value;
  installationData.forEach(function (row) {
    if (row.dtopID == desktopID) {
      document.getElementById("demo").value = row.dtopID;
      document.getElementById("currDesktop").value = row.dtopName;
    }
  });
  $("#calendar").fullCalendar("rerenderEvents");
}

/**
 * If a user selects a timeslot and clicks the Request button, this function will execute
 * author: Cassandra Bailey
 */
function requestSlot() {
  var time = $('#time').val();
  var date = $('#date').val();
  var desktop = $('#desktop').val();
  joinQueue(userData.user_num, time, date, desktop);
} // end of requestSlot

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
  const desktop = $('#desktop').val();
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
  if (!user.includes(userData.username)) {
    $("#dialog-confirm").dialog("close"); // Koala
    alert("You cannot release a timeslot which you don't have reserved.");
  }
  else if(eventType === ''){
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
        curr: userData.user_num,
        time: time,
        date: date,
        desktop: desktop,
        eventType: eventType
      },
      success: function (result) {
        redisplay();
        alert(result);
        console.log("release.php -- success!!" + result); //Koala
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
 * Get the desktop name of the current view
 */
function getDesktopName() {
  return document.getElementById("Desktop").text;
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

  console.log("submitted = (" + submitted + ")"); //Koala

  if (submitted == 1) {
    var a = new Date(start);
    var b = new Date(end);

    //change date format
    var dd = a.getDate();
    var mm = a.getMonth() + 1;
    var yyyy = a.getFullYear();
    if (dd < 10) {
      dd = '0' + dd;
    }
    if (mm < 10) {
      mm = '0' + mm;
    }
    date = yyyy + '-' + mm + '-' + dd;
    document.getElementById("date").value = '' + date.toString(); //sets the value of a date in the info display form.

    //change the  time format
    var h = a.getHours() + 5;
    var min = a.getMinutes();
    var sec = a.getSeconds();
    if (h < 10) {
      h = '0' + h;
    }
    if (min < 10) {
      min = '0' + min;
    }
    if (sec < 10) {
      sec = '0' + sec;
    }
    time = h + ':' + min + ':' + sec;
    document.getElementById("time").value = '' + time.toString(); //sets the value of a time in the info display form.

    document.getElementById("desktop").value = document.getElementById("Desktop").value;
    document.getElementById("reservedBy").value = "";
    document.getElementById('user').value = '';
    installationData.forEach(function (row) {
      if (row.dtopID == document.getElementById("desktop").value) {
        document.getElementById("desktopForm").value = row.dtopName;
      }
    });
    return true;
  } else {
    alert("You must select a desktop and submit to see time slot information.")
    return false;
  }
}

/*
 *This function returns today's date.
 */
function getTodaysDate() {
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth() + 1; //January is 0!
  var yyyy = today.getFullYear();
  if (dd < 10) {
    dd = '0' + dd;
  }

  if (mm < 10) {
    mm = '0' + mm;
  }

  today = yyyy + '-' + mm + '-' + dd;

  return today;
};

/**
 * Creates the calendar display and the popup modal for requesting / releasing
 */
function BuildCalendar() {

    $(document).ready(function () {

        //BEGIN : Koala modifications
        // The Modal
        $("#dialog-confirm").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            autoOpen: false,
            modal: true,
            buttons: [{
                id: "btnRequest",
                text: "Request",
                icon: "ui-icon-plus",
                click: function () {
                    requestSlot();
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

        // The calendar
        $('#calendar').fullCalendar({
            eventSources: [{
                url: '../api/get_requests',
                type: 'GET',
                color: 'pink', // Other Users' Requested
                borderColor: 'black',
                textColor: 'black',
                success: function (data) {
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
            // aspectRatio: 1.8,
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
            firstDay: 1,
            header: {
                left: 'prev,next,today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultDate: getTodaysDate(),

            selectAllow: function (selectInfo) {
                var duration = moment.duration(selectInfo.end.diff(selectInfo.start));

                if (duration.asHours() > 3) {
                    $('#calendar').fullCalendar('unselect');
                    return false;
                }
                return true;
            },

            unselect: function (event) {
                // console.log(event);
            },

            select: function (start, end, _, view) {
                const startOfNextWeek = moment().startOf('week').add(7, 'days');
                const endOfNextWeek = moment().endOf('week').add(8, 'days');
                const activeStart = moment(view.start);
                const activeEnd = moment(view.end);
                // console.log(activeStart.toDate() + "----" + startOfNextWeek.toDate() + "\n" + activeEnd.toDate() +"----" + endOfNextWeek.toDate());
                console.log(start)
                // Calendar slots are non-responsive if the current date range is outside of the normal request
                // period unless the current user is an admin.
                if ((userData.admin == 2 && activeStart >= startOfNextWeek) || (activeStart >= startOfNextWeek && activeEnd <= endOfNextWeek)) {
                    end = start + 1.08e+7; // enforces the 3hr blocks. (milliseconds)
                    $("#dialog-confirm").dialog("open"); // Shows the Reservation Dialog Box
                    var check = checkForInfoDisplay(start, end);
                    if (check == false) {
                        $('#calendar').fullCalendar('unselect');
                    }
                }
            },

            eventClick: function (event, element) {
              //BEGIN : Koala modifications 
              $("#dialog-confirm").dialog("open"); // Shows the Reservation Dialog Box
              // $('#calendar').fullCalendar('updateEvent', event);
              //END : Koala modifications
          
              document.getElementById('reservedBy').value = (event.className == 'request') ? '' : event.title;
              document.getElementById('date').value = event.date;
              document.getElementById('time').value = event.time;
              document.getElementById('desktop').value = event.id;
              installationData.forEach(function (row) {
                if (row.dtopID == event.id) {
                  document.getElementById("desktopForm").value = row.dtopName;
                }
              });
              document.getElementById('user').value = (event.usernames) ? event.usernames : event.username;
            },

            eventOverlap: function (stillEvent, movingEvent) {
                return stillEvent.allDay && movingEvent.allDay;
            },

            eventDrop: function (event, delta, revertFunc) {
                setStartEndTime(event.start, event.end);
            },
            eventRender: function (event, element) {
                element.css("font-size", "1.1em");
                // Color the events differently if it belongs to the current user
                if (event.className[0] === 'request') {
                    if (event.usernames.includes(userData.username)) {
                        element.css("background-color", "lightgreen") // Your Request
                        element.css("border-color", "black")
                        element.css("cursor", "pointer")
                    }
                } else {
                    if (event.username === userData.username) {
                        element.css("background-color", "green") // Your Final
                        // element.css("font-weight", "bold")
                        element.css("color", "white")
                        element.css("border-color", "black")
                        element.css("cursor", "pointer")
                    }
                }
                desktop = document.getElementById('demo').value;

                return desktop === event.id ? element : false;

            },
        });

    });
} // BuildCalendar
