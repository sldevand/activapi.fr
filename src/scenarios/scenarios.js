import {SequenceRowTemplate} from './sequenceRow';

export class Scenarios {

    init() {
        fetch('api/actionneurs/')
            .then((data) => {
                return data.json();
            })
            .then((actionneurs) => {
                this.actionneurs = actionneurs;
            })
            .then(() => {
                this.initSequenceAddListener();
                const deleteButtons = document.getElementsByClassName('delete');
                for (let deleteButton of deleteButtons) {
                    this.initRemoveButton(deleteButton)
                }
            })
            .catch(err => console.log(err))
    }

    addRow() {
        const sequences = document.querySelector('#sequences');
        const elt = document.createElement('div');
        elt.classList.add('row');
        elt.id = 'delete-button';
        elt.innerHTML = this.createRow();
        sequences.appendChild(elt);
        $('select').material_select();

        this.initRemoveButton(elt.id);
    }

    createRow() {
        return SequenceRowTemplate.render(this.actionneurs);
    }

    removeRow(target) {
        target.remove();
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
        });
    }
}
