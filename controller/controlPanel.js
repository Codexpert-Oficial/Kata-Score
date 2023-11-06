const load = () => {
    const callParticipant = document.querySelector(".callCurrentParticipant");
    const scoreParticipant = document.querySelector(".scoreCurrentParticipant");
    const nextRound = document.querySelector(".nextRound");

    nextRound.addEventListener("click", (e) => {
        e.preventDefault();

        fetch('/kata-score/model/nextRound.php', {
            method: "POST"
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
            .then(data => {
                changeMsg(data);
                openMsgSuccess();
            })
            .catch(error => {
                changeMsg(error.message);
                openMsgError();
            });

    });

    callParticipant.addEventListener("click", (e) => {
        e.preventDefault();

        fetch('/kata-score/model/callCurrentParticipant.php', {
            method: "POST"
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
            .then(data => {
                changeMsg(data);
                openMsgSuccess();
            })
            .catch(error => {
                changeMsg(error.message);
                openMsgError();
            });

    });

    scoreParticipant.addEventListener("click", (e) => {
        e.preventDefault();

        fetch('/kata-score/model/scoreCurrentParticipant.php', {
            method: "POST"
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
            .then(data => {
                changeMsg(data);
                openMsgSuccess();
            })
            .catch(error => {
                changeMsg(error.message);
                openMsgError();
            });
    });
}

const script = document.createElement('script');
script.src = '/kata-score/controller/messages.js';
script.type = 'text/javascript';

fetch("/kata-score/model/controlPanel.php")
    .then(response => response.text())
    .then(data => {
        document.body.innerHTML = data + document.body.innerHTML;
        load();
        document.body.appendChild(script);
    })
    .catch(error => console.log(error));