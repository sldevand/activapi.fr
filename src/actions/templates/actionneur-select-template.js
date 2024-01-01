export class ActionneurRowTemplate {
    static render(actionneurs, idSelected = 0) {
        if (!actionneurs) {
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
