<html>
  <head>
    <title>Localhost</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/standard.css">
    <link rel="icon" type="image/x-icon" href="images/xampp.png">
    <link rel="shortcut icon" href="images/xampp.png">
    <?php  $path = $_GET['path'];?>
  </head>
  <form action="index">
  <input type="search" name="search" value="<?php if (isset($_GET['search']))echo$_GET['search'];?>" placeholder="Hier Suchen...">
  <input type="hidden" name="path" value="<?php echo $_GET['path']; ?>">
  <select name="filter">
    <option value="all" <?php if(!isset($_GET['filter'])||$_GET['filter']=='all')echo"selected";?>>Alles</option>
    <option value="folders"<?php if(isset($_GET['filter']))if($_GET['filter']=='folders')echo"selected";?>>Ordner</option>
    <option value="files"<?php if(isset($_GET['filter']))if($_GET['filter']=='files')echo"selected";?>>Dateien</option>
  </select>
  </form>
  <p class='path'><?php echo $path?></p>
  <section class="list box">
<?php
if (!isset($_GET['search']) || empty($_GET['search'])) foreach (glob("$path*") as $ff) {
  $name = explode("/", "$ff");
  $name = end($name);
  if (is_file(($ff))) {
    echo "<a class='flex' href='../$ff'><img src='images/file.png'>$name</a>";
  }
  else echo "<a href='?path={$ff}/' class='flex'><img src='images/folder.png'>$name</a>";
}
else {
  // Sehr ineffiziente Suche, aber es funktioniert
  $dirs = readDirs("../");
  foreach ($dirs as $dir) {
    $name = explode("/", "$dir");
    $name = end($name);
    $search = trim($_GET['search']);
    $name_small = strtolower($name);
    $search = strtolower($search);
    if (strpos($name_small, $search) !== false) {
      if (is_file(($dir)) && $_GET['filter'] == 'all' || $_GET['filter'] == 'files' && is_file(($dir))) echo "<a class='flex' href='../$dir'><img src='images/file.png'>$name</a>";
      else if ($_GET['filter'] == 'all' || $_GET['filter'] == 'folders') echo "<a href='?path={$dir}/' class='flex'><img src='images/folder.png'>$name</a>";
    }
  }
}
function readDirs($main){
  $dirs = array();
  foreach (glob("$main*") as $ff) {
    if (file_exists("$ff/.ignore")) continue;
    if (is_dir($ff)) {
      $dirs[] = $ff;
      $dirs = array_merge($dirs, readDirs("$ff/"));
    }
    else $dirs[] = $ff;
  }
  return $dirs;
}
?>
</section>
</html>