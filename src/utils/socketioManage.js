export class SocketIOManage {
    constructor(io, address, port) {
        this.io = io;
        this.fullAddress = `http://${address}:${port}`;
        this.socket = null;
    }

    connect() {
        this.socket = this.io.connect(this.fullAddress);

        return this;
    }

    run() {
        if (!this.socket) {
            return false;
        }
        this.initReceive();
        this.initSend();
    }

    initReceive() {
        this.socket.on('connect', () => {
            window.dispatchEvent(new Event("io-connect"));
        });

        this.socket.on('disconnect', () => {
            window.dispatchEvent(new Event("io-disconnect"));
        });

        this.socket.on('messageConsole', message => {
            const event = new CustomEvent("io-messageConsole", {
                bubbles: true,
                detail: message
            });
            window.dispatchEvent(event);
        });

        return this;
    }

    initSend() {
        window.addEventListener('io-sendCommand', (e) => {
            this.socket.emit('command', e.detail);
        });

        window.addEventListener('io-serialportReset', () => {
            this.socket.emit('serialportReset', '');
        });

        return this;
    }
}
