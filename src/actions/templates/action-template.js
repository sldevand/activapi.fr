export class ActionTemplate {
    static render(action) {
        if (!action || action.error) {
            action.id = 0;
            action.nom = '';
        }
        action.id = action.id || 0;
        action.nom = action.nom || '';

        return `
    <div class="row">
        <div class="col s8">
            <label for="action-name-${action.id}" class="active">Nom</label>
            <input type="text" name="nom" id="action-name-${action.id}" value="${action.nom}" required>
        </div>
    </div>
    <div class="row">
        <div id="actionneur" class="col s6"></div>
        <div class="col s3">
            <label for="etat" class="active">Etat</label>
            <input type="number" name="etat" id="etat" value="${action.etat}" required>
        </div>
         <div class="col s3">
            <label for="timeout" class="active">Timeout</label>
            <input type="number" name="timeout" id="timeout" value="${action.timeout}" required>
        </div>
    </div>
`;
    }
}
