function get(id) { return document.getElementById(id); }
deadzoneField = get("deadzone");
deadzone = 0.36;
interval = 15;
loop = false;
get("disconnect").addEventListener("click", function() {
  socket.send(JSON.stringify({type: "disconnect"}));
  socket.close();
});
get("connect").addEventListener("click", function() {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "start.php", true);
  xhr.send();
});
get("stop-start").addEventListener("click", function() {
  if (loop) {
    loop = false;
    get("stop-start").innerHTML = "Start";
  }
  else {
    loop = true;
    get("stop-start").innerHTML = "Stop";
  }
});
socketStatus = get("socket-status");
var socket = new WebSocket("ws://127.0.0.1:8080");
window.addEventListener("load", function() {
  // new event to trigger the event listener below
  get("ip").dispatchEvent(new Event('change'));
});
  // check if the browser supports the gamepad API
get("ip").addEventListener("change", function() {
  socketStatus.innerHTML = "Verbinden...";
  socket.close();
  socket = new WebSocket("ws://" + get("ip").value + ":8080");
  socket.onopen = function() {
    socketStatus.innerHTML = "Websocket verbunden";
  };
  socket.onmessage = function(event) {
    data = JSON.parse(event.data);
    if (data.type == "gamepad") {
      // Setzt die Werte der Sticks
      leftStick_x.innerHTML = data.value.sticks[0].toFixed(4);
      leftStick_y.innerHTML = data.value.sticks[1].toFixed(4);
      rightStick_x.innerHTML = data.value.sticks[2].toFixed(4);
      rightStick_y.innerHTML = data.value.sticks[3].toFixed(4);
      left_circle.setAttribute("cx", 50 + data.value.sticks[0] * 30);
      left_circle.setAttribute("cy", 50 + data.value.sticks[1] * 30);
      right_circle.setAttribute("cx", 50 + data.value.sticks[2] * 30);
      right_circle.setAttribute("cy", 50 + data.value.sticks[3] * 30);
      // Setzt die Werte der Trigger
      get("trigger-left").setAttribute("value", data.value.triggers[0] * 100);
      get("trigger-right").setAttribute("value", data.value.triggers[1] * 100);
      // Setzt die Werte der Buttons
      for (let i = 0; i < buttons.length; i++) {
        if (data.value.buttons[i]) {
          // check if it has a name attribute
          if (buttons[i].name) {
          get(buttons[i].name).setAttribute(buttons[i].attr, "red");
          }
        }
        else {
          if (buttons[i].name) {
            if (buttons[i].name == "right-btn" || buttons[i].name == "left-btn")
          get(buttons[i].name).setAttribute(buttons[i].attr, "black");
          else get(buttons[i].name).setAttribute(buttons[i].attr, "none");
          }
        }
      }
    }
  };
  socket.onclose = function(event) {
    if (event.wasClean) {
      console.log(`[close] Verbindung geschlossen Code=${event.code} Grund=${event.reason}`);
    } else {
      console.log('[close] Verbindung getrennt');
    }
    socketStatus.innerHTML = "Websocket getrennt";
  };
  socket.onerror = function(error) {
    console.log(`[error] ${error.message}`);
  };
});

// Holt sich die Elemente für die Sticks
const [leftStick_x,leftStick_y,rightStick_x,rightStick_y,left_circle,right_circle]
= ["left-stick-x","left-stick-y","right-stick-x","right-stick-y","left","right"]
.map(get);

const buttons = [
  {name: 'button-1', index: 0, attr: 'fill'},
  {name: 'button-2', index: 1, attr: 'fill'},
  {name: 'button-3', index: 2, attr: 'fill'},
  {name: 'button-4', index: 3, attr: 'fill'},
  {name: 'bumper-left', index: 4, attr: 'fill'},
  {name: 'bumper-right', index: 5, attr: 'fill'},
  {index: 6},
  {index: 7},
  {name: 'share', index: 8, attr: 'fill'},
  {name: 'options', index: 9, attr: 'fill'},
  {name: 'left-btn', index: 10, attr: 'stroke'},
  {name: 'right-btn', index: 11, attr: 'stroke'},
  {name: 'dpad-up', index: 12, attr: 'fill'},
  {name: 'dpad-down', index: 13, attr: 'fill'},
  {name: 'dpad-left', index: 14, attr: 'fill'},
  {name: 'dpad-right', index: 15, attr: 'fill'},
  {name: 'ps-button', index: 16, attr: 'fill'},
  {name: 'touchpad', index: 17, attr: 'fill'}
];
// Test ob Gamepad API unterstützt wird
if(navigator.getGamepads) {
  // Event Listener für das Verbinden und Trennen von Gamepads
  window.addEventListener("gamepadconnected", function(e) {
    get('status').innerHTML = `Verbunden: ${e.gamepad.id.replace(/\(.*\)/, '')}`;
  });
  window.addEventListener("gamepaddisconnected", function(e) {
    get('status').innerHTML = "Controller getrennt";
  });
  setInterval(function() {
    // Holt sich das erste Gamepad
    var gamepad = navigator.getGamepads()[0];
    // Test ob ein Gamepad verbunden ist und ob der Websocket verbunden ist
    if(gamepad && socket.readyState == WebSocket.OPEN && loop) {
      // Holt sich die Werte der Sticks
      let [leftStickX, leftStickY, rightStickX, rightStickY] = [gamepad.axes[0], gamepad.axes[1], gamepad.axes[2], gamepad.axes[3]];
      // Für die Deadzone
      let leftStickDist = Math.sqrt(leftStickX ** 2 + leftStickY ** 2);
      let rightStickDist = Math.sqrt(rightStickX ** 2 + rightStickY ** 2);
      if (leftStickDist < deadzone) [leftStickX, leftStickY] = [0, 0];
      if (rightStickDist < deadzone) [rightStickX, rightStickY] = [0, 0];
      // Sendet die Daten an den Server als JSON
      socket.send(JSON.stringify({
        type: "gamepad",
        value: {
          sticks: [leftStickX, leftStickY, rightStickX, rightStickY],
          triggers: [gamepad.buttons[6].value, gamepad.buttons[7].value],
          buttons: gamepad.buttons.map(button => button.pressed)
        }
      }));
  }
  }, interval);
}
