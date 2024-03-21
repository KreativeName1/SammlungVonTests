function openProfile() {
  var profile = document.getElementById("profilePopup");
  if (profile.style.display === "flex") {
    profile.style.display = "none";
  } else {
    profile.style.display = "flex";
  }
}
function openForm($id) {
  document.getElementById($id).style.display = "block";
}
function closeForm($id) {
  document.getElementById($id).style.display = "none";
}