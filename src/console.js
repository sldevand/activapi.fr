import './virtuscroll/virtual-scroll'
import { Config } from "./config/config";
import { default as VirtualScrollRow } from './virtuscroll/virtual-scroll-row';

let ip = Config.getConfig().ip;
let apiEndpoint = Config.getConfig().apiEndpoint;
const period = document.querySelector('#period').value;
let apiUrl = `http://${ip}/${apiEndpoint}/node/log/${period}`;

/* Console Display */
let consoleDisplayContainer = document.querySelector("#console-display-container");
let consoleDisplay = document.querySelector('#console-display');
consoleDisplay.initCreateRowCallback((rowData) => {
    const cssStyle = `
        <style>
            :host {
                display: grid;
                grid-template-columns: 1fr 3fr;
                grid-auto-rows: auto;
                text-align:left;
            }
        </style>
        `
    return new VirtualScrollRow(rowData, cssStyle);
});
consoleDisplay.setViewportHeight(consoleDisplayContainer.offsetHeight);

let ticking = false;
consoleDisplayContainer.addEventListener("scroll", (event) => {
    if (!ticking) {
        window.requestAnimationFrame(() => {
            consoleDisplay.render(event.target.scrollTop);
            ticking = false;
        });

        ticking = true;
    }
});

fetch(apiUrl)
    .then((data) => {
        return data.json();
    })
    .then((logs) => {
        let messages = logs.messages.map((message) => {
            let date = new Date();
            date.setTime(parseInt(message.createdAt) * 1000);
            return {
                date: date.toLocaleTimeString("fr-FR", { timeZone: "Europe/Paris" }),
                content: message.content
            };
        });

        consoleDisplay.setData(messages);
    })
    .catch(err => console.log(err));

/* Socket IO Stuff */
document.getElementById('serialport-reset').addEventListener('click', onSerialPortResetClick);
window.addEventListener('io-messageConsole', onMessageConsole);
document.getElementById('send-command').addEventListener('click', onSendCommandClick);

function onSendCommandClick(event) {
    event.preventDefault();
    const command = $("#console-edit-text").val();
    if ("" === command || null === command) {
        return false;
    }

    const ioSendCommandEvent = new CustomEvent("io-sendCommand", {
        bubbles: true,
        detail: command
    });
    window.dispatchEvent(ioSendCommandEvent);
}

function onSerialPortResetClick() {
    window.dispatchEvent(new Event('io-serialportReset'));
}

function onMessageConsole(event) {
    let message = event.detail;
    const date = message.substring(0, message.indexOf(" "));
    const content = message.substring(message.indexOf(' ') + 1);
    let row = {
        date: date,
        content: content
    };
    document.getElementById('console-display').prependRow(row);
}
