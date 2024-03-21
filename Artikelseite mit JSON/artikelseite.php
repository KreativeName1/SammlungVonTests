<html>
  <head>
    <title>Artikel</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <script src="functions.js"></script>
    <?php
    include 'functions.php';
    session_start();
    $users = file_get_contents("users.json");
    $users = json_decode($users, true);
    if (isset($_SESSION["user"])) {
      $current_id = $_SESSION["user"];
      $current_user = get_user($current_id);
      $current_role = $current_user[5];
    }
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
        <form action="" class="artikel-nav">
          <fieldset>
            <legend>Sortieren nach</legend>
          <input type="submit" name="newest" value="Neueste">
          <input type="submit" name="following" value="Folge ich">
          <input type="submit" name="random" value="Zufällig">
          </fieldset>
          <fieldset>
            <legend>Top</legend>
          <select type="submit" name="top">
            <option value="day"<?php if (isset($_GET['top-submit'])) if ($_GET["top"] == "day") echo "selected" ?>>Heute</option>
            <option value="week"<?php if (isset($_GET['top-submit'])) if ($_GET["top"] == "week") echo "selected" ?>>Woche</option>
            <option value="month"<?php if (isset($_GET['top-submit'])) if ($_GET["top"] == "month") echo "selected" ?>>Monat</option>
            <option value="year"<?php if (isset($_GET['top-submit'])) if ($_GET["top"] == "year") echo "selected" ?>>Jahr</option>
            <option value="alltime"<?php if (isset($_GET['top-submit'])) if ($_GET["top"] == "alltime") echo "selected" ?>>Aller Zeiten</option>
          </select>
          <input type="submit" value="✔" name="top-submit">
          </fieldset>
        </form>
        <?php
          $posts = array();
          foreach ($users as $user) {
            if ($user["banned"] == false)
            foreach ($user["posts"] as $post) {
              if ($post["banned"] == true) continue;
              $post["user_id"] = $user["id"];
              $post["username"] = $user["username"];
              $post["image"] = $user["image"];
              array_push($posts, $post);
            }
          }
        if (isset($_GET['newest'])) {
          usort($posts, function($a, $b) {
            return $b["created_at"] <=> $a["created_at"];
          });
          foreach ($posts as $post) {
            outputArticle($post, "");
          }
        }
        if (isset($_GET['following'])) {
          if (!isset($current_id)) {
            echo "<h1 class='center'>Du musst angemeldet sein, um diese Funktion zu nutzen.</h1>";
            die();
          }
          $following = array();
          foreach ($users as $user) {
            if ($user["id"] == $current_id) {
              $following = $user["following"];
              if (count($following) == 0) {
                echo "<h1 class='center'>Du folgst noch niemandem.</h1>";
              }
              foreach ($following as $follow) {
                foreach ($posts as $post) {
                  if ($post["user_id"] == $follow) {
                    outputArticle($post, "");
                  }
                }
              }
            }
          }
        }
        if (isset($_GET['top-submit'])) {
          $space = $_GET["top"];
          if ($space == "day") $last_days = 0;
          if ($space == "week") $last_days = 7;
          if ($space == "month") $last_days = 30;
          if ($space == "year") $last_days = 365;
          if ($space == "alltime") $last_days = 100000;
          $postsweek = $posts;
          foreach ($postsweek as $key => $post) {
            $date = $post["created_at"];
            $date = strtotime($date);
            $date = date("Y-m-d", $date);
            $date = strtotime($date);
            $now = strtotime(date("Y-m-d"));
            $diff = $now - $date;
            $diff = floor($diff / (60 * 60 * 24));
            if ($diff > $last_days) {
              unset($postsweek[$key]);
            }
          }
          usort($postsweek, function($a, $b) {
            $b = $b["viewedby"];
            $b = count($b);
            $a = $a["viewedby"];
            $a = count($a);
            return $b <=> $a;
          });
          foreach ($postsweek as $post) {
            outputArticle($post, "");
          }
        }
        if (isset($_GET['random'])) {
          shuffle($posts);
          foreach ($posts as $post) {
            outputArticle($post, "");
          }
        }

        ?>
    </main>
  </body>
</html>
