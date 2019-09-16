import {Console} from "./console/console-component";
import {Config} from "./config/config";

let ip = Config.getConfig().ip;
let console = new Console('http://' + ip + '/activapi.fr/api');
console.init();