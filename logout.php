<?php
/**
 * Destroy the current session removing all cookies and session data.
 * Taken from the PHP Documentation at https://www.php.net/manual/en/function.session-destroy.php
 */
// Initialize the session.
session_start();

  function logout() {
    // Unset all of the session variables.
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
          $params["path"], $params["domain"],
          $params["secure"], $params["httponly"]
      );
    }

    // Finally, destroy the session.
    session_destroy();

    // Redirect to login page
    header('location: Views/login');
  }

  logout();


?>
