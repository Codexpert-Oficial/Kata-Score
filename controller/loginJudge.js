const form = document.querySelector('.form');

if (form !== null) {

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(form);

        if (isCompleted(formData)) {
            fetch("/kata-score/model/loginJudge.php", {
                method: "POST",
                body: formData
            })
                .then(response => {
                    if (response.status === 200) {
                        return response.text();
                    } else {
                        return response.json().then(error => {
                            throw new Error(error.error);
                        });
                    }
                })
                .then(() =>
                    window.location.href = './judgeCompetitions.html'
                )
                .catch(error => {
                    changeMsg(error.message);
                    openMsgError();
                });
        }
    });

}

const competitions = document.querySelector(".competition__menu");

if (competitions !== null) {

    const links = () => {
        const competitionsLinks = document.querySelectorAll(".competition__info");

        competitionsLinks.forEach(competition => {

            competition.addEventListener('click', () => {

                formData = new FormData();

                formData.set("judgeCompetition", competition.getAttribute("value"));

                fetch("/kata-score/model/setJudgeCompetition.php", {
                    method: "POST",
                    body: formData
                })
                    .catch(error => console.log(error));

                window.location.href = "./judgePanel.html";
            });

        });
    }

    fetch("/kata-score/model/judgeCompetitions.php")
        .then(response => response.text())
        .then(data => {
            competitions.innerHTML = data
            links();
        })
        .catch(error => console.log(error));

}
