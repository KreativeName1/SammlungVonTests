
velocityX = get("velocityX");
velocityY = get("velocityY");
velocityZ = get("velocityZ");
Status = get("status");
function get(id) {
  return document.getElementById(id);
}

const listener = new GamepadListener({
  deadZone: 0.3
});

listener.on('gamepad:connected', event => {
  const {
      index, // Gamepad index: Number [0-3].
      gamepad, // Native Gamepad object.
  } = event.detail;

  console.log(`Gamepad ${index} connected!`);
});