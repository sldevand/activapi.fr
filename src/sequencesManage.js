import {Sequences} from './sequences/sequence-component';

$(document).ready(function () {
    $('select').material_select();
});

let sequences = new Sequences();
sequences.init();
