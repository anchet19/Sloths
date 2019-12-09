/**
 * Handles all the logic for the user dashboard page.
 * author: Chris Ancheta
 * date: 2019-11-12
 */

let lastVisible = undefined;
$(document).ready(function () {

  /**
   * Create a DataTables style table using the data retrieved from the REST API
   * for a complete list of reservations for the current users.
   */
  const table = $('#reservation-table').DataTable({
    ajax: {
      url: '../api/get_user_reservations_data.php',
      type: "POST",
      data: {
        username: sessionStorage.username
      }
    },
    // data: tableInfo,
    columns: [
      { title: 'ID', data: 'id' },
      { title: 'DATE', data: 'date' },
      { title: 'DESKTOP', data: 'desktop' },
      { title: 'BUILD', data: 'build' },
      { title: 'TIME', data: 'time' },
      {
        title: 'OUTCOME',
        data: 'outcome',
        render: (data, type, row) => {
          const now = moment();
          const startTime = moment(row.date + ' ' + row.time);
          // If DateTime now > DateTime reservation and no feedback provided,
          // make a link to open the form. Oterwise, display Coming Up
          if (data == null) {
            if (now < startTime) {
              return "COMING UP";
            }
            else {
              const a = document.createElement('a');
              a.className = "feedback-link";
              a.value = row.id;
              a.name = "outcome";
              a.href = '#';
              a.style = 'color: red';
              a.textContent = 'Needs Feedback';
              return a.outerHTML;
            }
          }
          else {
            return data.toUpperCase();
          }
        },
        // Attach an event handler to any link elements that get created
        createdCell: (td, cellData, rowData, row, col) => {
          td.firstChild.addEventListener('click', (event) => {
            showFeedbackDialog(rowData.id);
          })
        }
      },
      {
        title: 'COMMENT',
        data: 'comment',
        render: (data, type, row) => {
          // If comment value is not null, then check to see if it's too long
          // if not then display full comment, otherwise truncate and store full comment in title field
          if (data != null && data != '') {
            a = document.createElement('a');
            a.href = '#';
            a.className = 'comment-link';
            a.value = data;
            a.style = "color: blue";
            a.textContent = `View`;
            // a.addEventListener('click', (event) => {
            //   showCommentDialog(event.target.value);
            // })
            return a.outerHTML;
          }
          // No comment exists
          else {
            return '';
          }
        },
        // Attach an event handler to any link elements that get created
        createdCell: (td, cellData, rowData, row, col) => {
          if (td.firstChild) {
            td.firstChild.addEventListener('click', (event) => {
              showCommentDialog(rowData.comment);
            });
          }
        }
      }
    ],
    autoWidth: true,
    paging: true,
    order: [[1, "desc"], [4, "desc"]]
  });

  /**
   * Creates the feedback dialog box using the dialog div in dashboard.php
   */
  $("#feedback-dialog").dialog({
    position: { my: "center", at: "top", of: window},
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
      click: function (event) {
        event.preventDefault();
        handleSubmitFeedback();
        $(this).dialog("close");
      }
    }]
  });

  /**
   * Creates the comment dialog box using the dialog div in dashboard.php
   */
  $("#comment-dialog").dialog({
    position: { my: "center", at: "top", of: window },
    resizable: false,
    height: "auto",
    width: 400,
    autoOpen: false,
    modal: true,
    closeOnEscape: true,
  });

  $("newPwd-form").submit((event) => {
    makeVisible('change-pwd');
    event.preventDefault();
  })
});

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

/**
 * Show a modal containing the related reservations feedback comment
 * @param {String comment} The comment to be displayed 
 */
function showCommentDialog(comment) {
  document.getElementById('comment-dialog-body').textContent = comment;
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
      $('#reservation-table').DataTable().ajax.reload();
      form.reset();
    }
  });
}