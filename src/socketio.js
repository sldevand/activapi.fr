import { SocketIOManage } from './utils/socketioManage';
import io from 'socket.io-client/dist/socket.io';
import { Config } from './config/config';


const ip = Config.getConfig().ip;
const port = Config.getConfig().port;

const socketIOManage = new SocketIOManage(io, ip, port);
socketIOManage.connect().run();

window.addEventListener('io-connect', () => {
    handleIoConnectionChip(true);
});
window.addEventListener('io-disconnect', () => {
    handleIoConnectionChip(false);
});

function handleIoConnectionChip(connected) {
    let colorRemove = connected ? 'red' : 'teal';
    let colorAdd = connected ? 'teal' : 'red';
    let ioConnection = document.querySelector('#ioconnection');
    ioConnection.classList.remove(colorRemove);
    ioConnection.classList.remove(`${colorRemove}-text`);
    ioConnection.classList.add(colorAdd);
    ioConnection.classList.add(`${colorAdd}-text`);
}

export default socketIOManage;
