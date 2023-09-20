const main = document.querySelector(".content");

fetch("/kata-score/model/listParticipants.php")
    .then(response => response.text())
    .then(data => main.innerHTML += data)
    .catch(error => console.log(error));