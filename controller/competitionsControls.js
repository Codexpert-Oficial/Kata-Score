const buttons = document.querySelectorAll(".competition__element__icon");

buttons.forEach(button => {

    button.addEventListener('click', () => {
        let formData = new FormData();
        formData.set('id', button.getAttribute('data-id'));
        formData.set('action', button.getAttribute('data-action'));

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
