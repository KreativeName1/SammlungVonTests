<html>
  <head>
    <title>Artikel</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
  <script type='module' src='https://md-block.verou.me/md-block.js'></script>
    <script src="functions.js"></script>
    <?php
    include 'functions.php';
    include 'user.class.php';
    session_start();
    $current_id = $_SESSION["user"];
    $current_user = get_user($current_id);
    $current_role = $current_user[5];
    $url = $_GET["id"];
    $url = explode("-", $url);
    $user_id = $url[0];
    $post_id = $url[1];
    $users = file_get_contents("users.json");
    $users = json_decode($users, true);
    ?>
  </head>
  <body>
    <div id="profilePopup">
      <?php if($current_role != "user") echo "<a href='publish.php'>Erstellen</a>"; echo "<a href='profile.php?id=$current_id'>Profil</a>"; ?>
      <a href="settings.php">Einstellungen</a>
      <a href="logout.php">Abmelden</a>
    </div>
    <header>
      <h1>Webseite</h1>
      <nav>
        <?php navbar(""); ?>
      </nav>
    </header>
    <main class="article-page">
    <div>
      <?php
      foreach ($users as $user)
        if ($user["id"] == $user_id)
          foreach ($user["posts"] as $post)
            if ($post["id"] == $post_id) $banned = $post["banned"];

      if ($current_role == "admin") echo "<h1 class='center'>Administrator</h1>";
      else if ($current_role == "moderator") echo "<h1 class='center'>Moderator</h1>";
      else if ($current_role == "author") echo "<h1 class='center'>Keine Rechte!</h1>";
      else if ($current_role == "user") echo "<h1 class='center'>Keine Rechte!</h1>";
      if (!in_array($current_role, array("author", "user"))) {
        ?>
      <form action="" method="post" class="form-popup panel" enctype='multipart/form-data'>
        <h1>Post Einstellungen</h1>
        <div>
        <label for="ban">Gebannt:</label>
        <input type="checkbox" name="ban" <?php if ($banned) echo "checked";?>>
        </div>
        <input type="submit" name="submit" value="Speichern">
        <a href="javascript:history.back()">Zurück</a>
      </form>
      <?php
        if (isset($_POST["submit"])){
          if (isset($_POST["ban"])) $banned = true;
          else $banned = false;
          $userC = new User("users.json");
          $userC->updatePost($post_id, $user_id, "banned", $banned);
          header("Location: ban.php?id=$user_id-$post_id");
        }
      }
      ?>
    </div>
    </main>
  </body>
</html>