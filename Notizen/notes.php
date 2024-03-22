<html>
<head>
  <title>Notizen</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="../reset.css">
</head>
<body>
  <header id="header">
  <h1>Notizen</h1>
  </header>
  <aside>
    <div class="note-card add-card" id="card-main" style="border-left: 15px solid red;">
      <div>
        <h3>Titel</h3>
        <input type="text" id="card-title"/>
        <div class="flex">
          <input type="color" value="#ff0000" list id="card-color">
          <button id="add-card" onclick="addNewCard()">Hinzufügen</button>
        </div>
      </div>
    </div>
    <div id="list">
      <?php
        $verbindung = new mysqli("localhost", "root", "", "ajaxTest");
        $sql = "SELECT * FROM notes ORDER BY note_id DESC";
        $result = mysqli_query($verbindung, $sql);
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['note_id'];
            $title = $row['title'];
            $color = $row['color'];
            echo "
            <div id='note-$id' class='note-card' style='border-left: 15px solid #$color;'>
              <h3>$title</h3>
              <button onclick='deleteCard($id)'>X</button>
            </div>";
          }
        }
      ?>
    </div>
  </aside>
  <main>
    <textarea id="textarea" placeholder="Hier etwas eingeben..."><?php
    ?></textarea>
  </main>
</body>
</html>
<script src="functions.js"></script>