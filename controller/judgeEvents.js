const buttons = () => {
    const events = document.querySelectorAll(".competition__info");

    events.forEach(event => {

        event.addEventListener('click', () => {

            formData = new FormData();

            formData.set("event", event.getAttribute("value"));

            fetch("/kata-score/model/setCurrentEvent.php", {
                method: "POST",
                body: formData
            })
                .catch(error => console.log(error));

            window.location.href = "./judgeCompetitions.html";
        });

    });

}

const eventsMenu = document.querySelector(".competition__menu");

fetch("/kata-score/model/judgeEvents.php")
    .then(response => response.text())
    .then(data => {
        eventsMenu.innerHTML = data;
        buttons();
    })
    .catch(error => console.log(error));