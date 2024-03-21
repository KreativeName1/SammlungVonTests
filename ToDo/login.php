<html>
    <?php
      require_once 'includes/head.php';
      require_once 'classes/login.class.php';
      if (isset($_POST['submit'])) {
        $login = new Login(
          $_POST['email'],
          $_POST['passwort']
        );
      }
    ?>
  <body class="vertical vertical_center ">
    <div class="flex formpage-topmar" >
      <img src="images/todo.png" class="img-15" alt="Logo der Todo App">
      <h1>Todo App</h1>
    </div>
    <form action="" method="post" class="vertical form formpage-topmar">
      <label for="email">Email</label>
      <input type="email" name="email">
      <label for="passwort">Passwort</label>
      <input type="password" name="passwort">
      <input type="submit" name="submit" value="Login">
      <p> Noch kein Account? Registriere dich <a href="register.php">hier</a></p>
      <p class="error"><?php echo @$login->error;?></p>
      <p class="success"><?php echo @$login->success;?></p>
    </form>
  </body>
</html>