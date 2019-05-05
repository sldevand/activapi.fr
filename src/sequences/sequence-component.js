import {SequenceTemplate} from "./templates/sequence-template";
import {ActionRowTemplate} from "./templates/actions-select-template";
import {ApiManage} from "../utils/apiManage";

export class Sequences {
    init() {
        const sequenceId = document.querySelector('#id').getAttribute('value');
        fetch("api/sequences/" + sequenceId)
            .then((data) => {
                return data.json();
            })
            .then((sequence) => {
                this.sequence = sequence;
                document.querySelector('#sequence-content').innerHTML = this.createSequenceTemplate();
                return fetch('api/actions/');
            })
            .then((data) => {
                return data.json();
            })
            .then((actions) => {
                if (actions.error) {
                    Materialize.toast(actions.error, 2000);
                    return;
                }
                this.actions = actions;
                for (let sequenceActionId in this.sequence.actions) {
                    let actionId = this.sequence.actions[sequenceActionId].id;

                    this.addRow(sequenceActionId, actionId);
                }
                this.initActionAddListener();
                this.initForm();
            })
            .catch(err => console.log(err))
    }

    addRow(sequenceActionId = null, selectedActionId = null) {
        const actions = document.querySelector('#actions');
        const elt = document.createElement('div');
        elt.classList.add('row');
        elt.id = 'action-row-' + sequenceActionId;
        let row = ActionRowTemplate.render(this.sequence, this.actions, sequenceActionId, selectedActionId);
        if (!row) {
            return;
        }
        elt.innerHTML = row;
        actions.appendChild(elt);
        $('select').material_select();
        this.initRemoveButton(elt.id);
    }

    removeRow(target) {
        target.remove();
    }

    addDeletionInput(itemId) {
        const actions = document.querySelector('#actions');
        const elt = document.createElement('input');
        elt.setAttribute('value', itemId);
        elt.setAttribute('name', 'deleted-sequenceAction-' + itemId);
        elt.hidden = true;
        actions.appendChild(elt);
    }

    initActionAddListener() {
        const actionAdd = document.querySelector('#action-add');
        actionAdd.addEventListener('click', (e) => {
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

    createSequenceTemplate() {
        return SequenceTemplate.render(this.sequence);
    }

    initForm() {
        let form = document.forms[0];
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            document.getElementById('submit').disabled = true;
            let apiManage = new ApiManage(form.getAttribute('method'), form.getAttribute('action'));

            let formData = new FormData(form);
            let object = {};
            object.sequenceActions = [];
            object.deletedSequenceActions = [];
            formData.forEach((value, key) => {
                if (key.startsWith('action-')) {
                    let sequenceActionId = key.split('-')[1];
                    object.sequenceActions.push({"id": sequenceActionId, "actionId": value});
                } else if (key.startsWith('deleted-sequenceAction-')) {
                    object.deletedSequenceActions.push(value);
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
                //window.location.replace('sequences');
            }
        );
    }
}
