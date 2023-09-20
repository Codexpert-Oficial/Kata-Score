const body = document.body;

fetch("/kata-score/model/judgePanel.php")
    .then(response => response.text())
    .then(data => body.innerHTML = data + body.innerHTML)
    .catch(error => console.log(error));