const form = document.querySelector('.form');

form.addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    if (formData.get('name') == '') {
        formData.set('name', 'Competicion');
    }

    if (isCompleted(formData)) {
        fetch("/kata-score/php/enterCompetition.php", {
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
    }
});