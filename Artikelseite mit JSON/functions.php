<?php
function navbar($path) {
  $users = file_get_contents("users.json");
  $users = json_decode($users, true);
  foreach ($users as $user) {
    if (isset($_SESSION['user'])) {
    if ($user['id'] == $_SESSION['user']) {
      $image = $user['image'];
    }
  }
  }
  if ($path == "") {
    echo "<a href='index.php'>Home</a>";
    echo "<a href='artikelseite.php'>Artikel</a>";
  }
  else {
    echo "<a href='$path/index.php'>Home</a>";
    echo "<a href='$path/artikelseite.php'>Artikel</a>";
  }
  // Wenn der User eingeloggt ist
  if (isset($_SESSION['logged_in'])) {
    if ($_SESSION['logged_in'] == true) {
    $user = $_SESSION['user'];
    if ($path == "") {
      echo "<button class='profile-picture' onclick='openProfile()'><img src='images/$image' alt='Profilbild'></button>";
    }
    else {
      echo "<button class='profile-picture' onclick='openProfile()'><img src='$path/images/$image' alt='Profilbild'></button>";
    }
  }
  }
  else {
      echo "<a href='login.php'>Login</a>";
  }
}

function return_jsonvalue($json, $key, $id) {
  $json = file_get_contents($json);
  $json = json_decode($json, true);
  foreach ($json as $user) {
    if ($user['id'] ==$id) {
      $value = $user[$key];
    }
  }
  return $value;
}


function get_user($id) {
  $json = file_get_contents("users.json");
  $json = json_decode($json, true);
  foreach ($json as $user) {
    if ($user['id'] == $id) {
      $name = $user['username'];
      $bio = $user['bio'];
      $created_at = $user['created_at'];
      $id = $user['id'];
      $posts = $user['posts'];
      $role = $user['role'];
      $banned = $user['banned'];
      $image = $user['image'];
    }
  }
  return array($name, $bio, $created_at , $id, $posts, $role, $banned, $image);
}
function outputArticle($post, $class) {
  $likedby = $post["likedby"];
  $likecount = count($likedby);
  $dislikedby = $post["dislikedby"];
  $dislikecount = count($dislikedby);
  $viewcount = count($post["viewedby"]);
  $post["created_at"] = date("d.m.Y", strtotime($post["created_at"]));
  echo "<article class='$class'><a href='article.php?id=" . $post["user_id"] . "-" . $post["id"] . "' class='article-link'>";
  echo "<h2>" . $post["title"] . "</h2>";
  echo "<p>" . $post["content"] . "</p>";
  echo "";
  echo "<div class='article-bottom'><a href='profile.php?id=" . $post["user_id"] ."'><div class='flex'><img class='profile-picture'src='images/" . $post["image"] . "' /><span class='ml1'>" . $post["username"] . " • " . $post["created_at"] . "</span></div></a><div class='views'>$likecount Likes • $dislikecount Dislikes • " . $viewcount ." Aufrufe</div></div>";
  echo "</article>";
}
function check_role($role, $id) {
  $json = file_get_contents("users.json");
  $json = json_decode($json, true);
  foreach ($json as $user) {
    if ($user['id'] == $id) {
      $role = $user['role'];
      if ($role == "admin") {$display_role = "admin"; $role = "Admin";}
      else if ($role == "author") {$display_role = "author2"; $role = "Autor";}
      else if ($role == "user") {$display_role = "user2"; $role = "";}
      else if ($role == "moderator") {$display_role = "moderator"; $role = "Moderator";}
      return array($role, $display_role);
    }
  }
}
?>