import socketIOManage from '../socketio.js'

export class Modes {

    constructor() {
    }

    askThermostat() {
        let command = 'nrf24/node/2Nodw/ther/get/mode/254/';
        socketIOManage.socket.emit("command", command);
    }

    init() {
        let dataModes = [];

        this.initStateClickListener();
        this.initSynchronizeClickListener();

        socketIOManage.socket.on("messageConsole", (data) => {
            let messageTab = data.split(" ");
            if (
                messageTab[1] !== "thermode"
                || dataModes.length >= 4
            ) {
                return;
            }

            dataModes.push(messageTab.slice(2));

            if (dataModes.length !== 4) {
                return;
            }

            this.createTable(dataModes);
            this.checkState()
                .then((modes) => {
                    let matched = 0;
                    for (let mode of modes) {
                        for (let dataMode of dataModes) {

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
                    }else {
                        this.stateOff();
                    }
                    dataModes = [];

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
        let syncModes = document.querySelector("#sync-mode-card #sync-modes");
        selector.classList.remove('red');
        selector.classList.remove('red-text');
        selector.classList.add('teal');
        selector.classList.add('teal-text');
        syncModes.style.display = "none";
    }

    stateOff() {
        let selector = document.querySelector("#sync-mode-card #check-modes");
        let syncModes = document.querySelector("#sync-mode-card #sync-modes");
        selector.classList.remove('teal');
        selector.classList.remove('teal-text');
        selector.classList.add('red');
        selector.classList.add('red-text');
        syncModes.style.display = "block";

    }

    initStateClickListener() {
        let selector = document.querySelector("#sync-mode-card #check-modes");
        selector.addEventListener("click", (e) => {
            this.askThermostat();
        });
    }

    initSynchronizeClickListener(){
        let syncModes = document.querySelector("#sync-mode-card #sync-modes");
        syncModes.addEventListener('click',(e)=>{
            socketIOManage.socket.emit("syncTherModes", "sync");
        });

    }
}
