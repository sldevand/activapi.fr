export class HttpRequest {
    postJson(url, body) {
        return fetch(url, {
            method: 'post',
            body: body,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(function (response) {
            return response.json();
        })
    }
}