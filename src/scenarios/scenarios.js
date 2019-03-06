import {SequenceRowTemplate} from './sequenceRow';

export class Scenarios {

    constructor() {

    }


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
            })
            .then(() => this.render())
            .catch(err => console.log(err))
    }

    render() {


    }

    addRow() {
        const sequences = document.querySelector('#sequences');
        const elt = document.createElement('div')
        elt.classList.add('row');
        elt.innerHTML = this.createRow();
        sequences.appendChild(elt);
        $('select').material_select();
    }

    createRow() {
        const template = new SequenceRowTemplate();
        return template.render(this.actionneurs);
    }

    removeRow() {

    }

    initSequenceAddListener() {
        const sequenceAdd = document.querySelector('#sequence-add');
        sequenceAdd.addEventListener('click', (e) => {
            this.addRow();
        });
    }
}
