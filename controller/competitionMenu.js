const competitionMenu = document.querySelector(".competition__menu");

const controls = () => {

    const buttons = document.querySelectorAll(".competition__element__icon");

    buttons.forEach(button => {

        button.addEventListener('click', () => {
            let formData = new FormData();
            formData.set('id', button.getAttribute('data-id'));
            formData.set('action', button.getAttribute('data-action'));

            console.log(formData);

            fetch("/kata-score/model/competitionsControls.php", {
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
                .then(() => {
                    location.reload();
                })
                .catch(error => {
                    changeMsg(error.message);
                    openMsgError();
                });

        });

    });
};

const links = () => {
    const competitions = document.querySelectorAll(".competition__info");

    competitions.forEach(competition => {

        competition.addEventListener('click', () => {

            formData = new FormData();

            formData.set("competition", competition.getAttribute("value"));

            fetch("/kata-score/model/setCurrentCompetition.php", {
                method: "POST",
                body: formData
            })
                .catch(error => console.log(error));

            window.location.href = "/kata-score/view/html/controlPanel/controlPanel.html";
        });

    });

}

fetch("/kata-score/model/competitionMenu.php")
    .then(response => response.text())
    .then(data => {
        competitionMenu.innerHTML += data;
        controls();
        links();
    })
    .catch(error => {
        console.log(error);
    });

