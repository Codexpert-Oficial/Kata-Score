const switchCheckBox = document.querySelector(".switch__checkbox");

const getCookie = (cname) => {
    let name = cname + "=";
    let cookies = decodeURIComponent(document.cookie);
    let cookieArray = cookies.split(';');
    for (let i = 0; i < cookieArray.length; i++) {
        let cookie = cookieArray[i];
        while (cookie.charAt(0) == ' ') {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(name) == 0) {
            return cookie.substring(name.length, cookie.length);
        }
    }
    return "";
}

const setCookie = (name, value, days) => {
    let d = new Date();
    d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/;";
}

let enURL = "/kata-score/view/html/en/index.html";
let esURL = "/kata-score/";

let lang = getCookie("lang");

let i = window.location.href.indexOf("/kata-score");
let url = window.location.href.substring(i);
console.log(url);

if (lang == "es" && url == enURL) {
    window.location.href = esURL;
} else if (lang == "en" && url == esURL) {
    window.location.href = enURL;
} else if (lang == "") {
    setCookie("lang", "es", 10);
}

switchCheckBox.addEventListener("change", () => {

    setTimeout(() => {
        if (switchCheckBox.checked) {
            setCookie("lang", "en", 10)
            window.location.href = enURL;

        } else {
            setCookie("lang", "es", 10)
            window.location.href = esURL;

        }
    }, 300);

})

