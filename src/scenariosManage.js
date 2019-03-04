import {Scenarios} from './scenarios/scenarios';

$(document).ready(function () {
    $('select').material_select();
});

let scenarios = new Scenarios();
scenarios.init();


