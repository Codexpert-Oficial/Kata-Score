

const isCompleted = (formData) => {

    let response = true;

    formData.forEach((data, index) => {
        if (data == '') {
            if (lang = "es") {
                changeMsg("Rellena el campo " + index);
            } else {
                changeMsg("Fill in the field " + index);
            }
            openMsgError();
            response = false;
        }

        if (index == 'ci') {

            if (data < 10000000 || data > 99999999) {
                console.log(index + data);
                if (lang = "es") {
                    changeMsg("Ingrese un ci valido");
                } else {
                    changeMsg("Enter a valid ci");
                }
                openMsgError();
                response = false;
            }
        }

        if (index == 'pool' || index == 'ci' || index == 'number' || index == 'score' || index == 'kata') {
            if (isNaN(data)) {
                if (lang = "es") {
                    changeMsg("Ingrese un " + index + " valido");
                } else {
                    changeMsg("Enter a valid " + index);
                }
                openMsgError();
                response = false;
            }
        }

        if (index == 'ageRange') {
            if (data != '12-13' && data != '14-15' && data != '16-17' && data != 'mayor') {
                if (lang = "es") {
                    changeMsg("Ingrese una edad valida");
                } else {
                    changeMsg("Enter a valid age");
                }
                openMsgError();
                response = false;
            }
        }

        if (index == 'teamType') {
            if (data != 'individual' && data != 'grupo') {
                changeMsg("Ingrese un tipo de equipos valido");
                openMsgError();
                response = false;
            }
        }

        if (index == 'gender') {
            if (data != 'masculino' && data != 'femenino') {
                if (lang = "es") {
                    changeMsg("Ingrese un sexo valido");
                } else {
                    changeMsg("Enter a valid gender");
                }
                openMsgError();
                response = false;

            }
        }

        if (index == 'score') {
            if (data < 5 || data > 10) {
                if (lang = "es") {
                    changeMsg("Ingrese un puntaje valido");
                } else {
                    changeMsg("Enter a valid score");
                }
                openMsgError();
                response = false;
            }
        }

        if (index == 'kata') {
            if (data < 1 || data > 102) {
                if (lang = "es") {
                    changeMsg("Ingrese un kata valido");
                } else {
                    changeMsg("Enter a valid kata");
                }
                openMsgError();
                response = false;
            }
        }

    });

    return response;
}
