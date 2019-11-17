/**
 * Handles all the logic for the user dashboard page.
 * author: Chris Ancheta
 * date: 2019-11-12
 */

 let lastVisible = undefined;
$(document).ready(function () {

  hydrateTable();

  /**
   * Creates the feedback dialog box using the dialog div in dashboard.php
   */
  $("#feedback-dialog").dialog({
    position: { my: "center", at: "center", of: window},
    resizable: false,
    height: "auto",
    width: 400,
    autoOpen: false,
    modal: true,
    closeOnEscape: true,
    buttons: [{
      id: "btnSubmit",
      text: "Submit",
      name: "feedback-submit",
      type: "submit",
      click: function () {
        handleSubmitFeedback();
        $(this).dialog("close");
      }
    }]
  });

  /**
   * Creates the comment dialog box using the dialog div in dashboard.php
   */
  $("#comment-dialog").dialog({
    resizable: false,
    height: "auto",
    width: 400,
    autoOpen: false,
    modal: true,
    closeOnEscape: true,
  });

  // WIP tooltip - currently not utilized
  $("comment.tip").tooltip({
    classes: {
      "ui-tooltip": "highlight",
      "ui-tooltip-content": "inline-block"
    },
    position: {
      my: "center bottom",
      at: "center top-10",
      collision: "none"
    }
  });

  $("newPwd-form").submit((event) => {
    event.preventDefault();
    
  })
});

/**
 * Hydrates the reservations table
 */
function hydrateTable() {
  fetch('../api/get_user_reservations_data.php', {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: $.param({
      username: sessionStorage.username
    })
  }).then((response) => {
    response.json().then((result) => {
      // Store the table element for use later
      const tbody = document.getElementById('table-body')
      // Capture the table head and table body data
      const headings = result.headings;
      const data = result.data;
      // Generate the markup for the table head
      let headerMarkup = `<tr class="table-header" id="table-header">`;
      headings.forEach((item) => { headerMarkup += `<th>${item}</th>` });
      headerMarkup += `</tr>`
      document.getElementById('table-head').innerHTML = headerMarkup;
      // Generate the markup for the table body
      data.forEach((row) => {
        const maxlength = 100;
        const rowArray = Object.entries(row);;
        const tr = document.createElement('tr');
        rowArray.forEach(([key, value]) => {
          const td = document.createElement('td');
          // Conditionally populate the outcome cell of the table
          if (key === 'outcome') {
            const now = moment();
            const startTime = moment(row.date + ' ' + row.time);
            // If DateTime now > DateTime reservation and no feedback provided,
            // make a link to open the form. Oterwise, display Coming Up
            if (value == null) {
              if (now < startTime) {
                td.innerHTML = "COMING UP";
                td.value = "upcoming"
                td.name = "outcome";
              } else {
                const a = document.createElement('a');
                a.className = "feedback-link";
                a.value = row.id;
                a.name = "outcome";
                a.href = '#';
                a.style = 'color: red';
                a.innerHTML = 'Needs Feedback';
                // Add an event listener to each link that will be called when it is clicked
                a.addEventListener('click', (event) => {
                  showFeedbackDialog(event.target.value);
                })
                td.appendChild(a);
              }
            }
            else {
              td.innerHTML = value.toUpperCase();
            }

          }
          // Populate the comment cell of the table
          else if (key === 'comment') {
            // If comment value is not null, then check to see if it's too long
            // if not then display full comment, otherwise truncate and store full comment in title field
            if (value != null) {
              a = document.createElement('a');
              a.href = '#';
              a.className = 'comment-link';
              a.value = value;
              a.style = "color: blue";
              a.innerHTML = `View`;
              a.addEventListener('click', (event) => {
                showCommentDialog(event.target.value);
              })
              td.appendChild(a);
            }
            // No comment exists
            else {
              td.innerHTML = '';
            }
          }
          // Value is not related to either outcome or comment
          else {
            td.innerHTML = value;
          }
          // hydrate the row and the table
          tr.appendChild(td);
          tbody.appendChild(tr);
        })
      });

      $('#reservation-table').DataTable({
        autoWidth: true,
        paging: true,
        order: [[1, "desc"], [4, "desc"]]
      });
    });
  })
}

/**
 * 
 * @param {*} id 
 */
function makeVisible(id) {
  const curr = document.getElementById(lastVisible)
  if (curr) {
    curr.style.display = "none";
  }
  next = document.getElementById(id).style.display = "block";
  lastVisible = id;
}

/**
 * Dynamically populate the list of outcome options in the feedback form
 * @param {Int} id The reservation id to provide feedback for
 */ 
function showFeedbackDialog(id) {
  fetch('../api/get_feedback_values', {
    method: 'get'
  }).then((result) => {
    result.json().then((data) => {
      const outcomeSelect = document.getElementById('outcome-select');
      outcomeSelect.innerHTML = '';
      data.forEach((item) => {
        const option = document.createElement('option');
        option.setAttribute('value', item);
        option.innerHTML = item.toUpperCase();
        outcomeSelect.append(option);
      })
    })
  })
  document.getElementById('reservation').value = id;
  $("#feedback-dialog").dialog("open");
}

function showCommentDialog(comment) {
  document.getElementById('comment-dialog-body').innerHTML = comment;
  $('#comment-dialog').dialog('open');
}

/**
 * Called when the feedback form is submitted
 */
function handleSubmitFeedback() {
  const form = document.getElementById('feedback-form');
  const formattedFormData = new FormData(form);

  fetch('../api/insert_feedback.php', {
    method: 'post',
    body: formattedFormData
  }).then((response) => {
    if (response.status === 200) {
      window.location.reload();
    }
  }).then(() => {
    hydrateTable();
  })
}