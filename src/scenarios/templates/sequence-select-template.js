export class SequenceRowTemplate {
    static render(scenario, sequences) {
        if (!scenario || !sequences) {
            return;
        }
        console.log(scenario,sequences);
        let template = `
<div class="col s6">
    <label for="sequence-select-">Sequence</label>
    <div class="select-wrapper"><span class="caret">▼</span>
        <select name="sequences[sequenceid][]" id="sequence-select-">`;
        for (let sequence of sequences) {
            template += `<option value="${sequence.id}" >${sequence.nom}</option>`
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
