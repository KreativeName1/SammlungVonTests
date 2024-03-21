<html>
  <head>
    <title>Artikel</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <script src="like_dislike.js"></script>
    <?php include 'functions.php'; session_start();?>
  </head>
  <body>
  <div id="profilePopup">
  <?php $user=$_SESSION["user"]; $aUser=get_user($user);if($aUser[5] != "user") echo "<a href='publish.php'>Erstellen</a>"; echo "<a href='profile.php?id=$user'>Profil</a>"; ?>
      <a href="settings.php">Einstellungen</a>
      <a href="logout.php">Abmelden</a>
    </div>
    <header>
      <h1>Webseite</h1>
      <nav>
        <?php navbar(""); ?>
      </nav>
    </header>
    <main class="publish-main">
      <h1>Login</h1>
      <form action="" class="form-popup panel" method="get">
        <label for="email">E-Mail</label>
        <input type="email" name="email"required placeholder="E-Mail"/></div>
        <label for="passwort">Passwort</label>
        <input type="password" name="passwort"required placeholder="Passwort"/></div>
        <input type="submit" value="Einloggen" name="submit">
        <a href="register.php">Registrieren</a>
      </form>
      <?php
      if (isset($_GET['submit'])) {
        $password = $_GET["passwort"];
        $email = $_GET["email"];
        $password = sha1($password);
        $json = file_get_contents("users.json");
        $users = json_decode($json, true);
        foreach ($users as $user) {
          if ($user["email"] == $email && $user["password"] == $password) {
            session_start();
          $_SESSION["user"] = $user["id"];
          $_SESSION["role"] = $user["role"];
          $_SESSION["logged_in"] = true;
            header("Location: artikelseite.php");
          }
        }
      }
    ?>
    </main>
</html>