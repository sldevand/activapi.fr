export class ApiManage {

    constructor(method, action, jsonHeader = true) {
        this.request = new XMLHttpRequest();
        this.method = method;
        this.action = action;
        this.jsonHeader = jsonHeader;

    }

    setJsonHeader() {
        this.request.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
    }

    sendObject(object, callback) {

        this.request.onreadystatechange = () => {
            if (this.request.readyState !== 4) {
                return;
            }
           callback(this.request);
        }

        this.request.open(this.method, this.action);
        if (this.jsonHeader) {
            this.setJsonHeader();
        }

        this.request.send(object);
    }
}