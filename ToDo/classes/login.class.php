<?php
// Klasse für die Loginfunktion 
// Funktionen:
// - __construct($email, $passwd): Konstruktor
// - checkEmail(): Überprüft ob die Email in der Datenbank existiert
// - checkPassword(): Überprüft ob das Passwort korrekt ist

require_once 'Connection.class.php';
class Login {

  // Variablen
  private $email;
  private $passwd;
  private $connection;
  public $error;
  public $success;

  // Konstruktor
  function __construct($email, $passwd)
  {
    // Erstellt eine neue Verbindung zur Datenbank
    $this->connection = new Connection();

    // Speichert die Werte in die Klassenvariablen und filtert diese
    $this->email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $this->passwd = htmlspecialchars($passwd, ENT_QUOTES, 'UTF-8');

    // Überprüft die Werte
    if (empty($this->email) || empty($this->passwd)) {
      $this->error = 'Bitte alle Felder ausfüllen!';
    }
    else {
      $this->checkEmail();
      if (empty($this->error)) {
        $this->checkPassword();
        if (empty($this->error)) {
          $this->login();
          $this->connection->close();
        }
      }
    }
  }

  // Überprüft ob die Email in der Datenbank existiert
  function checkEmail()
  {
    $check = $this->connection->check('users', 'email', $this->email);
    if (!$check) {
      $this->error = 'Diese Email existiert nicht!';
    }
  }

  // Überprüft ob das Passwort korrekt ist
  function checkPassword()
  {
    // use checkAndGet() from connetion to get the result
    $result = $this->connection->checkAndGet('users', 'email', $this->email);
    $password = $result['password'];
    if (!password_verify($this->passwd, $password)) {
      $this->error = 'Das Passwort ist falsch!';
    }
  }

  function login() {

    // Die User ID wird aus der Datenbank ausgelesen
    $result = $this->connection->checkAndGet('users', 'email', $this->email);
    $user_id = $result['user_id'];

    // Die User ID wird in die Session gespeichert
    $_SESSION['user_id'] = $user_id;
    $this->success = 'Login erfolgreich!';
    header( "refresh:1;url=index.php?page=home" );
  }
}
?>