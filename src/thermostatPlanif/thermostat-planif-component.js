import { ThermostatPlanifTemplate } from "./templates/thermostat-planif-template";
import { ApiManage } from "../utils/apiManage";

export class ThermostatPlanif {
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
            document.querySelector('#thermostat-planif-content').replaceWith(this.createTemplate(themostatPlanif));
            return this.initForm();
        } catch (err) {
            return console.log(err);
        }
    }

    minuteToHour(minute) {
        let hourPart = String(Math.floor(minute / 60)).padStart(2, '0');
        let minutePart = String(minute % 60).padEnd(2, '0');

        return `${hourPart}:${minutePart}`;
    }

    createTemplate(themostatPlanif) {
        let thermostatPlanifTemplate = new ThermostatPlanifTemplate();
        return thermostatPlanifTemplate.render(themostatPlanif);
    }

    initForm() {
        let form = document.forms[0];
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            document.getElementById('submit').disabled = true;
            let apiManage = new ApiManage(form.getAttribute('method'), form.getAttribute('action'));

            let formData = new FormData(form);
            debugger
            // let object = {};
            // object.scenarioSequences = [];
            // object.deletedScenarioSequences = [];
            // formData.forEach((value, key) => {
            //     if (key.startsWith('sequence-')) {
            //         let scenarioSequenceId = key.split('-')[1];
            //         object.scenarioSequences.push({"id": scenarioSequenceId, "sequenceId": value});
            //     } else if (key.startsWith('deleted-scenarioSequence-')) {
            //         object.deletedScenarioSequences.push(value);
            //     } else {
            //         object[key] = value;
            //     }
            // });
            // apiManage.sendObject(JSON.stringify(object), (request) => {
            //     this.responseManagement(request)
            // });
        });
    }

    // responseManagement(request) {
    //     let jsonResponse = JSON.parse(request.response);
    //     this.dispatchResponse(request.status, jsonResponse);
    // }

    // dispatchResponse(status, jsonResponse) {
    //     let crudOperation = '';
    //     switch (status) {
    //         case 202:
    //             crudOperation = "updated";
    //             break;
    //         case 201:
    //             crudOperation = "created";
    //             break;
    //         case 204:
    //             crudOperation = "deleted";
    //             break;
    //         default:
    //             return Materialize.toast(jsonResponse['error'], 2000);
    //     }

    //     return this.makeToast(jsonResponse, crudOperation);
    // }

    // makeToast(jsonResponse, crudOperation) {
    //     return Materialize.toast(
    //         jsonResponse.nom + " " + crudOperation,
    //         700,
    //         '',
    //         () => {
    //             window.location.replace('scenarios');
    //         }
    //     );
    // }
}
