document.addEventListener('DOMContentLoaded', (event) => {
    let loginForm = document.querySelector('#login-form');
    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const body = JSON.stringify(Object.fromEntries(new FormData(e.target).entries()));

        fetch('/api/user/login', {
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