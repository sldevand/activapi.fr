import socketIOManage from '../socketio.js'

export class ThermostatRtc {
    init() {
        socketIOManage.socket.on("messageConsole", (data) => {
            let messageArray = data.split(" ");
            if (messageArray[1] !== "therclock") {
                return;
            }
            this.refreshRtcTimeView(messageArray);
            this.initRtcSyncClickListener();
        });
    }

    refreshRtcTimeView(messageArray) {
        if (!document.querySelector('#rtc-time')) {
            let rtcView = document.querySelector('#rtc-view');
            rtcView.innerHTML = '';
            rtcView.append(document.querySelector('#rtc-view-layout').content.cloneNode(true));
        }
        delete messageArray[0];
        delete messageArray[1];
        document.querySelector('#rtc-time').innerText = messageArray.join(' ');
    }

    askRtc() {
        let command = 'nrf24/node/2Nodw/ther/get/rtc/';
        socketIOManage.socket.emit("command", command);
    }

    initRtcSyncClickListener() {
        let rtcSync = document.querySelector("#rtc-sync");
        if(!!rtcSync.onclick) {
            return;
        }
        rtcSync.addEventListener('click', this.rtcSyncListener);
    }

    rtcSyncListener() {
        socketIOManage.socket.emit("updateTherClock", "sync");
    }
}
