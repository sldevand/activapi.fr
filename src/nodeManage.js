import {NodeServer} from "./utils/nodeServer";
import {Config} from "./config/config";
import socketIOManage from "./socketio";

let ip = Config.getConfig().ip;
let apiEndpoint = Config.getConfig().apiEndpoint;
let nodeServer = new NodeServer('http://' + ip + '/' + apiEndpoint, socketIOManage.socket);
nodeServer.init();