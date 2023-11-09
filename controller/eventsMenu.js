const eventMenu = document.querySelector(".competition__menu");

const controls = () => {

    const buttons = document.querySelectorAll(".competition__element__icon");

    buttons.forEach(button => {

        button.addEventListener('click', () => {
            let formData = new FormData();
            formData.set('id', button.getAttribute('data-id'));

            fetch("/kata-score/model/eventsControls.php", {
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

            window.location.href = "./competitionMenu.html";
        });

    });

}

fetch("/kata-score/model/eventsMenu.php")
    .then(response => response.text())
    .then(data => {
        eventMenu.innerHTML += data;
        controls();
        links();
    })
    .catch(error => {
        console.log(error);
    });