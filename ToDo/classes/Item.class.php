<?php
  include 'classes/Connection.class.php';
  class Item {
    private $name;
    private $description;
    private $due_date;
    private $due_time;
    private $importance;
    private $user_id;

    public function __construct($name, $description, $due_date, $due_time, $importance) {
      $this->name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
      $this->description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
      $this->due_date = $due_date;
      $this->due_time = $due_time;
      $this->importance = $importance;
      $this->user_id = $_SESSION['user_id'];
      $this->add();
    }

    public function add() {
      $connection = new Connection();

      if ($this->checkDue()) {
        $sql = 'INSERT INTO items (name, description, due_date, due_time, importance, user_id) VALUES (?, ?, ?, ?, ?, ?)';
        $connection->preparedQuery($sql, 'ssssss', [$this->name, $this->description, $this->due_date, $this->due_time, $this->importance, $this->user_id]);
      } else {
        echo 'Date is in the past!';
      }

      $connection->close();
    }

    public function checkDue() {
      // check if date is in the past
      $date = new DateTime($this->due_date);
      $now = new DateTime();
      if ($date < $now) {
        return false;
      }
      // if date is today, check if time is in the past
      if ($date == $now) {
        $time = new DateTime($this->due_time);
        if ($time < $now) {
          return false;
        }
      }
      return true;
    }
  }
?>