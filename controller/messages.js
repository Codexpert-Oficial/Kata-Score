const msg = document.querySelector('.msg');
const msgInfo = document.querySelector('.msg__info');
const closeMsgButton = document.querySelector('.msg__close__button');
const closeMsgTab = document.querySelector('.msg__close');
const msgTitle = document.querySelector('.msg__title');
const msgText = document.querySelector('.msg__text');
const msgIcon = document.querySelector('.msg__icon');

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

lang = getCookie("lang");

const openMsgSuccess = () => {
    closeMsgTab.classList.remove('msg__close-error', 'msg__close-warning');
    closeMsgTab.classList.add('msg__close-success');
    if (lang == "es") {
        msgTitle.innerHTML = 'Accion exitosa';
    } else if (lang == "en") {
        msgTitle.innerHTML = 'Successful action';
    }
    msgText.classList.remove('msg__text-error', 'msg__text-warning');
    msgText.classList.add('msg__text-success');
    msgIcon.removeAttribute('src');
    msgIcon.src = '/kata-score/view/imgs/icons/success.svg';
    msg.classList.remove('msg__hidden');
}

const openMsgError = () => {
    closeMsgTab.classList.remove('msg__close-success', 'msg__close-warning');
    closeMsgTab.classList.add('msg__close-error');
    msgTitle.innerHTML = 'Error';
    msgText.classList.remove('msg__text-success', 'msg__text-warning');
    msgText.classList.add('msg__text-error');
    msgIcon.removeAttribute('src');
    msgIcon.src = '/kata-score/view/imgs/icons/error.svg';
    msg.classList.remove('msg__hidden');
}

const openMsgWarning = () => {
    closeMsgTab.classList.remove('msg__close-error', 'msg__close-warning');
    closeMsgTab.classList.add('msg__close-success');
    if (lang == "es") {
        msgTitle.innerHTML = 'Atencion';
    } else if (lang == "en") {
        msgTitle.innerHTML = 'Warning';
    }
    msgText.classList.remove('msg__text-error', 'msg__text-warning');
    msgText.classList.add('msg__text-success');
    msgIcon.removeAttribute('src');
    msgIcon.src = '/kata-score/view/imgs/icons/success.svg';
    msg.classList.remove('msg__hidden');
}

const changeMsg = (text) => msgText.innerHTML = text;

const closeMsg = () => msg.classList.add('msg__hidden');

msgInfo.addEventListener('click', (e) => e.stopPropagation());

msg.addEventListener('click', closeMsg);

closeMsgButton.addEventListener('click', closeMsg);