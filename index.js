/*
  Needs proper license disclaimer https://fullcalendar.io/license
  
*/

var startTime, endTime, currDesktop;
var submitted = 0;
var date;
var time;
var installationData = {};
var userData = {};


function redisplay() {
    $('#calendar').fullCalendar('rerenderEvents');
}

//checks to see if a desktop has been selected in the dropdown form on index.html
//if it hasn't, the user will be alerted to choose one
//Author: Cassandra Bailey

function checkForDesktop(form) {
    if (form.Desktop.value == "") {
        alert("You must select a desktop");
    }
}

//populates the build and desktop dropdowns.
//retrieves installation data using rest request.
//then the data is used to link the build and desktop dropdowns.
//Author: David Serrano (serranod7)
function populateDropdowns(username, password) {
    fetch('api/get_installations.php', {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: $.param({
            "username": username,
            "password": password
        })

    }).then(function(response) {
        response.json().then(function(data) {
            installationData = data;
            builds = document.getElementById("Build");
            builds.innerHTML = '';
            desktops = document.getElementById("Desktop");
            desktops.innerHTML = '';

            let option;
            data.forEach(function(row) {
                if ($("#Build option[value='" + row.bID + "']").length < 1) {
                    option = document.createElement('option');
                    option.text = row.buildName;
                    option.value = row.bID;
                    builds.add(option);

                }
                builds.value = option.value;
            });

            data.forEach(function(row) {
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


//updates the desktop dropdown for a given build id.
//Author David Serrano (serranod7)
function populateDropdown(b_num) {
    dropdown = document.getElementById("Desktop");
    let option;
    dropdown.innerHTML = '';
    installationData.forEach(function(row) {
        if (b_num == row.bID) {
            option = document.createElement('option');
            option.text = row.dtopName;
            option.value = row.dtopID;
            dropdown.add(option);
        }
    });
}

//Retrieves user data using rest api
//Author: David Serrano (serranod7)
function retrieveUser(username, password) {
    fetch('api/get_user.php', {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: $.param({
            "username": username,
            "password": password
        })
    }).then(function(response) {
        response.json().then(function(data) {
            if (data.validation !== false) {
                welcome = document.getElementById("welcome");
                welcome.innerHTML = "Hello " + data.first_name + "!";
                userData = data;
            } else {
                window.location.href = "login.html";
            }
        });
    });
}

//when a user requests a time slot that is already reserved by SOMEONE
//ELSE, they will be given the option to join the queue
//if they choose the accept it, this function is executed
//Author: Cassandra Bailey

function joinQueue() {
    var time = $('#time').val();
    var date = $('#date').val();
    var desktop = $('#desktop').val();

    $.ajax({
        type: 'post',
        url: 'joinQueue.php',
        data: {
            curr: userData.user_num,
            time: time,
            date: date,
            desktop: desktop
        },
        success: function(response) {
            alert(response);
        }
    });
}


//if a user clicks on the link to bring them to the admin page, this function verifies that the user has admin capabilities
//if they do, they are directed to adminPageTest.html
//if they don't, they are alerted to this and stay on index.html
//author: Cassandra Bailey

function checkForAdmin() {
    var admin = userData.admin;

    if (admin == 1) {
        window.location.href = "adminPage.html";
    } else {
        alert("You don't have permission to access this page.");
    }
}


function setDesktop() {
    desktopID = document.getElementById("Desktop").value;
    installationData.forEach(function(row) {
        if (row.dtopID == desktopID) {
            document.getElementById("demo").value = row.dtopID;
            document.getElementById("currDesktop").value = row.dtopName;
        }
    });
    $("#calendar").fullCalendar("rerenderEvents");
}



//if a user selects a timeslot and clicks the Request button, this function will execute
//author: Cassandra Bailey

function requestSlot() {
    var user = $('#user').val();
    var time = $('#time').val();
    var date = $('#date').val();
    var desktop = $('#desktop').val();
    var reservedBy = $('#reservedBy').val();

    //if the current user is the user that has the timeslot reserved, they will be alerted and will not be able to request the 
    //timeslot again

    if (userData.user_num == user) {
        $("#dialog-confirm").dialog("close"); // Koala
        alert("You already have desktop " + desktop + " reserved at " + time + " on " + date + ".");
    }


    //if user != "", then someone already has the timeslot reserved and the current user can choose to join the queue
    else if (user != "") {
        var check = confirm(reservedBy + " has desktop " + desktop + " reserved at " + time + " on " +
            date + ". Would you like to join the queue?");

        if (check == true) {
            joinQueue(userData.user_num, time, date, desktop);
        }
    }


    //the selected timeslot is not reserved
    else {

        console.log("The selected timeslot is available...."); //Koala

        //takes the selected date in YYYY-MM-DD format and converts it to the format needed for the Date class

        var selectedDate = moment(date, 'YYYY-MM-DD');
        var select = new Date(selectedDate.format());


        //gets the date for the Monday before and the Sunday after the selected date

        var mon = getMondayOfCurrentWeek(select);
        var sun = getSundayOfCurrentWeek(select);


        //puts mon and sun dates into YYYY-MM-DD format

        var formatMon = mon.toISOString().slice(0, 10);
        var formatSun = sun.toISOString().slice(0, 10);

        //checks to the see if the current user already have 3 reservations for the week in which they are requesting
        //CYCLE IS MONDAY-SUNDAY

        $.ajax({
            type: 'post',
            url: 'limitReservations.php',
            data: {
                user: userData.user_num,
                date: date,
                formatMon: formatMon,
                formatSun: formatSun
            },
            success: function(result) {
                    var num = result;
                    console.log("limitReservations.php -- success!!"); //Koala

                    //if limitReservations.php echoes 1, then the user has not reached their max reservations for the week
                    //request.php will be executed and the reservation will be inserted into the reservation table

                    if (num == 1) {
                        $.ajax({
                            type: 'post',
                            url: 'request.php',
                            data: {
                                user: user,
                                date: date,
                                time: time,
                                curr: userData.user_num,
                                desktop: desktop
                            },
                            success: function(result) {
                                alert(result);
                                $('#calendar').fullCalendar("refetchEvents");
                                console.log("request.php -- success!!"); //Koala
                            },
                            error: function(result) {
                                console.log(result); //Koala
                            },
                            failure: function(result) {
                                console.log(result);
                            }
                        });
                    }


                    //if limitReservations.php does not echo 1, then the user has reached their max reservations for the week
                    //they will not be permitted to request more timeslots until they release one they currently have
                    else {
                        $("#dialog-confirm").dialog("close"); // Koala
                        alert("You already have 3 timeslots reserved between " + formatMon + " and " + formatSun);
                    }
                } // end of success() function
        });
    } // end of else...selected timeslot is not reserved
} // end of requestSlot


//code from http://lifelongprogrammer.blogspot.com/2014/06/js-get-first-last-day-of-current-week-month.html

function getMondayOfCurrentWeek(d) {
    var day = d.getDay();
    return new Date(d.getFullYear(), d.getMonth(), d.getDate() + (day == 0 ? -6 : 1) - day);
}


//code from http://lifelongprogrammer.blogspot.com/2014/06/js-get-first-last-day-of-current-week-month.html

function getSundayOfCurrentWeek(d) {
    var day = d.getDay();
    return new Date(d.getFullYear(), d.getMonth(), d.getDate() + (day == 0 ? 0 : 7) - day);
}


//if a user selects a timeslot and clicks the Release button, this function will execute
//author: Cassandra Bailey

function releaseSlot() {
    var time = $('#time').val();
    var date = $('#date').val();
    var desktop = $('#desktop').val();
    var user = $('#user').val();

    //if the current user does not have the selected timeslot reserved, they are not permitted to release it

    if (userData.user_num != user) {
        $("#dialog-confirm").dialog("close"); // Koala
        alert("You cannot release a timeslot which you don't have reserved.");
    }

    //if the current user has the timeslot reserved, release.php will be executed and the reservation will be removed from
    //the reservation table in the database
    else {
        $.ajax({
            type: 'post',
            url: 'release.php',
            data: {
                curr: userData.user_num,
                time: time,
                date: date,
                desktop: desktop
            },
            success: function(result) {
                alert(result);
                $("#calendar").fullCalendar("refetchEvents");
                console.log("release.php -- success!!" + result); //Koala
            },
            error: function(result) {
                console.log(result); //Koala
            },
            failure: function(result) {
                console.log(result);
            }
        });
    }
}



function getDesktopName() {
    return document.getElementById("Desktop").text;
}

//when desktop chosen from dropdown and Submit button clicked, the desktop name will be displayed
//author: Cassandra Bailey
function submitFunction() {
    var x = document.getElementById("demo").value;
    if (x != "") {
        submitted = 1;
    }
    $('#calendar').fullCalendar('rerenderEvents');
}


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
        var h = a.getHours();
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
        installationData.forEach(function(row) {
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
}





function BuildCalendar() {

    $(document).ready(function() {

        //BEGIN : Koala modifications
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
                    click: function() {
                        requestSlot();
                        redisplay();
                        $(this).dialog("close");
                    }
                },
                {
                    id: "btnRelease",
                    text: "Release",
                    icon: "ui-icon-minus",
                    click: function() {
                        releaseSlot();
                        redisplay();
                        $(this).dialog("close");
                    }
                }
            ]
        });
        //END : Koala modifications



        $('#calendar').fullCalendar({
            events: {
                url: 'api/get_reservations.php',
                type: 'GET',
                success: function(data) {
                    console.log('reservations.php - success!! ' + data);
                    $('#calendar').fullCalendar('rerenderEvents');
                },
                error: function(data) {
                    console.log(data);
                },
                failure: function(data) {
                    console.log(data);
                }
            },
            minTime: "06:00:00",
            maxTime: "24:00:00",
            firstHour: "06:00:00",
            schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
            timezone: 'local',
            defaultView: 'agendaWeek',
            //aspectRatio: 1.8,
            resourceGroupField: 'desktop',
            navLinks: true, // can click day/week names to navigate views
            unselectAuto: false,
            selectable: true,
            selectHelper: true,
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            allDaySlot: false,
            slotDuration: "03:00:00",
            contentHeight: 'auto',
            eventDurationEditable: false, //prevents event from being resize

            header: {
                left: 'prev,next,today',
                center: 'title',
                right: 'month,agendaWeek'
            },

            defaultDate: getTodaysDate(),

            selectAllow: function(selectInfo) {
                var duration = moment.duration(selectInfo.end.diff(selectInfo.start));

                if (duration.asHours() > 3) {
                    $('#calender').fullCalendar('unselect');
                    return false;
                }
                return true;
            },

            unselect: function(event) {
                console.log(event);
            },

            select: function(start, end) {
                end = start + 1.08e+7; // enforces the 3hr blocks. (milliseconds)
                $("#dialog-confirm").dialog("open"); // Shows the Reservation Dialog Box
                var check = checkForInfoDisplay(start, end);
                if (check == false) {
                    $('#calendar').fullCalendar('unselect');
                }
            },

            eventClick: function(event, element) {

                //BEGIN : Koala modifications 
                $("#dialog-confirm").dialog("open"); // Shows the Reservation Dialog Box
                $('#calendar').fullCalendar('updateEvent', event);
                //END : Koala modifications

                document.getElementById('reservedBy').value = event.name;
                document.getElementById('date').value = event.date;
                document.getElementById('time').value = event.time;
                document.getElementById('desktop').value = event.id;

                installationData.forEach(function(row) {
                    if (row.dtopID == event.id) {

                        document.getElementById("desktopForm").value = row.dtopName;
                    }
                });
                document.getElementById('user').value = event.user;
                $('#calendar').fullCalendar('updateEvent', event);
            },

            eventOverlap: function(stillEvent, movingEvent) {
                return stillEvent.allDay && movingEvent.allDay;
            },

            eventDrop: function(event, delta, revertFunc) {

                setStartEndTime(event.start, event.end);
            },
            eventRender: function(event, element) {
                desktop = document.getElementById('demo').value;
                if (desktop == '' || desktop == event.id) {
                    return element;
                }
                return false;
            },
        });

    });
} // BuildCalendar