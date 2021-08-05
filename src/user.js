import {HttpRequest} from './services/http-request';

document.addEventListener('DOMContentLoaded', () => {
    let loginForm = document.querySelector('form');
    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const form = e.target;
        const body = JSON.stringify(Object.fromEntries(new FormData(form).entries()));
        let request = new HttpRequest();
        request
            .postJson(form.action, body)
            .then(function (data) {
                let message = data.hasOwnProperty('error')
                    ? 'Error : ' + data.error
                    : 'Success : ' + data.data;

                if (message) {
                    Materialize.toast(message, 3000);
                    if (data.hasOwnProperty('data')) {
                        window.location.replace('');
                    }
                }
            }).catch(err => console.log(err));
    });
});