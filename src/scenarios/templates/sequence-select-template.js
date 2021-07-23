export class SequenceRowTemplate {
    static render(scenario, sequences, scenarioSequenceId, idSelected = null) {
        if (!scenario || !sequences) {
            return;
        }
        let template = `
<div class="col s6">
    <label for="sequence-select-${scenarioSequenceId}">Sequence</label>
    <div class="select-wrapper"><span class="caret">▼</span>
        <select name="sequence-${scenarioSequenceId}" id="sequence-select-${scenarioSequenceId}">`;
        for (let sequence of sequences) {
            let selected = '';
            if (sequence.id === idSelected) {
                selected = 'selected';
            }
            template += `<option value="${sequence.id}" ${selected}>${sequence.nom}</option>`
        }
        template += `</select>
    </div>
</div>

<i id="scenario-sequence-delete-${scenarioSequenceId}" data-id="${scenarioSequenceId}" class="material-icons secondaryTextColor col s2 delete">delete</i>
`;
        return template;
    }
}
