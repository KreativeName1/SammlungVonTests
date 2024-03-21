<html>
  <head>
    <title>Artikel</title>
    <link rel="stylesheet" type="text/css" href="../style.css" />
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
  <script type='module' src='https://md-block.verou.me/md-block.js'></script>
    <script src="../functions.js"></script>
    <?php chdir("..");include 'functions.php'; session_start();?>
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
    <main class="article-page m0">
      <img class="banner" src="../images/Banner.jpg">
      <div class="grid-2c">
        <?php
        $url = $_SERVER['REQUEST_URI'];
        $url = explode("/", $url);
        $url = end($url);
        $url = explode(".", $url);
        $image = return_jsonvalue("users.json", "image", $url[0]);
          echo "<img class='profile-img' src='../images/$image'>";
        ?>
        <div class="profile-info">
          <?php
          $user = get_user($url[0]);
          $name = $user[0];
          $bio = $user[1];
          $created_at = $user[2];
          $id = $user[3];
          $posts = $user[4];
          $created_at = explode(" ", $created_at);
          $created_at = str_replace("-", ".", $created_at[0]);
            echo "<span class='user'>$name</span>";
            if ($id == $_SESSION['user']) {
              echo "<button onclick='openForm()'> Bearbeiten</button>";
            }
            echo "<md-block>$bio<p class='date right'>Am $created_at beigetreten</p></md-block>";
            echo "";
          ?>
        </div>
      </div>
      <div class='profile-article-parent'>
          <?php
          for ($i = 0; $i < count($posts); $i++) {
            $title = $posts[$i]['title'];
            $content = $posts[$i]['content'];
            $created_at = $posts[$i]['created_at'];
            $created_at = explode(" ", $created_at);
            $created_at = str_replace("-", ".", $created_at[0]);
            echo "
              <article>
                <h2>$title</h2>
                <p>$content</p>
                <span class='date'>$created_at</span>
              </article>
            ";
          }
          ?>
        </div>
    </main>
    <div class="form-popup">
      <form action="" method="post" class="form-container" enctype='multipart/form-data'>
        <h1>Profil bearbeiten</h1>
        <label for="name"><b>Name</b></label><br>
        <input type="text" placeholder="Name" name="name" required value="<?php if ($id == $_SESSION['user']) echo $name; ?>"><br>
        <label for="profilePicture"></label>
        <input type="file" name="profilePicture" id="profilePicture"><br>
        <label for="bio"><b>Bio</b></label><br>
        <textarea name="bio" placeholder="Bio" required><?php if ($id == $_SESSION['user']) echo $bio; ?></textarea><br>
        <button type="submit" name='change_profile'class="btn">Speichern</button>
        <button type="button" class="btn cancel" onclick="closeForm()">Abbrechen</button>
      </form>
      <?php
        if (isset($_POST['change_profile']) && $_SESSION['user'] == $id) {
          $name = $_POST['name'];
          $bio = $_POST['bio'];
          $image = $_FILES['profilePicture']['name'];
          $json = file_get_contents("users.json");
          $users = json_decode($json, true);
          foreach ($users as $user) {
            if ($user['id'] == $id) {
              $users[$key]['username'] = $name;
              $users[$key]['bio'] = $bio;
              if ($image != "") {
                move_uploaded_file($_FILES['profilePicture']['tmp_name'], "images/$image");
                $users[$key]['image'] = $image;
              }
            }
          }
          $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
          file_put_contents("users.json", $json);
        }
      ?>
    </div>
  </body>
</html>
