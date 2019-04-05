import {Scenarios} from './scenarios/scenario-component';

$(document).ready(function () {
    $('select').material_select();
});

let scenarios = new Scenarios();
scenarios.init();
