<html>
  <head>
  <title>Register</title>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="functions.js"></script>
  </head>
  <body>
    <header>
      <h1>Webseite</h1>
      <nav>
          <a href="index.php">Home</a>
          <a href="register.php">Register</a>
          <a href="login.php">Login</a>
      </nav>
    </header>
  <?php
  require 'functions.php';
  require 'update.class.php';
  session_start();
  $current_id = $_SESSION['user'];
  $current_user = get_user($current_id);
  if (isset($_SESSION['user'])) {
    if (isset($_GET['logout'])) {
      session_destroy();
      header("Location: login.php");exit();
    }
    if (isset($_GET['update'])) {
      echo "test";
      $username = $_GET['username'];
      $update_user = new UpdateUser($current_id, $username);
    }
  } else {
    header("Location: login.php"); exit();
  }
  ?>
    <main class="center-div">
      <div class="panel">
        <div class="panel-header">
        <h3 class="center">Welcome <?php echo $current_user['username'] ?></h3>
        <div class="flex center">
          <button><a class="center white"href="?logout">Log out</a></button>
          <button class="center" onclick="openModal('popup')">Settings</button>
        </div>
        </div>
        <div class="panel-content">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea voluptate odio perferendis consequatur delectus dolorum iste quas, quae placeat saepe corporis eum aut deleniti sequi porro atque aliquid autem eligendi.</p>
        </div>
        <div class="panel-footer">
        </div>
      </div>
      <div class="panel popop" id='popup'>
        <form action="" class="center reset flex-col">
          <h2>Settings</h2>
          <label for="username">Username</label>
          <input type="text" name="username" value="<?php echo $current_user['username'] ?>">
          <input type="submit" name="update" value="Update">
        </form>
        <p class="error center"><?php echo @$update_user->error ?></p>
        <p class="success center"><?php echo @$update_user->success ?></p>
        <button id="close" onclick="closeModal('popup')">X</button>
      </div>
    </main>
  </body>
</html>