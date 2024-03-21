<html>
  <head>
    <title>ToDo</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../standard.css">
    <link rel="stylesheet" href="../reset.css">
    <?php
    session_start();
    if (!isset($_SESSION['user_id'])) header('Location: login.php');
    ?>
  </head>
  <body class="centered vertical">
    <div class="box">
      <h1>ToDo Liste</h1>

      <a href="addItem.php"><button>Aufgabe Hinzufügen</button></a>
      <?php
      include "classes/Connection.class.php";
      $user_id = $_SESSION['user_id'];
      $connection = new Connection();

      // Holt alle Items aus der Datenbank von dem User
      $sql = "SELECT * FROM items WHERE user_id = $user_id";
      $result = $connection->getRows($sql);

      // Loopt durch alle Items
      foreach ($result as $row) {

        // Formatiert das Datum und die Uhrzeit
        $date = $row['due_date'];
        $date = date("d.m.Y", strtotime($date));
        $row['due_date'] = $date;
        $time = $row['due_time'];
        $time = date("H:i", strtotime($time));
        $row['due_time'] = $time;

        // Setzt die Klasse des Items auf die Wichtigkeit
        if ($row['importance'] == "Hoch") {
          echo "<div class='item Hoch'>";
        } else if ($row['importance'] == "Mittel") {
          echo "<div class='Mittel item'>";
        } else {
          echo "<div class='Niedrig item'>";
        }

        // Gibt das Item aus
        echo "<h2>" . $row['name'] . "</h2>";
        echo "<p>" . $row['description'] . "</p>";
        echo "<div class='DateTime'>
        <img class='icon' src='images/date-range-svgrepo-com.svg'>
        <p>{$row['due_date']}</p>
        <img class='icon'src='images/clock-svgrepo-com.svg'>
        <p>{$row['due_time']}</p>
        </div>";
        echo "<p class='importance'>" . $row['importance'] . "</p>";
        echo "<a class='delete-btn' href='deleteItem.php?id={$row['item_id']}-{$user_id}'>X</a>";
        echo "</div>";
      }
      ?>
    </div>
  </body>