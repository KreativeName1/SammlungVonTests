<?php
  $conn = new mysqli("localhost", "root", "", "ajaxTest");
  $card = $_GET['card'];
  $type = $_GET['type'];

  if ($type == 'new') {
  $card = json_decode($card);
  $title = $card->title;
  $color = $card->color;
  $sql = "INSERT INTO notes (title, color) VALUES ('$title', '$color')";
  $result = mysqli_query($conn, $sql);
  $id = mysqli_insert_id($conn);
  echo "
  <div id='card-$id' class='note-card' style='border-left: 15px solid #$color;'>
    <h3>$title</h3>
    <button onclick='deleteCard($id)'>X</button>
  </div>
  ";
  }
  else if ($type == 'delete') {
    $sql = "DELETE FROM notes WHERE note_id = $card";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      echo "success";
    }
    else {
      echo "error";
    }
  }
  else if ($type == 'display') {
    $sql = "SELECT * FROM notes WHERE note_id = $card";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo $row['description'];
  }
  else if ($type == 'save') {
    $content = $_GET['content'];
    $sql = "UPDATE notes SET description = '$content' WHERE note_id = $card";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      echo "success";
    }
    else {
      echo "error";
    }
  }
  else {
    echo "error";
  }
?>