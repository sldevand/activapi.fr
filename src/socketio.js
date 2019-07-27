import {SocketIOManage} from "./utils/socketioManage";
import io from 'socket.io-client/dist/socket.io';
import {Config} from "./config/config";


const ip = Config.getConfig().ip;
const port = Config.getConfig().port;

const socketIOManage = new SocketIOManage(io, ip, port);
socketIOManage.connect().run();

export default socketIOManage;