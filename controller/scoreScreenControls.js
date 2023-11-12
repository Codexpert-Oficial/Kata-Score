const buttons = document.querySelectorAll(".scoreScreenControl");

buttons.forEach(button => {

    button.addEventListener("click", (e) => {

        e.preventDefault();

        let option = button.getAttribute("data-option");

        formData = new FormData();

        formData.set("option", option);

        fetch('/kata-score/model/scoreScreenControls.php', {
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
            .then(data => {
                changeMsg(data);
                openMsgSuccess();
            })
            .catch(error => {
                changeMsg(error.message);
                openMsgError();
            });

    });

});

