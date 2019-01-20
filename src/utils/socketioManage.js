export class SocketIOManage {

    constructor(io, address, port) {
        this.io = io;
        this.fullAddress = 'http://' + address + ':' + port;
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
        const ioconnectionId = $('#ioconnection');
        this.socket.on('messageConsole', message => {
            $('#console-display').prepend(message + "<br>");
        });

        this.socket.on('connect', message => {
            ioconnectionId.removeClass("red").removeClass("red-text");
            ioconnectionId.addClass("teal").addClass("teal-text");
        });

        this.socket.on('disconnect', message => {
            ioconnectionId.removeClass("teal").removeClass("teal-text");
            ioconnectionId.addClass("red").addClass("red-text");
        });

        return this;
    }

    initSend() {
        $("#send-command").click((e) => {
            e.preventDefault();
            const command = $("#console-edit-text").val();

            if ("" === command || null === command) {
                return false;
            }

            this.socket.emit("command", command);
        });

        return this;
    }
}
