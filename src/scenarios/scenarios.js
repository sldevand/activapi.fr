import {SequenceRowTemplate} from './sequenceRow';

export class Scenarios {

    init() {
        fetch('api/actionneurs/')
            .then((data) => {
                return data.json();
            })
            .then((actionneurs) => {
                this.actionneurs = actionneurs;

                const scenario = document.querySelector('#scenarioid');
                const scenarioid = scenario.getAttribute('value');

                return fetch('api/scenarios/' + scenarioid);
            })
            .then((data) => {
                return data.json();
            })
            .then((scenarios) => {
                console.log(scenarios);
                this.scenarios = scenarios;
                //  this.addRows();
                this.initSequenceAddListener();
                const deleteButtons = document.getElementsByClassName('delete');
                for (let deleteButton of deleteButtons) {
                    this.initRemoveButton(deleteButton);
                }
            })
            .catch(err => console.log(err))
    }

    addRows() {
        for (let scenario in this.scenarios) {
            this.addRow(scenario);
        }
    }

    addRow(scenario=null) {
        const sequences = document.querySelector('#sequences');
        const elt = document.createElement('div');
        elt.classList.add('row');
        elt.id = 'delete-button';
        elt.innerHTML = this.createRow(scenario);
        sequences.appendChild(elt);
        $('select').material_select();

        this.initRemoveButton(elt.id);
    }

    createRow(scenario=null) {
        return SequenceRowTemplate.render(this.actionneurs, scenario);
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
            this.addRow();
        });
    }

    initRemoveButton(deleteButton) {

        deleteButton.addEventListener('click', (e) => {
            this.removeRow(e.target.parentNode);
            this.addDeletionInput(e.target.parentNode.dataset.sequenceid);
        });
    }
}
