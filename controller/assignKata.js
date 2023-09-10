const assignKata = document.querySelector(".assign__kata");
const editKataButtons = document.querySelectorAll(".edit__kata");
const form = document.querySelector(".form");
let participantCi;

editKataButtons.forEach((editKata) => {
    editKata.addEventListener("click", () => {
        participantCi = editKata.getAttribute("data-participant");
        assignKata.classList.remove("assign__kata__hidden");
    });
});

assignKata.addEventListener("click", () => {
    window.location.reload();
});

form.addEventListener("click", (e) => {
    e.stopPropagation();
})

form.addEventListener("submit", (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    formData.set("participant", participantCi);

    if (isCompleted(formData)) {
        fetch("/kata-score/model/kataAssign.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (response.status === 200) {
                    return response.text();
                } else {
                    return response.json().then((error) => {
                        throw new Error(error.error);
                    });
                }
            })
            .then((data) => {
                changeMsg(data);
                openMsgSuccess();
            })
            .catch((error) => {
                changeMsg(error.message);
                openMsgError();
            });
    }
});
