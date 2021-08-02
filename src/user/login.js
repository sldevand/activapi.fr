document.addEventListener('DOMContentLoaded', () => {
    let loginForm = document.querySelector('#login-form');
    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const form = e.target;
        const body = JSON.stringify(Object.fromEntries(new FormData(form).entries()));

        fetch(form.action, {
            method: 'post',
            body: body,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(function (response) {
            return response.json();
        }).then(function (data) {
            let message = data.hasOwnProperty('error')
                ? 'Error : ' + data.error
                : 'Success : ' + data.data;

            if (message) {
                Materialize.toast(message, 3000);
            }
        });
    });
});