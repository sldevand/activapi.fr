import {ActionTemplate} from "./templates/action-template";
import {ActionneurRowTemplate} from "./templates/actionneur-select-template";
import {ApiManage} from "../utils/apiManage";

export class Actions {
    init() {
        const actionId = document.querySelector('#id').getAttribute('value');
        fetch("api/actions/" + actionId)
            .then((data) => {
                return data.json();
            })
            .then((action) => {
                this.action = action;
                document.querySelector('#action-content').innerHTML = this.createActionTemplate();
                return fetch('api/actionneurs/');
            })
            .then((data) => {
                return data.json();
            })
            .then((actionneurs) => {
                if (actionneurs.error) {
                    Materialize.toast(actionneurs.error, 2000);
                    return;
                }
                this.actionneurs = actionneurs;
                this.addRow(this.action.actionneurId);
                this.initForm();
            })
            .catch(err => console.log(err))
    }

    addRow(actionneurId = null) {
        const actionneurs = document.querySelector('#action-content #actionneur');
        const elt = document.createElement('div');
        elt.classList.add('row');
        elt.id = 'actionneur-row-' + actionneurId;
        let row = ActionneurRowTemplate.render(this.action, this.actionneurs, actionneurId);
        if (!row) {
            return;
        }
        elt.innerHTML = row;
        actionneurs.appendChild(elt);
        $('select').material_select();
    }

    createActionTemplate() {
        return ActionTemplate.render(this.action);
    }

    initForm() {
        let form = document.forms[0];
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            document.getElementById('submit').disabled = true;
            let apiManage = new ApiManage(form.getAttribute('method'), form.getAttribute('action'));

            let formData = new FormData(form);
            let object = {};
            formData.forEach((value, key) => {
                object[key] = value;
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
                window.location.replace('actions');
            }
        );
    }
}
