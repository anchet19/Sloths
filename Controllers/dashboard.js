/**
 * Handles all the logic for the user dashboard page.
 * 
 * author: Chris Ancheta
 * date: 2019-11-12
 */


$(document).ready(function () {
  /**
   * Creates the feedback dialog box using the dialog div in dashboard.php
   */
  $("#dialog").dialog({
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

  // Creates an on click event listener for the "Needs Feedback" links
  $('.feedback-link').click((event) => {
    document.getElementById('reservation').value = event.target.id;
    $("#dialog").dialog("open");
  });
});

/**
 * Called when the feedback form is submitted
 */
function handleSubmitFeedback(){
  const form = document.getElementById('feedback-form');
  const formattedFormData = new FormData(form);

  fetch('../api/insert_feedback.php', {
    method: 'post',
    body: formattedFormData
  }).then((response) => {
    if(response.status === 200){
      window.location.reload();
    }
  })
}