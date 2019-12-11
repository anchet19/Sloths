// Log the user out deleting any session info and redirect to login page
function logout() {
  sessionStorage.clear();
  fetch('../logout.php');
  window.location.href = "login";
}