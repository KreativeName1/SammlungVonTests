function openModal($id) {
    var modal = document.getElementById($id);
    modal.style.display = "flex";
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}
function closeModal($id) {
    var modal = document.getElementById($id);
    modal.style.display = "none";
}
function toggleModal($id) {
    var modal = document.getElementById($id);
    if (modal.style.display == "block") {
        modal.style.display = "none";
    } else {
        modal.style.display = "block";
    }
}