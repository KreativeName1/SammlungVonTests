<html>
  <head>
    <title>Artikel</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
  <script type='module' src='https://md-block.verou.me/md-block.js'></script>
    <script src="functions.js"></script>
    <?php
    include 'functions.php';
    session_start();
    $user_id = $_GET["id"];
    $current_id = $_SESSION["user"];
    $current_user = get_user($current_id);
    $current_role = $current_user[5];
    $selected_user = get_user($user_id);
    $selected_role = $selected_user[5];
    $selected_state = $selected_user[6];
    $json = file_get_contents("users.json");
    $users = json_decode($json, true);
    ?>
  </head>
  <body>
    <div id="profilePopup">
      <?php ;if($current_role != "user") echo "<a href='publish.php'>Erstellen</a>"; echo "<a href='profile.php?id=$current_id'>Profil</a>"; ?>
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
      if ($current_role == "admin") echo "<h1 class='center'>Administrator</h1>";
      else if ($current_role == "moderator") echo "<h1 class='center'>Moderator</h1>";
      else if ($current_role == "author") echo "<h1 class='center'>Keine Rechte!</h1>";
      else if ($current_role == "user") echo "<h1 class='center'>Benutzer</h1>";
      if ($current_role != "author" && $current_role != "user") {
        ?>
      <form action="" method="post" class="form-popup panel" enctype='multipart/form-data'>
        <h1>Benutzer Einstellungen</h1>
        <div>
        <label for="ban">Gebannt:</label>
        <input type="checkbox" name="ban" <?php if ($selected_state == true) echo "checked";?>>
        </div>
        <?php if ($current_role == "admin") { ?>
          <fieldset>
        <legend for="role">Rolle</legend>
        <select name="role">
          <option value="user" <?php if ($selected_role == "user")  echo "selected"?>>Benutzer</option>
          <option value="author"<?php if ($selected_role == "author")  echo "selected"?>>Autor</option>
          <option value="moderator"<?php if ($selected_role == "moderator")  echo "selected"?>>Moderator</option>
          <option value="admin"<?php if ($selected_role == "admin")  echo "selected"?>>Administrator</option>
        </select>
        </fieldset>
        <?php } ?>
        <input type="submit" name="submit" value="Speichern">
        <a href="javascript:history.back()">Zurück</a>
      </form>
      <?php
        if (isset($_POST["submit"])) {
          if (isset($_POST["ban"])) $banned = true;
          else $banned = false;
          if ($current_role == "admin") $new_role = $_POST["role"];
          foreach ($users as $key => $user) {
            if ($user["id"] == $id) {
              $users[$key]["banned"] = $banned;
              if ($current_role == "admin" && isset($new_role)) $users[$key]["role"] = $new_role;
              $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
              file_put_contents("users.json", $json, LOCK_EX);
            }
          }
          header("Location: panel.php?id=$id");
        }
      }
      ?>
    </div>
    </main>
  </body>
</html>