<html>
  <head>
    <title>Artikel</title>
    <link rel="stylesheet" type="text/css" href="../style.css" />
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
  <script type='module' src='https://md-block.verou.me/md-block.js'></script>
    <script src="../functions.js"></script>
    <?php
      chdir("..");
      include 'functions.php';
      session_start();
      $url = $_GET["id"];
      $url = explode("-", $url);
      $id = $url[0];
      $post_id = $url[1];
      ?>
  </head>
  <body>
    <div id="profilePopup">
      <a href="../publish.php">Erstellen</a>
      <?php $user = $_SESSION["user"]; echo "<a href='$user.php'>Profil</a>"; ?>
      <a href="../settings.php">Einstellungen</a>
      <a href="../logout.php">Abmelden</a>
    </div>
    <header>
      <h1>Webseite</h1>
      <nav>
        <?php navbar(".."); ?>
      </nav>
    </header>
    <main class='article'>
    <md-block>
    <?php
      // $post_id = $post_id-1;
      $users = file_get_contents("users.json");
      $users = json_decode($users, true);
      foreach ($users as $user) {
        if ($user["id"] == $id) {
          $author = $user["username"];
          $image = $user["image"];
          foreach ($user["posts"] as $post) {
            if ($post["id"] == $post_id) {
              $file = $post["file"];
            }
          }
        }
      }
      $content = file_get_contents("uploads/$file");
      echo $content;
    ?>
    </md-block>
    <section>
    <a <?php echo "href='../profile/$id.php'";?>><fieldset class="von"><legend>Erstellt von</legend>
      <?php echo "<div class='author'><img src='../images/$image' alt='Profilbild von $author'><h1>$author</h1></div>"; ?>
      </fieldset></a>
      <h1>Neueste Artikel</h1>
      <?php
      include('../load-newest.php');
      ?>
    </section>
  </main>
  </body>
</html>
