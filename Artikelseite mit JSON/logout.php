<?php
session_start();
$_SESSION["user"] = "";
$_SESSION["logged_in"] = false;
session_destroy();
header("Location: login.php");
?>