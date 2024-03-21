function get(id) { return document.getElementById(id); }
// Die Deadzone der Sticks
deadzoneField = get("deadzone");
deadzone = 0.36;
interval = 50;

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
  {name: 'trigger-left', index: 6},
  {name: 'trigger-right', index: 7},
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
    get('status').innerHTML = `Controller verbunden: ${e.gamepad.id.replace(/\(.*\)/, '')}`;
  });
  window.addEventListener("gamepaddisconnected", function(e) {
    get('status').innerHTML = "Controller getrennt";
  });
  setInterval(function() {
    // Holt sich das erste Gamepad
    var gamepad = navigator.getGamepads()[0];
    // Test ob ein Gamepad verbunden ist
    if(gamepad) {
      // Holt sich die Werte der Sticks
      let [leftStickX, leftStickY, rightStickX, rightStickY] = [gamepad.axes[0], gamepad.axes[1], gamepad.axes[2], gamepad.axes[3]];
      // Für die Deadzone
      let leftStickDist = Math.sqrt(leftStickX ** 2 + leftStickY ** 2);
      let rightStickDist = Math.sqrt(rightStickX ** 2 + rightStickY ** 2);
      if (leftStickDist < deadzone) [leftStickX, leftStickY] = [0, 0];
      if (rightStickDist < deadzone) [rightStickX, rightStickY] = [0, 0];

      // Die Kreise der Sticks bewegen
      [left_circle, right_circle].forEach((circle, i) => {
        const [x, y] = [[leftStickX, leftStickY], [rightStickX, rightStickY]][i];
        [x, y].forEach((value, i) => circle.setAttribute(['cx', 'cy'][i], 50 + value * 30));
      });
      // Die Werte der Sticks anzeigen
      [leftStickX, leftStickY, rightStickX, rightStickY].forEach((value, i) => {
        const element = [leftStick_x, leftStick_y, rightStick_x, rightStick_y][i];
        element.innerHTML = ['X: ', 'Y: '][i % 2] + value.toFixed(4);
      });
      // Für die Buttons
      for (let i = 0; i < buttons.length; i++) {
        const button = buttons[i];
        const element = get(button.name);
        let value = gamepad.buttons[button.index].pressed ? 'red' : 'none';
        button.attr === 'stroke' ? value = gamepad.buttons[button.index].pressed ? 'red' : 'black' : null;
        element.setAttribute(button.attr, value);
        button.name === 'trigger-left' ? element.setAttribute('value', gamepad.buttons[6].value * 100) : null;
        button.name === 'trigger-right' ? element.setAttribute('value', gamepad.buttons[7].value * 100) : null;
      }
  }
  }, interval);

}
