import { ThermostatPlanifRowTemplate } from "./templates/thermostat-planif-row-template";
import { ApiManage } from "../utils/apiManage";

export class ThermostatPlanif {
    constructor() {
        this.planifContent = document.querySelector('#thermostat-planif-content');
        this.lastIndex = 0;
    }

    async init() {
        const nomid = document.querySelector('#thermostat-planif-nomid').getAttribute('value');
        const jour = document.querySelector('#thermostat-planif-jour').getAttribute('value');
        try {
            const data = await fetch(`api/thermostat/planif/${nomid}/${jour}`);
            const json = await data.json();
            let timetable = JSON.parse(json.timetable);
            let newTimetable = timetable.map(element => {
                let minuteModeId = element.split('-');
                return { hour: this.minuteToHour(minuteModeId[0]), modeId: minuteModeId[1] };
            });

            json.timetable = newTimetable;
            const themostatPlanif = json;
            let planifRows = this.initPlanifRows(themostatPlanif);
            this.planifContent.innerHTML = '';
            this.planifContent.append(planifRows);
            this.initAddEvent();
            this.initDeleteEvents();
            this.refreshMaterializeElements();
            return this.initForm();
        } catch (err) {
            return console.log(err);
        }
    }

    initAddEvent() {
        document.querySelector('#planif-add').addEventListener('click', (event) => {
            event.preventDefault();
            if (document.querySelectorAll('.thermostat-planif-row').length > 5) {
                return;
            }
            this.lastIndex++;
            this.planifContent.append(this.createThermostatPlanifRow(null, this.lastIndex));
            this.initDeleteEvents();
            this.refreshMaterializeElements();
        });
    }

    refreshMaterializeElements() {
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
    }

    initDeleteEvents() {
        document.querySelectorAll('.thermostat-planif-row-delete').forEach((button) => {
            button.removeEventListener('click', this.removeRow);
            button.addEventListener('click', this.removeRow);
        });
    }

    removeRow(event) {
        event.currentTarget.parentNode.parentNode.remove();
    }

    minuteToHour(minute) {
        let hourPart = String(Math.floor(minute / 60)).padStart(2, '0');
        let minutePart = String(minute % 60).padEnd(2, '0');

        return `${hourPart}:${minutePart}`;
    }

    hourToMinute(hour) {
        let hourParts = hour.split(':');
        return parseInt(hourParts[0]) * 60 + parseInt(hourParts[1]);
    }

    initPlanifRows(thermostatPlanif) {
        const fragment = new DocumentFragment();
        thermostatPlanif.timetable.forEach((minuteModeId, index) => {
            fragment.append(this.createThermostatPlanifRow(minuteModeId, index));
            this.lastIndex = index;
        });

        return fragment;
    }

    createThermostatPlanifRow(minuteModeId, index) {
        let thermostatPlanifRowTemplate = new ThermostatPlanifRowTemplate();
        return thermostatPlanifRowTemplate.render(minuteModeId, index);
    }

    initForm() {
        let form = document.forms[0];
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            document.getElementById('submit').disabled = true;
            let apiManage = new ApiManage(form.getAttribute('method'), form.getAttribute('action'));

            const fieldsets = form.querySelectorAll('fieldset')
            let timetable = [];
            if (fieldsets.length) {

                fieldsets.forEach(fieldset => {
                    let timeValue = fieldset.querySelector(".thermostat-planif-time").value;
                    let minute = this.hourToMinute(timeValue);
                    let modeIdValue = fieldset.querySelector(".thermostat-planif-modeId > select").value;
                    let planifRowValue = `${minute}-${modeIdValue}`;
                    timetable.push(planifRowValue);
                    fieldset.setAttribute('disabled', true)
                })
            }

            let id = form.querySelector('#thermostat-planif-id').value;
            let nomid = form.querySelector('#thermostat-planif-nomid').value;
            let jour = form.querySelector('#thermostat-planif-jour').value;
            let payload = {
                id: id,
                nomid:nomid,
                jour: jour,
                timetable: JSON.stringify(timetable)
            };
            apiManage.sendObject(JSON.stringify(payload), (request) => {
                this.responseManagement(request)
            });
        });
    }

    responseManagement(request) {
        let jsonResponse = JSON.parse(request.response);
        this.dispatchResponse(request.status, jsonResponse);
    }

    dispatchResponse(status, jsonResponse) {
        let crudOperation = '';
        switch (status) {
            case 202:
                crudOperation = "updated";
                break;
            case 201:
                crudOperation = "created";
                break;
            case 204:
                crudOperation = "deleted";
                break;
            default:
                return Materialize.toast(jsonResponse['error'], 2000);
        }

        return this.makeToast(jsonResponse, crudOperation);
    }

    makeToast(jsonResponse, crudOperation) {
        return Materialize.toast(
            jsonResponse.id + " " + crudOperation,
            700,
            '',
            () => {
                window.location.replace('thermostat-planif');
            }
        );
    }
}
