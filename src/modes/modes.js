import socketIOManage from '../socketio.js'

export class Modes {

    constructor() {
        this.dataModes = [];
    }

    askThermostat() {
        let command = 'nrf24/node/2Nodw/ther/get/mode/254/';
        socketIOManage.socket.emit("command", command);
    }

    init() {
        socketIOManage.socket.on("messageConsole", (data) => {

            let messageTab = data.split(" ");
            if (
                messageTab[1] !== "thermode"
                || this.dataModes.length >= 4
            ) {
                return;
            }

            this.dataModes.push(messageTab.slice(2));


            if (this.dataModes.length !== 4) {
                return;
            }

            this.createTable(this.dataModes);
            this.checkState()
                .then((modes) => {
                    let matched = 0;
                    for (let mode of modes) {
                        for (let dataMode of this.dataModes) {

                            if (
                                dataMode[0] === mode.id
                                && parseFloat(dataMode[1]).toFixed(2) === parseFloat(mode.consigne).toFixed(2)
                                && parseFloat(dataMode[2]).toFixed(2) === parseFloat(mode.delta).toFixed(2)
                            ) {
                                matched++;
                            }
                        }
                    }

                    if (matched === 4) {
                        this.stateOn();
                    }

                });
        });
    }

    createTable(data) {
        let rows = this.createRows(data);

        let template =
            `<table id="tableModes" class="bordered striped responsive-table">
                <thead>
                    <tr><th>nom</th><th>consigne</th><th>delta</th></tr>
                </thead>
                <tbody>              
                ${rows}              
                </tbody>
                </table>`;

        document.querySelector("#sync-mode-card .card-content").innerHTML = template;
    }

    createRow(rowData) {
        let row = '';
        for (const item of rowData) {
            row += `<td>${item}</td>`;
        }

        return row;
    }

    createRows(data) {
        let rows = '';
        for (const row of data) {
            rows += '<tr>' + this.createRow(row) + '</tr>';
        }

        return rows;
    }

    checkState() {
        return fetch('api/thermostat/mode/')
            .then((response) => {
                return response.json();
            })
            .catch(err => console.error(err));

    }

    stateOn() {
        let selector = document.querySelector("#sync-mode-card #check-modes");
        selector.classList.remove('red');
        selector.classList.remove('red-text');
        selector.classList.add('teal');
        selector.classList.add('teal-text');
    }

    stateOff() {
        let selector = document.querySelector("#sync-mode-card #check-modes");
        selector.classList.remove('teal');
        selector.classList.remove('teal-text');
        selector.classList.add('red');
        selector.classList.add('red-text');
    }
}
