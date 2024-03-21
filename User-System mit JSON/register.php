<html>
  <head>
  <?php
        require 'user.class.php';
        if (isset($_POST['submit'])) {
          $password = $_POST['password'];
          $password2 = $_POST['password2'];
          if ($password == $password2) {
            $user = new RegisterUser($_POST['email'], $_POST['username'], $password,);
          } else {
            echo "Passwords do not match!";
          }
        }
      ?>
  <title>Register</title>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <link rel="stylesheet" type="text/css" href="style.css">
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
    <main class="center-div">
      <h2>Register</h2>
      <form action="register.php" method="post" class="flex-col wide">
        <label for="email">E-Mail</label>
        <input type="email" name="email" id="email" required>
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <label for="password2">Repeat password</label>
        <input type="password" name="password2" id="password2" required>
        <input type="submit" name="submit" value="Register">
        <p class="error center"><?php echo @$user->error ?></p>
        <p class="success center"><?php echo @$user->success ?></p>
      </form>
    </main>
  </body>
</html>