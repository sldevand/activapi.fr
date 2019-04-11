export class SequenceRowTemplate {
    static render(scenario, sequences, index, idSelected = null) {
        if (!scenario || !sequences) {
            return;
        }
        let template = `
<div class="col s6">
    <label for="sequence-select-${index}">Sequence</label>
    <div class="select-wrapper"><span class="caret">▼</span>
        <select name="sequence-${index}" id="sequence-select-${index}">`;
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

<label class="active" for="scenario-sequence-delete">*</label>
<i id="scenario-sequence-delete" class="material-icons secondaryTextColor col s2 delete">delete</i>
`;
        return template;
    }
}
