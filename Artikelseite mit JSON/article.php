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
      $url = $_GET["id"];
      $url = explode("-", $url);
      $user_id = $url[0];
      $post_id = $url[1];
      if (isset($_SESSION["user"])) $current_id = $_SESSION["user"];
      if (isset($current_id)){
      $current_user = get_user($current_id);
      $current_role = $current_user[5];
      }
      else $current_role = "user";
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
    <main class='article'>
    <md-block>
    <?php
      $users = file_get_contents("users.json");
      $users = json_decode($users, true);
      foreach ($users as $user) {
        if ($user["id"] == $user_id) {
          $author = $user["username"];
          $image = $user["image"];
          if ($user["banned"] == true) {
            if (in_array($current_role,array("admin","moderator")) || $current_id == $user_id) {
              echo "<h1 style=color:red>Dieser Benutzer wurde gesperrt.</h1>";
            }
            else {
              echo "<h1>Dieser Benutzer wurde gesperrt.</h1>";
              die();
            }
          }
          foreach ($user["posts"] as $post) {
            if ($post["id"] == $post_id) {
              if ($post["banned"] == true) {
                if (in_array($current_role,array("admin","moderator")) || $current_id == $user_id) {
                  echo "<h1 style=color:red>Dieser Artikel wurde gesperrt.</h1>";
                }
                else {
                  echo "<h1>Dieser Artikel wurde gesperrt.</h1>";
                  die();
                }
              }
              $file = $post["file"];
              $viewedby = $post["viewedby"];
              $likecount = count($post["likedby"]);
              $dislikecount = count($post["dislikedby"]);
              $liked = false;
              $disliked = false;
              if (isset($current_id)) {
                foreach ($post["likedby"] as $user) {
                  if ($user == $current_id) {
                    $liked = true;
                  }
                }
                foreach ($post["dislikedby"] as $user) {
                  if ($user == $current_id) {
                    $disliked = true;
                  }
                }
                if(!in_array($current_id, $viewedby)) {
                  array_push($viewedby, $current_id);
                  $post["viewedby"] = $viewedby;
                  $user["posts"][$post_id-1] = $post;
                  $users[$user_id-1] = $user;
                  $users = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                  file_put_contents("users.json", $users);
                }
            }
            }
          }
        }
      }
      $content = file_get_contents("uploads/$file");
      echo $content;
    ?>
    </md-block>
    <section>
      <?php echo "
      <a href='profile.php?id=$user_id'><fieldset class='von'>
      <legend>Erstellt von</legend>
      <div class='author'>
      <img src='images/$image' alt='Profilbild von $author'>
      <h1>$author</h1>
      </div></fieldset></a>";
      if (isset($current_id)) {
      if ($current_id == $user_id || in_array($current_role,array("admin","moderator")))
        echo "<a class='auto' href='edit.php?id=$user_id-$post_id'><img class='icon' src='icons/edit.png'></a>";
      if ($current_role == "admin" || $current_role == "moderator" && $user_id != $current_id)
        echo "<a class='auto ml1' href='ban.php?id=$user_id-$post_id'><img class='icon' src='icons/ban.png'></a>";
      }
      echo "<div class='flex big'>";
      echo "<p><a href='like-dislike.php?id=$user_id-$post_id&like=like'><img class='icon'";
      if ($liked) echo "src='icons/like-fill.png'";
      else echo "src='icons/like.png'";
      echo "></a>$likecount";

      echo "<p><a href='like-dislike.php?id=$user_id-$post_id&like=dislike'><img class='icon'";
      if ($disliked) echo "src='icons/dislike-fill.png'";
      else echo "src='icons/dislike.png'";
      echo "></a>$dislikecount";
      echo "</div>";
      ?>
      <h1>Andere Artikel</h1>
      <?php
      // get 5 random articles
      $posts = array();
      foreach ($users as $user) {
        if ($user["banned"] == true) {
          continue;
        }
        foreach ($user["posts"] as $post) {
          if ($post["banned"] == true) {
            continue;
          }
          $post["username"] = $user["username"];
          $post["user_id"] = $user["id"];
          $post["image"] = $user["image"];
          array_push($posts, $post);
        }
      }
      for ($i = 0; $i < 5; $i++) {
        shuffle($posts);
        $post = $posts[0];
        outputArticle($post, "white");
      }
      ?>
    </section>
  </main>
  </body>
</html>
