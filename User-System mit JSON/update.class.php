<?php
class UpdateUser {
  private $id;
  private $username;
  private $storage = "users.json";
  private $stored_users;
  private $update_user;
  public $error;
  public $success;

  public function __construct($id, $username) {
    $this->id = $id;
    $this->username = trim($username);
    if (empty($username)) {
      $this->error = "Username cannot be empty.";die();
    }
    $this->stored_users = json_decode(file_get_contents($this->storage), true);
    $this->update();
  }
  private function update() {
    foreach ($this->stored_users as $user) {
      if ($user['id'] == $this->id) {
        $this->update_user = [
          "username" => $this->username,
        ];
        $this->stored_users[$user['id']-1] = array_merge($this->stored_users[$user['id']-1], $this->update_user);
        file_put_contents($this->storage, json_encode($this->stored_users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
      }
    }
  }
}
?>