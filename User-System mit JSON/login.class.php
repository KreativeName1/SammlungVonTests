<?php
class LoginUser {
  private $email;
  private $password;
  public $error;
  public $success;
  private $storage = "users.json";
  private $stored_users;

  public function __construct($email, $password) {
    $this->email = $email;
    $this->password = $password;
    $this->stored_users = json_decode(file_get_contents($this->storage), true);
    $this->login();
  }
  private function login() {
    foreach ($this->stored_users as $user) {
      if ($user['email'] == $this->email && password_verify($this->password, $user['password']) == true) {
          session_start();
          $_SESSION['user'] = $user['id'];
          header("Location: account.php"); exit();
      }
    }
    return $this->error = "Wrong E-Mail or Password.";
  }
}
?>