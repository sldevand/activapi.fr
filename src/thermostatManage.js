import { ThermostatRtc } from './thermostat/thermostat-rtc-component';

let thermostatRtc = new ThermostatRtc();
thermostatRtc.init();
setTimeout(() => {
    thermostatRtc.askRtc();
}, 400);
