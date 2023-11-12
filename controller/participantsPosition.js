const content = document.querySelector(".content");

fetch("/kata-score/model/savePositions.php")
    .then(response => response.text())
    .then(data => content.innerHTML += data)
    .catch(error => console.log(error));