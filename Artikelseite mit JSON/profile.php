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
    $user_id = $_GET["id"];
    if (isset($_SESSION["user"])){
      $current_id = $_SESSION["user"];
      $current_user = get_user($current_id);
      $current_role = $current_user[5];
      $banned = return_jsonvalue("users.json", "banned", $user_id);
      $banner = return_jsonvalue("users.json", "banner", $user_id);
      $image = return_jsonvalue("users.json", "image", $user_id);
      $Users = file_get_contents("users.json");
      $Users = json_decode($Users, true);
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
    <main class="article-page m0">
        <?php
        echo "<img class='banner' src='images/$banner'>
        <div class='grid-2c'>
        <img class='profile-img' src='images/$image'>";
        ?>
        <div class="profile-info">
          <?php
          $follower = array();
          $followedby = array();
          foreach ($Users as $user) {
            foreach ($user["following"] as $following) {
              if ($following == $user_id) {
                array_push($follower, $user_id);
                array_push($followedby, $user);
              }
            }
          }
          $followerCount = count($follower);
          $followed = return_jsonvalue("users.json", "following", $user_id);
          $followedbyCount = count($followed);

          if (isset($current_id)) {
            $followedfromCurrentUser = return_jsonvalue("users.json", "following", $current_id);
            foreach($followedfromCurrentUser as $user) {
              if ($user == $user_id) {
                $followedfromCurrentUser = true;
                break;
              }
              else $followedfromCurrentUser = false;
            }
          }
          $user = get_user($user_id);
          $name = $user[0];
          $bio = $user[1];
          $posts = $user[4];
          $role = check_role($user[5], $user_id);
          $display_role = $role[1];
          $role = $role[0];
          $created_at = (str_replace("-", ".", explode(" ", $user[2])[0]));
            echo "<span class='user'>$name<sup class='role $role'>$display_role</sup></span>";
            if (isset($current_id)) {
              if ($current_id == $user_id)
                echo "<button class='big-icon' onclick='" . 'openForm("form-popup")' ."'><img class='icon' src='icons/edit.png'></button>";
              else {
                if (in_array($current_role, array("moderator","admin")))
                echo "<a href='panel.php?id=$user_id' class='big-icon'><img class='icon' src='icons/settings.png'></a>";
                if ($followedfromCurrentUser) echo "<a href='follow.php?id=$user_id' class='big-icon'><img class='icon'src='icons/added.png'></a>";
                else echo "<a href='follow.php?id=$user_id' class='big-icon'><img class='icon'src='icons/add.png'></a>";
              }
            }
            echo "<br><button onclick='" . 'openForm("followerPopup")' ."'>Folgt $followedbyCount</button>";
            echo "<button onclick='" . 'openForm("followedbyPopup")' ."'>$followerCount Follower</button>";
            if ($banned) echo "<h1 class='center'>Dieser Benutzer wurde gesperrt.</h1>";
            echo "<md-block>$bio<p class='date right'>Am $created_at beigetreten</p></md-block>";
          ?>
        </div>
      </div>
      <div class='profile-article-parent'>
          <?php
          for ($i = 0; $i < count($posts); $i++) {
            $post = $posts[$i];
            $post["username"] = $name;
            $post["user_id"] = $user_id;
            $post["image"] = $image;
            outputArticle($post, "");
          }
          ?>
        </div>
    </main>
    <div class="followPopup center popup-lower" id="form-popup">
      <form action="" method="post" class="form-container" enctype='multipart/form-data'>
        <h1>Profil bearbeiten</h1>
        <label for="name"><b>Name</b></label><br>
        <input type="text" placeholder="Name" name="name" required value="<?php if ($user_id == $current_id) echo $name; ?>"><br>
        <label for="profilePicture">Profilbild</label><br>
        <input type="file" name="profilePicture"><br>
        <label for="banner">Banner</label><br>
        <input type="file" name="banner"><br>
        <label for="bio"><b>Biografie</b></label><br>
        <textarea name="bio" placeholder="Bio" required><?php if ($user_id == $current_id) echo $bio; ?></textarea><br>
        <button type="submit" name='change_profile'>Speichern</button>
        <button type="button"onclick="closeForm('form-popup')">Abbrechen</button>
      </form>
      <?php
        if (isset($_POST['change_profile']) && $current_id == $user_id) {
          $userC = new User("users.json");
          $name = $_POST['name'];
          $bio = $_POST['bio'];
          $image = $_FILES['profilePicture']['name'];
          $banner = $_FILES['banner']['name'];
          $json = file_get_contents("users.json");
          $users = json_decode($json, true);
          foreach ($users as $user) {
            if ($user['id'] == $user_id) {
              $userC ->updateUser($user_id, 'username', $name);
              $userC ->updateUser($user_id, 'bio', $bio);
              if ($image != "") {
                move_uploaded_file($_FILES['profilePicture']['tmp_name'], "images/$image");
                $userC ->updateUser($user_id, "image", $image);
              }
              if ($banner != "") {
                move_uploaded_file($_FILES['banner']['tmp_name'], "images/$banner");
                $userC ->updateUser($user_id, "banner", $banner);
              }
            }
          }
        }
      ?>
    </div>
    <form action=""id='followerPopup'class="followPopup pos-fixed">
      <button type="button" class="center" onclick="closeForm('followerPopup')">Schließen</button>
      <?php
        if (count($followed) == 0) echo "<h1 class='center'>Dieser Benutzer hat noch niemanden gefolgt.</h1>";?>
      <div class="follower-grid">
      <?php
        for ($i = 0; $i < count($followed); $i++) {
          $user = get_user($followed[$i]);
          $name = $user[0];
          $image = $user[7];
          $role = $user[5];
          $follow_id = $user[3];
          $role = check_role($role, $follow_id);
          $display_role = $role[1];
          $role = $role[0];
          echo "<a href='profile.php?id=$follow_id'><img class='round img100' src='images/$image'><span class='user'>$name</span><sup class='role $display_role'>$role</sup></a>";
        };
      ?>
      </div>
    </form>
    <form action=""id='followedbyPopup' class="followPopup pos-fixed">
      <button type="button" class="center" onclick="closeForm('followedbyPopup')">Schließen</button>
      <?php if ($followerCount == 0) echo "<h1 class='center'>Dieser Benutzer hat noch keine Follower.</h1>.</h1>";
      ?>
      <div class="follower-grid">
      <?php
      foreach ($followedby as $user) {
        $name = $user["username"];
        $image = $user["image"];
        $role = $user["role"];
        $follow_id = $user["id"];
        $role = check_role($role, $follow_id);
        $display_role = $role[1];
        $role = $role[0];
        echo "<a href='profile.php?id=$follow_id'><img class='round img100' src='images/$image'><span class='user'>$name</span><sup class='role $display_role'>$role</sup></a>";
      }
      ?>
      </div>
    </form>
  </body>
</html>