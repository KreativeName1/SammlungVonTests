<html>
  <head>
    <title>Artikel</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <script src="like_dislike.js"></script>
    <?php
    include 'functions.php';
    require 'user.class.php';
    session_start();
    $current_id = $_SESSION['user'];
    $current_user = get_user($current_id);
    $current_role = $current_user[5];
    $current_state = $current_user[6];
    ?>
  </head>
  <body>
  <div id="profilePopup">
  <?php if($current_role != "user") echo "<a href='publish.php'>Erstellen</a>"; echo "<a href='profile.php?id=$current_id'>Profil</a>"; ?>
      <a href="settings.php">Einstellungen</a>
      <a href="logout.php">Abmelden</a>
      <script src="functions.js"></script>
    </div>
    <header>
      <h1>Webseite</h1>
      <nav>
        <?php navbar(""); ?>
      </nav>
    </header>
    <main class="publish-main">
  <?php
  $users = get_user($_SESSION['user']);
  if ($_SESSION['logged_in'] == true && $current_role != "user" && $current_state == false) {
    ?>
      <h1>Artikel erstellen</h1>
        <form action='' method='post' class='form-popup panel' enctype='multipart/form-data'>
          <input type='text' name='title' placeholder='Titel'>
          <textarea type='text' name='content' placeholder='Beschreibung' class="desc-height"></textarea>
          <label for='file'>Markdown Datei auswählen</label>
          <input type='file' name='file' id='file'>
          <input type='submit' name='submit' value='Erstellen'>
          </form>
          <?php
        if (isset($_POST['submit'])) {
          $title = $_POST['title'];
          $content = $_POST['content'];
          $text = $_POST['text'];
          $file = $_FILES['file'];
          $file_name = $file['name'];
          $titles = array();
          $ids = array();
          $id = 0;
          $titles = array();
          $posts = array();
          $file_tmp = $file['tmp_name'];
          $file_ext = explode('.', $file_name);
          $file_ext = strtolower(end($file_ext));
          $allowed = array('md');

          if (in_array($file_ext, $allowed)) {
              move_uploaded_file($file_tmp, "uploads/$file_name");
              $json = file_get_contents("users.json");
              $users = json_decode($json, true);
              foreach ($users as $user) {
                if ($user["id"] == $current_id) {
                  $number_of_records = count($user["posts"]);
                  if ($number_of_records == 0) {
                    $id = 1;
                  }
                  else {
                    $id = $number_of_records + 1;
                  }
                  $newpost = array(
                    "id" => $id,
                    "banned" => false,
                    "title" => $title,
                    "content" => $content,
                    "file" => $file_name,
                    "created_at" => date("d-m-Y H:i:s"),
                    "viewedby" => array(),
                    "likedby" => array(),
                    "dislikedby" => array()
                  );
                  if (in_array($title, $titles)) {
                    echo "Der Titel existiert bereits";
                  }
                  else {
                    array_push($user["posts"], $newpost);
                    $users[$current_id - 1] = $user;
                    file_put_contents("users.json", json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX);
                    $updatejson = file_get_contents("users.json");
                    $updatejson = json_decode($updatejson, true);
                    foreach ($updatejson as $user) {
                      if ($user["id"] == $current_id) {
                        $posts = $user["posts"];
                        foreach ($posts as $post) {
                          if ($post["file"] == $file_name) {
                            $old_name = $post["file"];
                            $new_name = $user["id"] . "-" . $post["id"] . ".md";
                            rename("uploads/$old_name", "uploads/$new_name");
                            $updatejson[$current_id - 1]["posts"][$post["id"] - 1]["file"] = $new_name;
                            file_put_contents("users.json", json_encode($updatejson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX);
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
  }
  else if ($current_state == true) {
    echo "<h1>Du bist gesperrt!</h1>";
  }
  else if ($current_role == "user") {
    echo "<h1>Du hast keine Rechte zum Erstellen von Artikeln!</h1>";
  }
  else header("Location: login.php");
  ?>
  </main>
</html>