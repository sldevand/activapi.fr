import { ConsoleRow } from "./templates/console-row";

export class Console {

    constructor(address) {
        this.nodeAddress = address + '/node';
    }

    init() {
        const display = document.querySelector('#console-display');
        const period = document.querySelector('#period').value;

        fetch(this.nodeAddress + "/log/" + period)
            .then((data) => {
                return data.json();
            })
            .then((logs) => {
                this.logs = logs.messages;
                for (let log of this.logs) {
                    let row = new ConsoleRow(log);
                    display.appendChild(row.render());
                }
            })
            .catch(err => console.log(err))
    }
}
