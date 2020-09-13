export class NodeServer {
    constructor(address, socket) {
        this.nodeAddress = address + '/node';
        this.toggleAddress = this.nodeAddress + '/toggle';
        this.switch = document.getElementById('node');
        this.serialPortReset = document.getElementById('serialport-reset');
        this.socket = socket;
        this.isInitialized = false;
    }

    init() {
        if (!this.isInitialized) {
            this.addEvents();
            this.status();
            setInterval(() => this.status(), 2000);
            this.isInitialized = true;
        }
    }

    addEvents() {
        var nodeServer = this;
        this.switch.addEventListener('click', (event) => {
            let status = nodeServer.getSwitchStatus(event);
            this.toggle(status);
        });
        this.serialPortReset.addEventListener('click', (event) => {
            this.resetSerialPort();
        });
    }

    toggle(status) {
        fetch(this.toggleAddress + '/' + status)
            .then((res) => {
                return res.json();
            })
            .then((status) => {
                console.log(status);
            })
            .catch((err) => {
                console.error(err);
            });
    }

    status() {
        fetch(this.nodeAddress + '/status')
            .then((res) => {
                return res.json();
            })
            .then((status) => {
                this.setSwitchStatus(status);
            })
            .catch((err) => {
                console.error(err)
            });
    }

    getSwitchStatus(event) {
        return event.currentTarget.checked ? 'on' : 'off';
    }

    setSwitchStatus(status) {
        this.switch.checked = (status === 'on') ? this.switch.checked = true : this.switch.checked = false;
    }

    resetSerialPort() {
        this.socket.emit("serialportReset", '');
    }
}
