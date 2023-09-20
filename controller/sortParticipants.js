const table = document.querySelector(".table");

fetch("/kata-score/model/sortParticipants.php")
    .then(response => response.text())
    .then(data => table.innerHTML += data)
    .catch(error => console.log(error));