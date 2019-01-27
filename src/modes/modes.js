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

            if (this.dataModes.length === 4) {
                this.createTable(this.dataModes);
            }
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
}
