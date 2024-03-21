<header>
<div class="flex">
  <img src="images/todo.png" class="img-9" alt="Logo der Todo App">
  <h1>Todo App</h1>
</div>
<nav>
  <ul>
    <li><a href="index.php?page=home"  <?php  if ($page == 'home') echo "class='selected'";?>>Home</a></li>
    <li><a href="index.php?page=lists" <?php  if ($page == 'lists') echo "class='selected'";?>>Listen</a></li>
    <li><a href="index.php?page=shared"<?php  if ($page == 'shared') echo "class='selected'";?>>Geteilt</a></li>
  </ul>
</nav>
<a href="index.php?page=settings" <?php  if ($page == 'settings') echo "class='selected'";?>>
  <img src="images/settings.png"class="img-4" alt="Einstellungen">
</a>
</header>