import {SocketIOManage} from "./utils/socketioManage";
import io from 'socket.io-client/dist/socket.io';

const address = 'localhost';
const port = 5901;

const socketIOManage = new SocketIOManage(io, address, port);
socketIOManage.connect().run();

export default socketIOManage;