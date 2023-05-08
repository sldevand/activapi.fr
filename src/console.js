import {Console} from "./console/console-component";
import {Config} from "./config/config";

let ip = Config.getConfig().ip;
let apiEndpoint = Config.getConfig().apiEndpoint;
let console = new Console('http://' + ip + '/' + apiEndpoint);
console.init();
