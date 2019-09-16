import {ConsoleTemplate} from "./templates/console-template";

export class Console {

    constructor(address) {
        this.nodeAddress = address + '/node';
    }

    init() {
        const display = document.querySelector('#console-display');
        const period = document.querySelector('#period').value;

        fetch( this.nodeAddress+"/log/" + period)
            .then((data) => {
                return data.json();
            })
            .then((logs) => {

                this.logs = logs.messages;

                display.innerHTML = this.createDisplayTemplate();
            })
            .catch(err => console.log(err))
    }

    createDisplayTemplate() {
        return ConsoleTemplate.render(this.logs);
    }
}
