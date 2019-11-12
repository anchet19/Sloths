<?php 
/**
 * Called when the Admin submits the changeUserPassword form.
 * 
 * author: Chris Ancheta
 * created: 2019-11-9
 */
if(!include_once("../Utils/connect.php")){
  die("error finding connect file");
}

try {
  $db = ConnectDB();
  $user = $_POST["user-select"];
  $new_pass = $_POST["newPassword"];
  $new_pass_c = $_POST["newPassword_confirm"];

  if(empty($user) || empty($new_pass) || empty($new_pass_c)) {
    echo json_encode('{"result": false, "message": "All fields required."}');
  }
  else if($new_pass == $new_pass_c) {
    $new_pass = password_hash($new_pass,PASSWORD_DEFAULT);
    $sql = "UPDATE user SET password='$new_pass', login_attempts = 0 WHERE user_num='$user'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    echo json_encode(array("result" => true, "message" => "Password updated successfully."));
  } 
  else {
    echo json_encode(array("result" => false, "message" => "Passwords do not match."));
  }
} catch (\Error $e) {
  echo json_encode($e->getMessage());
}
?>