<html>
  <?php require_once 'includes/head.php'; ?>
  <body>
      <?php require_once 'includes/header.php'; ?>
    <main>
        <?php
        switch ($page) {
          case 'home':
            require_once 'pages/home.php';
            break;
          case 'lists':
            require_once 'pages/lists.php';
            break;
          case 'shared':
            require_once 'pages/shared.php';
            break;
          case 'settings':
            require_once 'pages/settings.php';
            break;
          default:
            require_once 'pages/home.php';
            break;
        }
        ?>
    </main>
  </body>
</html>