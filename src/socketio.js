import {SocketIOManage} from "./utils/socketioManage";
import io from 'socket.io-client/dist/socket.io';

const address = '192.168.1.52';
const port = 5901;

const socketIOManage = new SocketIOManage(io, address, port);
socketIOManage.connect().run();