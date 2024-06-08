import './virtuscroll/virtual-scroll'
import { Config } from "./config/config";
import { default as VirtualScrollRow } from './virtuscroll/virtual-scroll-row';

let ip = Config.getConfig().ip;
let apiEndpoint = Config.getConfig().apiEndpoint;
const period = document.querySelector('#period').value;
let apiUrl = `http://${ip}/${apiEndpoint}/node/log/${period}`;

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
    .catch(err => console.log(err))
