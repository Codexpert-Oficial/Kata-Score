const select = document.querySelector(".select");

fetch("/kata-score/model/participantSchool.php")
    .then(response => response.text())
    .then(data => select.innerHTML += data)
    .catch(error => console.log(error));