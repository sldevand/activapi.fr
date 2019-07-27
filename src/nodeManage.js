import {NodeServer} from "./utils/nodeServer";
import {Config} from "./config/config";

let ip = Config.getConfig().ip;
let nodeServer = new NodeServer('http://' + ip + '/activapi.fr/api');
nodeServer.init();