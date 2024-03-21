<?php
session_start();
$current_id = $_SESSION['user'];
$id = $_GET['id'];
$users = file_get_contents("users.json");
$users = json_decode($users, true);
$following = [];
foreach ($users as $user) if ($user["id"] == $current_id) $following = $user["following"];
if (in_array($id, $following)) {
  $key = array_search($id, $following);
  unset($following[$key]);
} else array_push($following, $id);
foreach ($users as $key => $user) if ($user["id"] == $current_id) $users[$key]["following"] = $following;
$users = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
file_put_contents("users.json", $users, LOCK_EX);
header("Location: profile.php?id=$id");