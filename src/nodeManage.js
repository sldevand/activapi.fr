import {NodeServer} from "./utils/nodeServer";
import {Config} from "./config/config";

let ip = Config.getConfig().ip;
let apiEndpoint = Config.getConfig().apiEndpoint;
let nodeServer = new NodeServer('http://' + ip + '/' + apiEndpoint);
nodeServer.init();