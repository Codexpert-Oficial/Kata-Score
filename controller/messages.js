const msg = document.querySelector('.msg');
const msgInfo = document.querySelector('.msg__info');
const closeMsgButton = document.querySelector('.msg__close__button');
const closeMsgTab = document.querySelector('.msg__close');
const msgTitle = document.querySelector('.msg__title');
const msgText = document.querySelector('.msg__text');
const msgIcon = document.querySelector('.msg__icon');

const openMsgSuccess = () => {
    closeMsgTab.classList.remove('msg__close-error', 'msg__close-warning');
    closeMsgTab.classList.add('msg__close-success');
    msgTitle.innerHTML = 'Accion exitosa';
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
    msgTitle.innerHTML = 'Atencion';
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