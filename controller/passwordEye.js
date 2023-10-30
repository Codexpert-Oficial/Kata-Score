const input = document.querySelector(".input-password");
const eye = document.querySelector(".password__eye");

eye.addEventListener("click", (e) => {

    e.preventDefault();
    if (input.type == 'text') {
        input.type = 'password';
    } else {
        input.type = 'text';
    }
    eye.classList.toggle("password__eye__hidden")

});
