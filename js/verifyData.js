const isCompleted = (formData) => {

    formData.forEach((data, index) => {
        if (data == '') {
            changeMsg("rellena el campo " + index);
            openMsgError();
            return false;
        }
    });

    return true;
}
