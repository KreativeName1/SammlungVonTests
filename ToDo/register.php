<html>
    <?php
      require_once 'includes/head.php';
      require_once 'classes/registration.class.php';
      if (isset($_POST['submit'])) {
        $registration = new Registration(
          $_POST['email'],
          $_POST['password'],
          $_POST['password2'],
          $_POST['first_name'],
          $_POST['last_name'],
          $_POST['username'],
        );
      }
    ?>
  <body class="vertical vertical_center ">
  <div class="flex formpage-topmar" >
      <img src="images/todo.png" class="img-15" alt="Logo der Todo App">
      <h1>Todo App</h1>
    </div>
    <form action="" method="post" class="vertical form formpage-topmar">
      <div class="register-grid">
        <div>
        <label for="email">E-Mail</label><br>
        <input type="email" name="email">
        </div>
        <div>
          <label for="username">Benutzername</label><br>
          <input type="text" name="username">
        </div>
        <div>
        <label for="first_name">Vorname</label><br>
        <input type="text" name="first_name">
        </div>
        <div>
        <label for="last_name">Nachname</label><br>
        <input type="text" name="last_name">
        </div>
        <div>
        <label for="password">Passwort</label><br>
        <input type="password" name="password">
        </div>
        <div>
        <label for="password2">Passwort wiederholen</label><br>
        <input type="password" name="password2">
        </div>
      </div>
      <input type="submit" name="submit" value="Registrieren">
      <p class="error"><?php echo @$registration->error;?></p>
      <p class="success"><?php echo @$registration->success;?></p>
    </form>
  </body>
</html>