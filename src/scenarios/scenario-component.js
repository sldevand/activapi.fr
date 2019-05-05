import {ScenarioTemplate} from "./templates/scenario-template";
import {SequenceRowTemplate} from "./templates/sequence-select-template";
import {ApiManage} from "../utils/apiManage";

export class Scenarios {
    init() {
        const scenarioId = document.querySelector('#id').getAttribute('value');
        fetch("api/scenarios/" + scenarioId)
            .then((data) => {
                return data.json();
            })
            .then((scenario) => {
                this.scenario = scenario;
                document.querySelector('#scenario-content').innerHTML = this.createScenarioTemplate();
                return fetch('api/sequences/');
            })
            .then((data) => {
                return data.json();
            })
            .then((sequences) => {
                if (sequences.error) {
                    Materialize.toast(sequences.error, 2000);
                    return;
                }
                this.sequences = sequences;
                for (let scenarioSequenceId in this.scenario.sequences) {
                    let sequenceId = this.scenario.sequences[scenarioSequenceId].id;
                    this.addRow(scenarioSequenceId, sequenceId);
                }
                this.initSequenceAddListener();
                this.initForm();
            })
            .catch(err => console.log(err))
    }

    addRow(scenarioSequenceId = null, selectedSequenceId = null) {
        const sequences = document.querySelector('#scenario-content #sequences');
        const elt = document.createElement('div');
        elt.classList.add('row');
        elt.id = 'sequence-row-' + scenarioSequenceId;
        let row = SequenceRowTemplate.render(this.scenario, this.sequences, scenarioSequenceId, selectedSequenceId);
        if (!row) {
            return;
        }
        elt.innerHTML = row;
        sequences.appendChild(elt);
        $('select').material_select();
        this.initRemoveButton(elt.id);
    }

    removeRow(target) {
        target.remove();
    }

    addDeletionInput(itemId) {
        const sequences = document.querySelector('#scenario-content #sequences');
        const elt = document.createElement('input');
        elt.setAttribute('value', itemId);
        elt.setAttribute('name', 'deleted-scenarioSequence-' + itemId);
        elt.hidden = true;
        sequences.appendChild(elt);
    }

    initSequenceAddListener() {
        const sequenceAdd = document.querySelector('#sequence-add');
        sequenceAdd.addEventListener('click', (e) => {
            e.preventDefault();
            this.addRow();
        });
    }

    initRemoveButton(domId) {
        const deleteButton = document.querySelector('#' + domId);
        deleteButton.addEventListener('click', (e) => {
            e.preventDefault();
            this.removeRow(e.target.parentNode);
            if (e.target.dataset.id !== 'null') {
                this.addDeletionInput(e.target.dataset.id);
            }
        });
    }

    createScenarioTemplate() {
        return ScenarioTemplate.render(this.scenario);
    }

    initForm() {
        let form = document.forms[0];
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            document.getElementById('submit').disabled = true;
            let apiManage = new ApiManage(form.getAttribute('method'), form.getAttribute('action'));

            let formData = new FormData(form);
            let object = {};
            object.scenarioSequences = [];
            object.deletedScenarioSequences = [];
            formData.forEach((value, key) => {
                if (key.startsWith('sequence-')) {
                    let scenarioSequenceId = key.split('-')[1];
                    object.scenarioSequences.push({"id": scenarioSequenceId, "sequenceId": value});
                } else if (key.startsWith('deleted-scenarioSequence-')) {
                    object.deletedScenarioSequences.push(value);
                } else {
                    object[key] = value;
                }
            });
            apiManage.sendObject(JSON.stringify(object), (request) => {
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
            default :
                return Materialize.toast(jsonResponse['error'], 2000);
        }

        return this.makeToast(jsonResponse, crudOperation);
    }

    makeToast(jsonResponse, crudOperation) {
        return Materialize.toast(
            jsonResponse.nom + " " + crudOperation,
            700,
            '',
            () => {
                window.location.replace('scenarios');
            }
        );
    }
}
