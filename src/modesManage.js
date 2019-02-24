import {Modes} from './modes/modes';

const modes = new Modes();

modes.init();
setTimeout(() => {
    modes.askThermostat();
    modes.checkState();
}, 200);


