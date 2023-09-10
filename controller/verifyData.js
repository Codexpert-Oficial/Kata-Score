const isCompleted = (formData) => {

    let response = true;

    formData.forEach((data, index) => {
        if (data == '') {
            changeMsg("rellena el campo " + index);
            openMsgError();
            response = false;
        }

        if (index == 'ci') {

            if (data < 10000000 || data > 99999999) {
                console.log(index + data);
                changeMsg("Ingrese un ci valido");
                openMsgError();
                response = false;
            }
        }

        if (index == 'pool' || index == 'ci' || index == 'number' || index == 'score' || index == 'kata') {
            if (isNaN(data)) {
                changeMsg("Ingrese un " + index + " valido");
                openMsgError();
                response = false;
            }
        }

        if (index == 'ageRange') {
            if (data != '12-13' && data != '14-15' && data != '16-17' && data != 'mayor') {
                changeMsg("Ingrese una edad valida");
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
                changeMsg("Ingrese un sexo valido");
                openMsgError();
                response = false;

            }
        }

        if (index == 'score') {
            if (data < 5 || data > 10) {
                changeMsg("Ingrese un puntaje valido");
                openMsgError();
                response = false;
            }
        }

        if (index == 'kata') {
            if (data < 1 || data > 102) {
                changeMsg("Ingrese un kata valido");
                openMsgError();
                response = false;
            }
        }

    });

    return response;
}
