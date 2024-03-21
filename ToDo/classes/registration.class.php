<?php
// Klasse für die Registrierung eines neuen Benutzers in der Datenbank
// Funktionen:
// - checkEmail(): Überprüft ob die Email bereits in der Datenbank existiert
// - checkPassword(): Überprüft ob die Passwörter übereinstimmen und ob das Passwort die Anforderungen erfüllt
// - addToDb(): Fügt die Werte in die Datenbank ein

require_once 'Connection.class.php';
class Registration {

  // Variablen
  private $username;
  private $passwd;
  private $passwd2;
  private $enc_passwd;
  private $firstname;
  private $lastname;
  private $email;
  private $connection;
  public $error;
  public $success;

  // Konstruktor
  function __construct($email,$password, $password2, $firstname, $lastname, $username)
  {
    // Erstellt eine neue Verbindung zur Datenbank
    $this->connection = new Connection();

    // Speichert die Werte in die Klassenvariablen und filtert diese
    $this->passwd = $password;
    $this->passwd2 = $password2;
    $this->firstname = htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8');
    $this->lastname = htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8');
    $this->email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $this->username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

    // Überprüft die Werte
    if (empty($this->email) || empty($this->passwd) || empty($this->passwd2) || empty($this->firstname) || empty($this->lastname) || empty($this->username)) {
      $this->error = 'Bitte alle Felder ausfüllen!';
    }
    else {
    $this->checkEmail();
    $this->checkPassword();
    $this->checkUsername();

    // Verschlüsselt das Passwort
    $this->enc_passwd = password_hash($this->passwd, PASSWORD_DEFAULT);

    // Wenn keine Fehler aufgetreten sind wird der Benutzer in die Datenbank eingetragen
    if (empty($this->error)) $this->addToDb();
    }
  }

  // Überprüft ob die Email bereits existiert
  function checkEmail()
  {
    if (empty($this->email)) {
      $this->error = 'Email ist erforderlich!';
    }
    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      $this->error = 'Email ist ungültig!';
    }

    $check = $this->connection->check('users', 'email', $this->email);
    if ($check) $this->error = 'Diese Email ist bereits registriert!';
  }

  // Überprüft ob das Passwort den Anforderungen entspricht
  function checkPassword()
  {
    if ($this->passwd != $this->passwd2) {
      $this->error = 'Passwörter stimmen nicht überein!';
    }
    if (strlen($this->passwd) < 8) {
      $this->error = 'Das Passwort muss mindestens 8 Zeichen lang sein!';
    }
    else if (!preg_match("#[0-9]+#", $this->passwd)) {
      $this->error = 'Das Passwort muss mindestens eine Zahl enthalten';
    }
    else if (!preg_match("#[a-zA-Z]+#", $this->passwd)) {
      $this->error = 'Das Passwort muss mindestens einen Buchstaben enthalten';
    }
    else if (!preg_match("#[A-Z]+#", $this->passwd)) {
      $this->error = 'Das Passwort muss mindestens einen Großbuchstaben enthalten';
    }
    else if (!preg_match("#[a-z]+#", $this->passwd)) {
      $this->error = 'Das Passwort muss mindestens einen Kleinbuchstaben enthalten';
    }
  }

  // Überprüft ob der Benutzername bereits existiert
  function checkUsername()
  {
    $check = $this->connection->check('users', 'username', $this->username);
    if ($check) $this->error = 'Dieser Benutzername ist bereits vergeben!';
  }
  function addToDb()
  {
    // Fügt die Daten in die Datenbank ein
    $sql="INSERT INTO users (email,password,first_name,last_name,username) values(?, ?, ?, ?, ?)";
    $result = $this->connection->preparedQuery($sql, 'sssss', [$this->email, $this->enc_passwd, $this->firstname, $this->lastname, $this->username]);

    // Überprüft ob die Daten erfolgreich in die Datenbank eingefügt wurden
    if ($result) {
      $this->success = 'Registrierung erfolgreich! Wird weitergelietet...';
      // Leitet nach 3 Sekunden auf die Login-Seite weiter
      header( "refresh:3;url=login.php" );
    }
    else {
      $this->error = 'Registrierung fehlgeschlagen!';
    }

    // Schließt die Verbindung zur Datenbank
    $this->connection->close();
  }
}