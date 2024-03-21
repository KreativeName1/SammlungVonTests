<html>
  <head>
  <?php
  session_start();
  require 'login.class.php';
  if (isset($_SESSION['user'])) {
    header("Location: account.php"); exit();
  }
  if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = new LoginUser($email, $password);
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
      <h2>Login</h2>
      <form action="" method="post" class="flex-col wide">
        <label for="email">E-Mail</label>
        <input type="email" name="email" id="email" required>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <input type="submit" name="submit" value="Login">
        <p class="error center"><?php echo @$user->error ?></p>
        <p class="success center"><?php echo @$user->success ?></p>
      </form>
    </main>
  </body>
</html>