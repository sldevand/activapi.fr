export class ActionRowTemplate {
    static render(sequence, actions, sequenceActionId, idSelected = null) {
        if (!sequence || !actions) {
            return;
        }
        let template = `
<div class="col s6">
    <label for="action-select-${sequenceActionId}">Action</label>
    <div class="select-wrapper"><span class="caret">▼</span>
        <select name="action-${sequenceActionId}" id="action-select-${sequenceActionId}">`;
        for (let action of actions) {
            let selected = '';
            if (action.id === idSelected) {
                selected = 'selected';
            }
            template += `<option value="${action.id}" ${selected}>${action.nom}</option>`
        }
        template += `</select>
    </div>
</div>

<i id="sequence-action-delete" data-id="${sequenceActionId}" class="material-icons secondaryTextColor col s2 delete">delete</i>
`;
        return template;
    }
}
