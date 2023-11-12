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

}

const script1 = document.createElement('script');
script1.src = '/kata-score/controller/messages.js';
script1.type = 'text/javascript';
const script2 = document.createElement('script');
script2.src = '/kata-score/controller/scoreScreenControls.js';
script2.type = 'text/javascript';

fetch("/kata-score/model/controlPanel.php")
    .then(response => response.text())
    .then(data => {
        document.body.innerHTML = data + document.body.innerHTML;
        load();
        document.body.appendChild(script1);
        document.body.appendChild(script2);
    })
    .catch(error => console.log(error));