export class SequenceRowTemplate {
    render(actionneurs) {
        let template = `
<div class="col s2">
    <label for="actionneur-id-" class="active">ItemId</label>
    <input type="text" name="actionneurs[id][]" id="actionneur-id-" value="" readonly="" required="">
</div>

<div class="col s6">
    <label for="actionneur-select-">Actionneur</label>
    <div class="select-wrapper"><span class="caret">▼</span>
        <select name="actionneurs[actionneurid][]" id="actionneur-select-">`;
        for (let actionneur of actionneurs) {
            template += `<option value="${actionneur.id}" >${actionneur.nom}</option>`
        }
        template += `</select>
    </div>
</div>

<div class="col s2">
    <label for="actionneur-etat-" class="active">Etat</label>
    <input type="number" name="actionneurs[etat][]" id="actionneur-etat-" value="0" min="0" max="255" step="1">
</div>

<label for"scenario-sequence-delete-"="">*</label>
<i id="scenario-sequence-delete-" class="material-icons secondaryTextColor col s2">delete</i>
`;
        return template;
    }
}
