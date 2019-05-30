export class ActionneurRowTemplate {
    static render(action, actionneurs, idSelected = null) {
        if (!action || !actionneurs) {
            return;
        }
        let template = `
<div class="col s12">
    <label for="action-select">Actionneur</label>
    <div class="select-wrapper"><span class="caret">▼</span>
        <select name="actionneurId" id="actionneur-select">`;
        for (let actionneur of actionneurs) {
            let selected = '';
            if (actionneur.id === idSelected) {
                selected = 'selected';
            }
            template += `<option value="${actionneur.id}" ${selected}>${actionneur.nom}</option>`
        }
        template += `</select>
    </div>
</div>
`;
        return template;
    }
}
