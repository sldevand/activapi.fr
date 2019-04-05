import {ScenarioTemplate} from "./templates/scenario-template";
import {SequenceRowTemplate} from "./templates/sequence-select-template";

export class Scenarios {
    init() {
        const scenarioId = document.querySelector('#scenarioid').getAttribute('value');
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
                this.sequences = sequences;
                this.initSequenceAddListener();
            })
            .catch(err => console.log(err))
    }

    addRow() {
        const sequences = document.querySelector('#sequences');
        const elt = document.createElement('div');
        elt.classList.add('row');
        elt.id = 'delete-button';
        let row = SequenceRowTemplate.render(this.scenario, this.sequences);
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
        const sequences = document.querySelector('#sequences');
        const elt = document.createElement('input');
        elt.setAttribute('value', itemId);
        elt.setAttribute('name', 'actionneurs[deleteId][]');
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

    initRemoveButton(deleteButton) {
        deleteButton.addEventListener('click', (e) => {
            e.preventDefault();
            this.removeRow(e.target.parentNode);
            this.addDeletionInput(e.target.parentNode.dataset.sequenceid);
        });
    }

    createScenarioTemplate() {
        return ScenarioTemplate.render(this.scenario);
    }
}
