<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"] == "" || $_SESSION["user"] == null || $_SESSION["logged_in"] != true)
header("Location: login.php");
else {
$ids = $_GET["id"];
$ids = explode("-", $ids);
$self_id = $_SESSION["user"];
$user_id = $ids[0];
$post_id = $ids[1];
$like = $_GET["like"];
$removedlike = false;
$removeddislike = false;
$json = file_get_contents("users.json");
$json = json_decode($json, true);
$post = [];
foreach ($json as $user) if ($user["id"] == $user_id) foreach ($user["posts"] as $p) if ($p["id"] == $post_id) $post = $p;
$likes = $post["likedby"];
$dislikes = $post["dislikedby"];
foreach ($json as $user) if ($user["id"] == $self_id) if ($user["banned"] == true) {
      echo "<head><link rel='stylesheet' type='text/css' href='style.css' /></head>";
      echo "<h1 class='center'>Dieser Benutzer ist gesperrt!</h1>";
      die();
    }
// if the user id is in the likes array, remove the value from the array
if (in_array($self_id, $likes)) {
  $key = array_search("$self_id", $likes);
  print $self_id;
  unset($likes[$key]);
  $removedlike = true;
}
// if the user id is in the dislikes array, remove it
if (in_array($self_id, $dislikes)) {
  $key = array_search($self_id, $dislikes);
  unset($dislikes[$key]);
  $removeddislike = true;
}
// if the user id is not in the likes array and the user clicked like, add it
if (!in_array($self_id, $likes) && $like == "like" && !$removedlike) {
  array_push($likes, $self_id);
}
// if the user id is not in the dislikes array and the user clicked dislike, add it
if (!in_array($self_id, $dislikes) && $like == "dislike" && !$removeddislike) {
  array_push($dislikes, $self_id);
}
// if the user already liked the post and clicked dislike, remove the like and add the dislike
if (in_array($self_id, $likes) && $like == "dislike") {
  $key = array_search($self_id, $likes);
  unset($likes[$key]);
  array_push($dislikes, $self_id);
}
// if the user already disliked the post and clicked like, remove the dislike and add the like
if (in_array($self_id, $dislikes) && $like == "like") {
  $key = array_search($self_id, $dislikes);
  unset($dislikes[$key]);
  array_push($likes, $self_id);
}
// update the post's likes
foreach ($json as $key => $user) if ($user["id"] == $user_id) foreach ($user["posts"] as $k => $p) if ($p["id"] == $post_id) {
        $json[$key]["posts"][$k]["likedby"] = $likes;
        $json[$key]["posts"][$k]["dislikedby"] = $dislikes;
      }
// write the new json to the file
$json = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
file_put_contents("users.json", $json, LOCK_EX);
header("Location: article.php?id=$user_id-$post_id");
}