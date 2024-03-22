
let card_main = get("card-main")
let card_list = get("list");

function get(what) {
  return document.getElementById(what);
}
card_list.addEventListener("click", function(e) {
  if (e.target.tagName == "DIV") {
    displayCard(e.target.id.split("-")[1]);
  }
});
get("card-color").addEventListener("input", function() {
  card_main.style.borderColor = card_color.value;
});


function deleteCard(id) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText == "success") {
        get("card-" + id).remove();
      }
    }
  }
  xhttp.open("GET", "cards.php?type=delete&card=" + id, true);
  xhttp.send();
}

function displayCard(id) {
  if (id == null) return;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      get("textarea").blur();
      get("textarea").value = this.responseText;
    }
  }
  xhttp.open("GET", "cards.php?type=display&card=" + id, true);
  xhttp.send();
  get("header").innerHTML = "<h1>Notizen</h1><button onclick='speichern(" + id + ")'>Speichern</button>";
}

function speichern(id) {
  var xhttp = new XMLHttpRequest();
  xhttp.open("GET", "cards.php?type=save&card=" + id + "&content=" + get("textarea").value, true);
  xhttp.send();
}

function addNewCard() {
  var card = {
    "title": get("card-title").value,
    "color": get("card-color").value.replace("#", "")
  };
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      card_list.innerHTML = this.responseText + card_list.innerHTML;
    }
  }
  xhttp.open("GET", "cards.php?type=new&card=" + JSON.stringify(card), true);
  xhttp.send();
}

