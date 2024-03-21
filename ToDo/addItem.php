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

    require_once 'classes/Item.class.php';
    if (isset($_POST['submit'])) {
      $task = new Item(
        $_POST['name'],
        $_POST['description'],
        $_POST['date'],
        $_POST['time'],
        $_POST['importance'],
      );

      // wait 3 seconds and redirect to display.php
      header("Refresh:3; url=display.php");
    }

    ?>
  </head>
  <body class="centered vertical">
    <h1>ToDo Liste</h1>
    <form action="" method="post" class="flex">
      <input type="text" name="name" placeholder="Aufgabe hinzufügen">
      <textarea name="description" placeholder="Beschreibung"></textarea>
      <!-- Datum -->
      <div class=flex>
        <input type="date" name="date">
        <input type="time" name="time">
      </div>
      <!-- Wichtigkeit -->
      <div class="flex">
        <div class="flex"><input type="radio" value="Niedrig"  name="importance">Niedrig</div>
        <div class="flex"><input type="radio" value="Mittel" name="importance">Mittel</div>
        <div class="flex"><input type="radio" value="Hoch" name="importance">Hoch</div>
      </div>
      <input type="submit" name="submit" value="Hinzufügen">
    </form>
</html>