import {Actions} from './actions/action-component';

$(document).ready(function () {
    $('select').material_select();
});

let actions = new Actions();
actions.init();
