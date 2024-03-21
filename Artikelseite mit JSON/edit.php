<html>
  <head>
    <title>Artikel</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
  <script type='module' src='https://md-block.verou.me/md-block.js'></script>
    <script src="functions.js"></script>
  </head>
  <body>
    <?php
    include 'user.class.php';
    include 'functions.php';
    session_start();
    $url = $_GET["id"];
    $url = explode("-", $url);
    $user_id = $url[0];
    $post_id = $url[1];
    $role = "";
    $current_id = $_SESSION["user"];
    $current_user = get_user($current_id);
    $current_role = $current_user[5];
    $article = file_get_contents("uploads/$user_id-$post_id.md");
    $json = file_get_contents("users.json");
    $users = json_decode($json, true);
    foreach ($users as $user)
      if ($user['id'] == $user_id)
        foreach ($user['posts'] as $post)
          if ($post['id'] == $post_id) {
            $title = $post['title'];
            $content = $post['content'];
          }
    ?>
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
    <main class="article-main m0">
      <?php
      if ($current_id == $user_id || in_array($current_role, array("admin", "moderator"))) {
        ?>
        <form action="" method="post" class="form-popup relative panel w100">
          <label for="title">Titel</label>
          <input type="text" name="title" placeholder="Titel"<?php echo "value='$title'"?>>
          <label for="description">Beschreibung</label>
          <textarea name="description" class='desc-height left maxw0'placeholder="Beschreibung"><?php echo $content;?></textarea>
          <label for="content">Inhalt</label>
          <textarea name="content"class='left maxw0 textarea-long'placeholder="Inhalt"><?php echo $article;?></textarea>
          <input type="submit" value="Speichern">
        </form>
        <?php
        if (isset($_POST["title"]) && isset($_POST["content"])) {
          $description = $_POST["description"];
          $content = $_POST["content"];
          $title = $_POST["title"];
          $UserC = new User("users.json");
          $UserC->updatePost($post_id, $user_id, "title", $title);
          $UserC->updatePost($post_id, $user_id, "content", $description);
          file_put_contents("uploads/$user_id-$post_id.md", $content);
          header("Location: artikelseite.php?id=$user_id-$post_id");
        }
      } else {
        header("Location: artikelseite.php?id=$user_id-$post_id");
      }
      ?>
  </main>
  </body>
</html>
