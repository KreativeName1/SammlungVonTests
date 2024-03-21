<?php
// Diese Klasse ist für die Verbindung zur Datenbank zuständig und enthält Funktionen, die mit der Datenbank interagieren
// Funktionen:
// - connect(): Verbindet mit der Datenbank
// - query($sql): Führt eine SQL-Abfrage aus
// - check($table, $column, $value): Überprüft ob ein Wert in einer Tabelle existiert
// - getResults($sql): Gibt die Ergebnisse einer SQL-Abfrage zurück
// - preparedQuery($sql, $types, $values): Führt eine SQL-Abfrage mit Prepared Statements aus
// - close(): Schließt die Verbindung zur Datenbank

class Connection
{

   // Variablen
   private $host;
   private $user;
   private $passwd;
   private $schema;
   public $mysqli;

   // Konstruktor
   public function __construct()
   {
      $this->host = 'localhost';
      // $this->user = 'todo-app';
      // $this->passwd = 'fD3d_3';
      $this->user = 'root';
      $this->passwd = '';
      $this->schema = 'todo-app';
      $this->connect();
   }

   // Funktion zum Verbinden mit der Datenbank
   public function connect()
   {
      $this->mysqli = new mysqli($this->host, $this->user, $this->passwd, $this->schema);

      //! nur für Debugging
      if (!is_null($this->mysqli->connect_error))
      {
         echo 'Connection failed<br>';
         echo 'Error number: {$this->mysqli->connect_errno}<br>';
         echo 'Error message: {$this->mysqli->connect_error}<br>';
         die();
      }
   }

   // Funktion zum Ausführen von SQL-Abfragen //* ohne Prepared Statements
   public function query($sql)
   {
      $result = $this->mysqli->query($sql);

      //! nur für Debugging
      if (!$result)
      {
         echo 'Query failed<br>';
         echo 'Error number: {$this->mysqli->errno}<br>';
         echo 'Error message: {$this->mysqli->error}<br>';
         die();
      }
      return $result;
   }

   // Funktion zum Überprüfen, ob ein Wert in einer Tabelle existiert
   public function check($table, $column, $value)
   {
      $sql = "SELECT * FROM $table WHERE $column = ?";
      $stmt = $this->preparedQuery($sql, 's', array($value));
      $result = $stmt->get_result();
      if ($result->num_rows > 0)
      {
         return true;
      }
      return false;
   }
   public function checkAndGet($table, $column, $value)
   {
      $sql = "SELECT * FROM $table WHERE $column = ?";
      $stmt = $this->preparedQuery($sql, 's', array($value));
      $result = $stmt->get_result();
      if ($result->num_rows > 0)
      {
         return $result->fetch_assoc();
      }
      return false;
   }
   // Funktion zum Ausführen von SQL-Abfragen und Rückgabe der Ergebnisse
   public function getRows($sql)
   {
      $result = $this->query($sql);
      $rows = array();
      while ($row = $result->fetch_assoc())
      {
         $rows[] = $row;
      }
      return $rows;
   }
   // Funktion zum Ausführen von SQL-Abfragen mit Prepared Statements
   public function preparedQuery($sql, $types, $params)
   {
      $stmt = $this->mysqli->prepare($sql);

      //! nur für Debugging
      if (!$stmt)
      {
         echo 'Prepare failed<br>';
         echo 'Error number: {$this->mysqli->errno}<br>';
         echo 'Error message: {$this->mysqli->error}<br>';
         die();
      }

      $stmt->bind_param($types, ...$params);
      $stmt->execute();

      return $stmt;
   }
   // Funktion zum Schließen der Datenbankverbindung
   public function close()
   {
      $this->mysqli->close();
   }
}
?>