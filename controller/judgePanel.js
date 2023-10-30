const body = document.body;
const script1 = document.createElement('script');
script1.src = '/kata-score/controller/messages.js';
script1.type = 'text/javascript';
const script2 = document.createElement('script');
script2.src = '/kata-score/controller/verifyData.js';
script2.type = 'text/javascript';
const script3 = document.createElement('script');
script3.src = '/kata-score/controller/formFetch.js';
script3.type = 'text/javascript';

fetch("/kata-score/model/judgePanel.php")
    .then(response => response.text())
    .then(data => {
        body.innerHTML = data + body.innerHTML;
        document.body.appendChild(script1);
        document.body.appendChild(script2);
        document.body.appendChild(script3);
    })
    .catch(error => console.log(error));