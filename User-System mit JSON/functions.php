<?php
function get_user($id) {
  $users = json_decode(file_get_contents("users.json"), true);
  foreach ($users as $user) {
    if ($user['id'] == $id) {
      return [
        "id" => $user['id'],
        "username" => $user['username'],
        "email" => $user['email']
      ];
    }
  }
}
?>