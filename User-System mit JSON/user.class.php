<?php
class RegisterUser {
  private $email;
  private $username;
  private $raw_password;
  private $encrypted_password;
  public $error;
  public $success;
  private $storage = "users.json";
  private $stored_users; // array
  private $new_user; // array

  public function __construct($email, $username, $password) {
    $this->username = filter_var(trim($username), FILTER_SANITIZE_STRING);
    $this->email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    $this->raw_password = filter_var(trim($password), FILTER_SANITIZE_STRING);
    $this->encrypted_password = password_hash($this->raw_password, PASSWORD_DEFAULT);
    $this->stored_users = json_decode(file_get_contents($this->storage), true);

    $this->new_user = [
      "id" => count($this->stored_users) + 1,
      "username" => $this->username,
      "email" => $this->email,
      "password" => $this->encrypted_password
    ];
    if ($this->checkValues()) {
      $this->insert_user();
    }
  }
  private function checkValues() {
    if (empty($this->username) || empty($this->email) || empty($this->raw_password)) {
      $this->error = "Please fill out all fields.";
    }
    else return true;
  }
  private function usernameExists() {
    foreach ($this->stored_users as $user) {
      if ($user["username"] == $this->username) {
        $this->error = "Username already exists.";
        return true;
      }
    }
    return false;
  }
  private function emailExists() {
    foreach ($this->stored_users as $user) {
      if ($user["email"] == $this->email) {
        $this->error = "E-Mail already exists.";
        return true;
      }
    }
    return false;
  }
  private function insert_user() {
    if ($this->usernameExists() == false && $this->emailExists() == false) {
      array_push($this->stored_users, $this->new_user);
      if (file_put_contents($this->storage, json_encode($this->stored_users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX)) {
        return $this->success = "Successfully registered.";
      }
      else {
        $this->error = "Something went wrong.";
      }
    }
  }
}
?>