const container = document.querySelector(".extraScore__container");

fetch("/kata-score/model/participantExtraScore.php")
    .then(response => response.text())
    .then(data => container.innerHTML = data)
    .catch(error => console.log(error));