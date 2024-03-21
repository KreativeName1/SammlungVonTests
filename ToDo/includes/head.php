<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../reset.css">
  <link rel="stylesheet" href="sass/main.css">
  <?php
    session_start();

    // Url holen und die Name der Seite speichern
    $url = explode('/', $_SERVER['REQUEST_URI']);
    $url = end($url);

    // Wenn die Seite nicht login.php oder register.php ist, dann prüfen ob der User eingeloggt ist
    if ($url != 'login.php' && $url != 'register.php' && !isset($_SESSION['user_id'])) {
        header('Location: login.php');
    }
    else  {
      $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
      $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    }
  ?>
</head>