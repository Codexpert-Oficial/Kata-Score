const buttons = () => {
    const competitions = document.querySelectorAll(".competition__info");

    competitions.forEach(competition => {

        competition.addEventListener('click', () => {

            formData = new FormData();

            formData.set("scoreCompetition", competition.getAttribute("value"));

            fetch("/kata-score/model/setScoreScreenCompetition.php", {
                method: "POST",
                body: formData
            })
                .catch(error => console.log(error));

            window.location.href = "/kata-score/view/html/controlPanel/scoreScreen.html";
        });

    });

}

const competitionsMenu = document.querySelector(".competition__menu");

fetch("/kata-score/model/scoreScreenCompetitions.php")
    .then(response => response.text())
    .then(data => {
        competitionsMenu.innerHTML = data;
        buttons();
    })
    .catch(error => console.log(error));