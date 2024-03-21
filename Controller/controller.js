// Die Deadzone der Sticks
deadzoneField = get("deadzone");
deadzone = 0.36;
deadzoneField.addEventListener("change", function() {
  deadzone = deadzoneField.value;
  console.log(deadzone);
});

// Die Elemente für die Anzeige der Werte
Status = get("status"); // Status
leftStick_x = get("left-stick-x"); // left stick X value
leftStick_y = get("left-stick-y"); // left stick Y value
rightStick_x = get("right-stick-x"); // right stick X value
rightStick_y = get("right-stick-y"); // right stick Y value
left_circle = get("left"); // left stick
right_circle = get("right"); // right stick
dpadUp = get("dpad-up"); // dpad
dpadDown = get("dpad-down"); // dpad
dpadLeft = get("dpad-left"); // dpad
dpadRight = get("dpad-right"); // dpad
button1 = get("button-1"); // X
button2 = get("button-2"); // O
button3 = get("button-3"); // □
button4 = get("button-4"); // △
leftBtn = get("left-btn"); // L1
rightBtn = get("right-btn"); // R3
triggerLeft = get("trigger-left"); // L2
triggerRight = get("trigger-right"); // R2
bumperLeft = get("bumper-left"); // L1
bumperRight = get("bumper-right"); // R1
share = get("share"); // Share
options = get("options"); // Options
psButton = get("ps-button"); // PS

function get(id) {
  return document.getElementById(id);
}

// Test ob Gamepad API unterstützt wird
if(navigator.getGamepads) {
  // Event Listener für das Verbinden und Trennen von Gamepads
  window.addEventListener("gamepadconnected", function(e) {
    Status.innerHTML = "Controller verbunden: " + e.gamepad.id;

  });
  window.addEventListener("gamepaddisconnected", function(e) {
    Status.innerHTML = "Controller getrennt: " + e.gamepad.id;
  });

  // Alle 50ms aktualisieren
  setInterval(function() {

    // Holt sich das erste Gamepad
    var gamepad = navigator.getGamepads()[0];
    // Holt sich die Werte der Sticks
    let leftStickY = gamepad.axes[1];
    let leftStickX = gamepad.axes[0];
    let rightStickY = gamepad.axes[3];
    let rightStickX = gamepad.axes[2];

    // Test ob ein Gamepad verbunden ist
    if(gamepad) {

      // Die Deadzone der Sticks
      if (Math.abs(leftStickX) < deadzone || Math.abs(leftStickY) < -deadzone) {
        leftStickX = 0;
      }
      if (Math.abs(rightStickX) < deadzone || Math.abs(rightStickY) < -deadzone) {
        rightStickX = 0;
      }
      if (Math.abs(leftStickY) < deadzone || Math.abs(leftStickY) < -deadzone) {
        leftStickY = 0;
      }
      if (Math.abs(rightStickY) < deadzone || Math.abs(rightStickY) < -deadzone) {
        rightStickY = 0;
      }

      // Die Werte der Sticks anzeigen
      leftStick_x.innerHTML = "X: " + leftStickX.toFixed(4);
      leftStick_y.innerHTML = "Y: " + leftStickY.toFixed(4);
      rightStick_x.innerHTML = "X: " + rightStickX.toFixed(4);
      rightStick_y.innerHTML = "Y: " + rightStickY.toFixed(4);

      // Die Kreise der Sticks bewegen
      left_circle.setAttribute("cx", 50 + leftStickX *30);
      left_circle.setAttribute("cy", 50 + leftStickY * 30);
      right_circle.setAttribute("cx", 50 + rightStickX *30);
      right_circle.setAttribute("cy", 50 + rightStickY * 30);

      // Für die Trigger
      triggerLeft.setAttribute("value", gamepad.buttons[6].value * 100);
      triggerRight.setAttribute("value", gamepad.buttons[7].value * 100);
      // Für das D-Pad
      if (gamepad.buttons[12].pressed) {
        dpadUp.setAttribute("fill", "red");
      }
      else {
        dpadUp.setAttribute("fill", "none");
      }
      if (gamepad.buttons[13].pressed) {
        dpadDown.setAttribute("fill", "red");
      }
      else {
        dpadDown.setAttribute("fill", "none");
      }
      if (gamepad.buttons[14].pressed) {
        dpadLeft.setAttribute("fill", "red");
      }
      else {
        dpadLeft.setAttribute("fill", "none");
      }
      if (gamepad.buttons[15].pressed) {
        dpadRight.setAttribute("fill", "red");
      }
      else {
        dpadRight.setAttribute("fill", "none");
      }

      // Für die Buttons X, O, □, △
      if (gamepad.buttons[0].pressed) {
        button1.setAttribute("fill", "red");
      }
      else {
        button1.setAttribute("fill", "none");
      }
      if (gamepad.buttons[1].pressed) {
        button2.setAttribute("fill", "red");
      }
      else {
        button2.setAttribute("fill", "none");
      }
      if (gamepad.buttons[2].pressed) {
        button3.setAttribute("fill", "red");
      }
      else {
        button3.setAttribute("fill", "none");
      }
      if (gamepad.buttons[3].pressed) {
        button4.setAttribute("fill", "red");
      }
      else {
        button4.setAttribute("fill", "none");
      }

      // für die Buttons L3 und R3
      if (gamepad.buttons[10].pressed) {
        leftBtn.setAttribute("stroke", "pink");
      }
      else {
        leftBtn.setAttribute("stroke", "black");
      }
      if (gamepad.buttons[11].pressed) {
        rightBtn.setAttribute("stroke", "pink");
      }
      else {
        rightBtn.setAttribute("stroke", "black");
      }

      // Für die Buttons L1 und R1
      if (gamepad.buttons[4].pressed) {
        bumperLeft.setAttribute("fill", "red");
      }
      else {
        bumperLeft.setAttribute("fill", "none");
      }
      if (gamepad.buttons[5].pressed) {
        bumperRight.setAttribute("fill", "red");
      }
      else {
        bumperRight.setAttribute("fill", "none");
      }

      // Für die Buttons Share, Options und PS
      if (gamepad.buttons[8].pressed) {
        share.setAttribute("fill", "red");
      }
      else {
        share.setAttribute("fill", "none");
      }
      if (gamepad.buttons[9].pressed) {
        options.setAttribute("fill", "red");
      }
      else {
        options.setAttribute("fill", "none");
      }
      if (gamepad.buttons[16].pressed) {
        psButton.setAttribute("fill", "red");
      }
      else {
        psButton.setAttribute("fill", "none");
      }
  }
  }, 50); // Alle 50ms aktualisieren
}
