import {ThermostatPlanifTemplate} from "./templates/thermostat-planif-template";
import {ApiManage} from "../utils/apiManage";

export class ThermostatPlanif {
    init() {
        const nomid = document.querySelector('#thermostat-planif-nomid').getAttribute('value');
        const jour = document.querySelector('#thermostat-planif-jour').getAttribute('value');
        return fetch(`api/thermostat/planif/${nomid}/${jour}`)
            .then((data) => {
                return data.json();
            }).then((json) => {
                let timetable = JSON.parse(json.timetable);
                let newTimetable = timetable.map(element => {
                    let minuteModeId = element.split('-');
                    return {hour:this.minuteToHour(minuteModeId[0]), modeId:minuteModeId[1]};
                });

                json.timetable = newTimetable;

                return json;
            }).then((themostatPlanif) => {
                document.querySelector('#thermostat-planif-content').innerHTML = this.createTemplate(themostatPlanif);
            })
            .catch(err => console.log(err))
    }

    minuteToHour(minute) {
        let hourPart = String(Math.floor(minute/60)).padStart(2,'0');
        let minutePart = String(minute % 60).padEnd(2,'0');

        return `${hourPart}:${minutePart}`;
    }

    // addRow(scenarioSequenceId = null, selectedSequenceId = null) {
    //     const content = document.querySelector('#thermostat-planif-content');
    //     const elt = document.createElement('div');
    //     elt.classList.add('row');
    //     elt.id = 'sequence-row-' + scenarioSequenceId;
    //     let row = SequenceRowTemplate.render(this.scenario, this.sequences, scenarioSequenceId, selectedSequenceId);
    //     if (!row) {
    //         return;
    //     }
    //     elt.innerHTML = row;
    //     sequences.appendChild(elt);
    //     $('select').material_select();
    //     this.initRemoveButton(scenarioSequenceId);
    // }

    // removeRow(target) {
    //     target.remove();
    // }

    // addDeletionInput(itemId) {
    //     const sequences = document.querySelector('#scenario-content #sequences');
    //     const elt = document.createElement('input');
    //     elt.setAttribute('value', itemId);
    //     elt.setAttribute('name', 'deleted-scenarioSequence-' + itemId);
    //     elt.hidden = true;
    //     sequences.appendChild(elt);
    // }

    // initSequenceAddListener() {
    //     const sequenceAdd = document.querySelector('#sequence-add');
    //     sequenceAdd.addEventListener('click', (e) => {
    //         e.preventDefault();
    //         this.addRow();
    //     });
    // }

    // initRemoveButton(domId) {
    //     const deleteButton = document.querySelector('#scenario-sequence-delete-' + domId);
    //     deleteButton.addEventListener('click', (e) => {
    //         e.preventDefault();
    //         this.removeRow(e.target.parentNode);
    //         if (e.target.dataset.id !== 'null') {
    //             this.addDeletionInput(e.target.dataset.id);
    //         }
    //     });
    // }

    createTemplate(themostatPlanif) {
        return ThermostatPlanifTemplate.render(themostatPlanif);
    }

    // initForm() {
    //     let form = document.forms[0];
    //     form.addEventListener('submit', (e) => {
    //         e.preventDefault();
    //         document.getElementById('submit').disabled = true;
    //         let apiManage = new ApiManage(form.getAttribute('method'), form.getAttribute('action'));

    //         let formData = new FormData(form);
    //         let object = {};
    //         object.scenarioSequences = [];
    //         object.deletedScenarioSequences = [];
    //         formData.forEach((value, key) => {
    //             if (key.startsWith('sequence-')) {
    //                 let scenarioSequenceId = key.split('-')[1];
    //                 object.scenarioSequences.push({"id": scenarioSequenceId, "sequenceId": value});
    //             } else if (key.startsWith('deleted-scenarioSequence-')) {
    //                 object.deletedScenarioSequences.push(value);
    //             } else {
    //                 object[key] = value;
    //             }
    //         });
    //         apiManage.sendObject(JSON.stringify(object), (request) => {
    //             this.responseManagement(request)
    //         });
    //     });
    // }

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
    //         default :
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
