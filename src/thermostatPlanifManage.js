import { ThermostatPlanif } from './thermostatPlanif/thermostat-planif-component';

let thermostatPlanif = new ThermostatPlanif();
thermostatPlanif.init().then(() => {
    $('select').material_select();

    let timePicker = $(".timepicker");

    timePicker.pickatime({
        default: 'now',
        fromnow: 0,
        twelvehour: false,
        donetext: 'OK',
        cleartext: 'Effacer',
        canceltext: 'Annuler',
        autoclose: false,
        ampmclickable: false,
        aftershow: function () {
        }
    });

    timePicker.on('mousedown', function (event) {
        event.preventDefault();
    })
});
