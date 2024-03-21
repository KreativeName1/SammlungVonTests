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
    $user = $_SESSION["user"] ?? "";
    ?>
  </head>
  <body>
  <div id="profilePopup">
  <?php $aUser=get_user($user);if($aUser[5] != "user") echo "<a href='publish.php'>Erstellen</a>"; echo "<a href='profile.php?id=$user'>Profil</a>"; ?>
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
    <h1>Registrieren</h1>
        <form action='' method='post' class='form-popup panel' enctype='multipart/form-data'>
          <label for="username">Name</label>
          <input type='text' name='username' placeholder='Name'>
          <label for="email">E-Mail</label>
          <input type='email' name='email' placeholder='email@domain.com'>
          <label for="passwort">Passwort</label>
          <input type='password' name='passwort' placeholder='12345'>
          <label for="passwort2">Passwort wiederholen</label>
          <input type='password' name='passwort2' placeholder='12345'>
          <label for="bio"></label>
          <textarea name="bio" id="bio" cols="30" rows="10" placeholder="Biografie"></textarea>
          <label for="file">Profilbild</label>
          <input type='file' name='file' id='file'>
          <label for="banner">Banner</label>
          <input type='file' name='banner' id='file'>
          <input type='submit' name="register" value="Registrieren">
          </form>
  <?php
  if (isset($_POST['register'])) {
      $username = $_POST['username'];
      $email = $_POST['email'];
      $bio = $_POST['bio'];
      $password = $_POST['passwort'];
      $password2 = $_POST['passwort2'];
      $password = sha1($password);
      $password2 = sha1($password2);
      $image = $_FILES['file']['name'];
      $image_tmp = $_FILES['file']['tmp_name'];
      $banner = $_FILES['file']['name'];
      $banner_tmp = $_FILES['file']['tmp_name'];
      move_uploaded_file($image_tmp, "images/$image");
      move_uploaded_file($banner_tmp, "images/$banner");
          $new_user = [
              'id' => 1,
              'username' => $username,
              'email' => $email,
              'password' => $password,
              'role' => "author",
              'image' => $image,
              'banner' => $banner,
              'banned' => false,
              'created_at' => date('d-m-Y H:i:s'),
              'bio' => $bio,
              'posts' => [],
              'following' => [],
          ];
          $user = new User("users.json");
          $user->insertNewUser($new_user);
          Header("Location: login.php");
  }
  ?>
  </main>
</html>