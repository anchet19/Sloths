/**
 * Handles all the logic for the manager page.
 * author: Alex Cross
 * date: 2019-11-19
 */
var a = JSON.parse(sessionStorage.getItem("userData"));
const departmentNum = a['department'];
let lastVisible = undefined;
$(document).ready(function () {

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

function handleUserMetricsSubmit(){
    // Get the form
    const managerUserMetricsForm = document.getElementById("managerUserForm");
    // Format the form data -- Content-Type: application/x-www-form-urlencoded
    const formattedFormData = new FormData(managerUserMetricsForm);
    formattedFormData.set("department", departmentNum);
    managerUserMetricsPostData(formattedFormData);
  }

  async function managerUserMetricsPostData(formattedFormData) {
    const response = await fetch('../api/manager_user_metrics.php', {
      method: 'POST',
      body: formattedFormData      
    });
    const data = await response.text();
    document.getElementById("managerUserMetricsTable").innerHTML = data;
  }