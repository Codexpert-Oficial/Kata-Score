const form = document.querySelector('.form');

form.addEventListener('submit', (e) => {
    e.preventDefault();

    let formData = new FormData(form);

    let url = form.getAttribute('action');

    if (isCompleted(formData)) {
        fetch(url, {
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