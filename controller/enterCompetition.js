const form = document.querySelector('.form');

form.addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    if (formData.get('name') == '') {
        formData.set('name', 'Competencia');
    }

    if (isCompleted(formData)) {
        fetch("/kata-score/model/enterCompetition.php", {
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

const modalitySelect = document.querySelector('.modality-select');
const categorySelect = document.querySelector('.category-select');

modalitySelect.addEventListener("change", () => {
    if (modalitySelect.value == "paraKarate") {
        categorySelect.classList.remove("select-hidden");
    } else {
        categorySelect.classList.add("select-hidden");
    }
})